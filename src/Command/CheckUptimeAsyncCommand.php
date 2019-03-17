<?php
namespace App\Command;

use App\Entity\Site;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use GuzzleHttp\Client;

class CheckUptimeAsyncCommand extends Command
{
    protected static $defaultName = 'app:check-uptime-async';
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commandStart = microtime(true);
        $guzzle = new Client();
        $siteRepository = $this->em->getRepository(Site::class);
        $sites = $siteRepository->findAll();

        $promises = [];
        foreach ($sites as $site) {
            $crawlStart = microtime(true);
            $promises[] = $guzzle->requestAsync(
                'GET',
                $site->getUrl(),
                [
                    'headers' => [
                        'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36'
                    ],
                    'timeout' => 10
                ]
            )->then(
                function (ResponseInterface $response) use ($site, $crawlStart) {
                    $this->fulfilled($response, $site, $crawlStart);
                },
                function (RequestException $exception) use ($site) {
                    $this->rejected($exception, $site);
                }
            );
        }

        foreach ($promises as $promise) {
            $promise->wait();
        }

        $commandEnd = microtime(true);
        $duration = $commandEnd - $commandStart;
        $output->writeln('Command execution time: ' . $duration . ' seconds');
    }

    protected function fulfilled(ResponseInterface $response, Site $site, $crawlStart)
    {
        $crawlEnd = microtime(true);
        $requestTime = $crawlEnd - $crawlStart;
        $site->setResponseTimeLatest($requestTime);
        $this->em->flush();
        if ($requestTime > 5) {
            if ($site->getStatus() !== 'slow') {
                $site->setStatus('slow');
                $this->em->flush();
            }
            return;
        }

        if ($response->getStatusCode() !== 200) {
            if ($site->getStatus() !== 'down') {
                $site->setStatus('down');
                $this->em->flush();
            }
            return;
        }

        if ($site->getStatus() !== 'up') {
            $site->setStatus('up');
            $this->em->flush();
        }
    }

    protected function rejected(RequestException $exception, Site $site)
    {
        if ($site->getStatus() !== 'down') {
            $site->setStatus('down');
            $this->em->flush();
        }
    }
}

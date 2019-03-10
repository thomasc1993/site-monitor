<?php
namespace App\Command;

use App\Entity\Site;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use GuzzleHttp\Client;

class CheckUptimeCommand extends Command
{
    protected static $defaultName = 'app:check-uptime';
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
        foreach ($sites as $site) {
            try {
                $timeStart = microtime(true);
                $request = $guzzle->request(
                    'GET',
                    $site->getUrl(),
                    [
                        'timeout' => 10
                    ]
                );
                $timeEnd = microtime(true);

                $requestTime = $timeEnd - $timeStart;
                if ($requestTime > 5) {
                    if ($site->getStatus() !== 'slow') {
                        $site->setStatus('slow');
                        $this->em->flush();
                    }
                    continue;
                }

                if ($request->getStatusCode() !== 200) {
                    if ($site->getStatus() !== 'down') {
                        $site->setStatus('down');
                        $this->em->flush();
                    }
                    continue;
                }

                if ($site->getStatus() !== 'up') {
                    $site->setStatus('up');
                    $this->em->flush();
                }
            } catch (RequestException $e) {
                if ($site->getStatus() !== 'down') {
                    $site->setStatus('down');
                    $this->em->flush();
                }
            }
        }

        $commandEnd = microtime(true);
        $duration = $commandEnd - $commandStart;
        $output->writeln('Command execution time: ' . $duration . ' seconds');
    }
}

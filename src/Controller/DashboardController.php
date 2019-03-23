<?php
namespace App\Controller;

use App\Repository\SiteRepository;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Site;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

final class DashboardController extends AbstractController
{
    private $siteRepository;

    public function __construct(SiteRepository $siteRepository)
    {
        $this->siteRepository = $siteRepository;
    }

    public function renderDashboard(): Response
    {
        $sites = $this->siteRepository->findAllSortedByStatusAndName();
        $serializer = $this->getSerializer();
        $jsonSites = $serializer->serialize($sites, 'json');

        return $this->render('dashboard/index.html.twig', [
            'sites' => $jsonSites
        ]);
    }

    public function crawlSites(KernelInterface $kernel): JsonResponse
    {
        $application = new Application($kernel);
        $application->setAutoExit(false);
        $input = new ArrayInput([
            'command' => 'app:check-uptime-async'
        ]);
        $output = new NullOutput();
        $application->run($input, $output);

        $sites = $this->siteRepository->findAllSortedByStatusAndName();
        $serializer = $this->getSerializer();
        $jsonSites = $serializer->serialize($sites, 'json');

        return new JsonResponse($jsonSites, 200, [], true);
    }

    private function getSerializer(): Serializer
    {
        $normalizers = [new ObjectNormalizer()];
        $encoders = [new JsonEncoder()];
        return new Serializer($normalizers, $encoders);
    }
}

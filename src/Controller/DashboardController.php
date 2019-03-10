<?php
namespace App\Controller;

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
    public function display(): Response
    {
        $serializer = $this->getSerializer();
        $siteRepository = $this->getDoctrine()->getRepository(Site::class);
        $sites = $siteRepository->findBy([], [
            'status' => 'asc',
            'name' => 'asc'
        ]);
        $jsonSites = $serializer->serialize($sites, 'json');

        return $this->render('dashboard/index.html.twig', [
            'sites' => $jsonSites
        ]);
    }

    public function edit($id): Response
    {
        $siteRepository = $this->getDoctrine()->getRepository(Site::class);
        $site = $siteRepository->find($id);
        $jsonSite = $this->getSerializer()->serialize($site, 'json');

        return $this->render('dashboard/edit.html.twig', [
            'siteId' => $id,
            'site' => $jsonSite
        ]);
    }

    public function save(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());

        if (!$this->isCsrfTokenValid('save-site', $data->token)) {
            return $this->json(['error' => 'Token not valid.']);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $siteRepository = $this->getDoctrine()->getRepository(Site::class);

        $site = $siteRepository->find($data->id);
        $site->setName($data->name);
        $site->setUrl($data->url);
        $site->setCms($data->cms);
        $site->setAdminUrl($data->admin_url);
        $entityManager->flush();

        return $this->json([
            'response' => 'Site saved.'
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

        $serializer = $this->getSerializer();
        $siteRepository = $this->getDoctrine()->getRepository(Site::class);
        $sites = $siteRepository->findBy([], [
            'status' => 'asc',
            'name' => 'asc'
        ]);
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

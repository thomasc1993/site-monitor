<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Site;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

final class IndexController extends AbstractController
{
    public function index(): Response
    {
        $normalizers = [new ObjectNormalizer()];
        $encoders = [new JsonEncoder()];
        $serializer = new Serializer($normalizers, $encoders);
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
}

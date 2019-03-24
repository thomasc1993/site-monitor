<?php
namespace App\Controller;

use App\Entity\Site;
use App\Repository\SiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SiteController extends AbstractController
{
    private $siteRepository;

    public function __construct(SiteRepository $siteRepository)
    {
        $this->siteRepository = $siteRepository;
    }

    public function edit($slug): Response
    {
        $site = $this->siteRepository->findOneBy(['slug' => $slug]);

        return $this->render('dashboard/edit.html.twig', [
            'site' => $site
        ]);
    }

    public function add(): Response
    {
        return $this->render('dashboard/new.html.twig', []);
    }

    public function updateJson(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());

        if (!$this->isCsrfTokenValid('save-site', $data->token) || !isset($data->token)) {
            return $this->json(['error' => 'Token not valid.'], 400);
        }

        $site = $this->siteRepository->find($data->id);
        if (!$site) {
            return $this->json([
                'error' => 'Site with ID ' . $data->id . ' not found.'
            ], 404);
        }

        $this->setSiteData($site, $data);
        $this->getDoctrine()->getManager()->flush();

        return $this->json([
            'response' => 'Site saved.'
        ]);
    }

    public function saveJson(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());

        if (!$this->isCsrfTokenValid('save-site', $data->token) || !isset($data->token)) {
            return $this->json(['error' => 'Token not valid.'], 400);
        }

        $site = $this->setSiteData(new Site(), $data);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($site);
        $entityManager->flush();

        return $this->json([
            'response' => 'Site saved.'
        ]);
    }

    /**
     * Sets Site properties from a set of data.
     * @param Site $site
     * @param Object $data
     * @return Site the site entity with updated property values
     */
    private function setSiteData(Site $site, Object $data): Site
    {
        $site->setName($data->name);
        $site->setStatus('up');
        $site->setUrl($data->url);
        $site->setCms($data->cms ? $data->cms : 'none');
        $site->setAdminUrl($data->admin_url);

        return $site;
    }
}

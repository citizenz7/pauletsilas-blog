<?php

namespace App\Controller;

use App\Repository\SearchPageRepository;
use App\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RechercheController extends AbstractController
{
    #[Route('/recherche', name: 'app_recherche')]
    public function index(
        SettingRepository $settingRepository,
        SearchPageRepository $searchPageRepository
    ): Response
    {
        $settings = $settingRepository->findOneBy([]);

        $recherche = $searchPageRepository->findOneBy([]);

        return $this->render('recherche/index.html.twig', [
            'settings' => $settings,
            'recherche' => $recherche,
            'seoTitle' => html_entity_decode($recherche->getSeoTitle()),
            'seoDescription' => html_entity_decode($recherche->getSeoDescription()),
            'seoUrl' => $recherche->getSlug(),
            'pageTitle' => $recherche->getTitle()
        ]);
    }
}

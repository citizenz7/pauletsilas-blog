<?php

namespace App\Controller;

use App\Repository\ConfidentialitePageRepository;
use App\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ConfidentialiteController extends AbstractController
{
    #[Route('/confidentialite', name: 'app_confidentialite')]
    public function index(
        SettingRepository $settingRepository,
        ConfidentialitePageRepository $confidentialitePageRepository
    ): Response
    {
        $settings = $settingRepository->findOneBy([]);

        $confidentialitePage = $confidentialitePageRepository->findOneBy([]);

        return $this->render('confidentialite/index.html.twig', [
            'settings' => $settings,
            'confidentialitePage' => $confidentialitePage,
            'seoTitle' => html_entity_decode($confidentialitePage->getSeoTitle()),
            'seoDescription' => html_entity_decode($confidentialitePage->getSeoDescription()),
            'seoUrl' => $confidentialitePage->getSlug(),
            'pageTitle' => $confidentialitePage->getTitle()
        ]);
    }
}

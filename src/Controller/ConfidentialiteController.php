<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ConfidentialitePageRepository;
use App\Repository\SettingRepository;
use App\Repository\SocialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ConfidentialiteController extends AbstractController
{
    #[Route('/politique-confidentialite', name: 'app_confidentialite')]
    public function index(
        SettingRepository $settingRepository,
        ConfidentialitePageRepository $confidentialitePageRepository,
        CategoryRepository $categoryRepository,
        SocialRepository $socialRepository
    ): Response
    {
        $settings = $settingRepository->findOneBy([]);

        $confidentialitePage = $confidentialitePageRepository->findOneBy([]);

        $categories = $categoryRepository->findBy([], ['title' => 'ASC']);

        $socials = $socialRepository->findBy(['active' => true], []);

        return $this->render('confidentialite/index.html.twig', [
            'settings' => $settings,
            'confidentialitePage' => $confidentialitePage,
            'categories' => $categories,
            'socials' => $socials,
            'seoTitle' => html_entity_decode($confidentialitePage->getSeoTitle()),
            'seoDescription' => html_entity_decode($confidentialitePage->getSeoDescription()),
            'seoUrl' => $confidentialitePage->getSlug(),
            'pageTitle' => $confidentialitePage->getTitle()
        ]);
    }
}

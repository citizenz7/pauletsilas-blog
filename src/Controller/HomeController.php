<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\HomePageRepository;
use App\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        SettingRepository $settingRepository,
        ArticleRepository $articleRepository,
        HomePageRepository $homePageRepository
    ): Response
    {
        $settings = $settingRepository->findOneBy([]);

        $articles = $articleRepository->findBy(['active' => true], ['postedAt' => 'DESC'], 6);

        $homePage = $homePageRepository->findOneBy([]);

        return $this->render('home/index.html.twig', [
            'settings' => $settings,
            'articles' => $articles,
            'homePage' => $homePage,
            'seoTitle' => html_entity_decode($homePage->getSeoTitle()),
            'seoDescription' => html_entity_decode($homePage->getSeoDescription()),
            'seoUrl' => $homePage->getSlug(),
            'pageTitle' => $homePage->getTitle()
        ]);
    }
}

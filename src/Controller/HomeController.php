<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\HomePageRepository;
use App\Repository\SettingRepository;
use App\Repository\SocialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        SettingRepository $settingRepository,
        ArticleRepository $articleRepository,
        HomePageRepository $homePageRepository,
        CategoryRepository $categoryRepository,
        SocialRepository $socialRepository
    ): Response
    {
        $settings = $settingRepository->findOneBy([]);

        $articles = $articleRepository->findBy(['active' => true], ['postedAt' => 'DESC'], 6);

        $lastArticles = $articleRepository->findBy(['active' => true], ['postedAt' => 'DESC'], 3);

        $categories = $categoryRepository->findBy([], ['title' => 'ASC']);

        $homePage = $homePageRepository->findOneBy([]);

        $socials = $socialRepository->findBy(['active' => true], []);

        return $this->render('home/index.html.twig', [
            'settings' => $settings,
            'articles' => $articles,
            'lastArticles' => $lastArticles,
            'homePage' => $homePage,
            'categories' => $categories,
            'socials' => $socials,
            'seoTitle' => html_entity_decode($homePage->getSeoTitle()),
            'seoDescription' => html_entity_decode($homePage->getSeoDescription()),
            'seoUrl' => $homePage->getSlug(),
            'pageTitle' => $homePage->getTitle()
        ]);
    }
}

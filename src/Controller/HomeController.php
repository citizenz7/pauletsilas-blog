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

        // Récupérer les 6 derniers articles actifs triés par date de publication
        $articles = $articleRepository->findBy(['active' => true], ['postedAt' => 'DESC'], 6);

        // Récupérer tous les articles actifs triés par date de publication
        $lastArticles = $articleRepository->findBy(['active' => true], ['postedAt' => 'DESC']);

        // Récupérer tous les fichiers de tous les articles
        $allFiles = [];
        foreach ($lastArticles as $article) {
            foreach ($article->getFichiers() as $file) {
                $allFiles[] = [
                    'article' => $article,
                    'file' => $file,
                ];
            }
        }

        // Trier les fichiers par id
        usort($allFiles, function ($a, $b) {
            return $b['file']->getId() <=> $a['file']->getId();
        });

        // Sélectionner les 5 derniers fichiers
        $lastFiles = array_slice($allFiles, 0, 5);

        // Récupérer toutes les images de tous les articles
        $allPics = [];
        foreach ($lastArticles as $article) {
            foreach ($article->getMediapic() as $pic) {
                $allPics[] = [
                    'article' => $article,
                    'pic' => $pic,
                ];
            }
        }

        // Trier les images par id
        usort($allPics, function ($a, $b) {
            return $b['pic']->getId() <=> $a['pic']->getId();
        });

        // Sélectionner les 6 dernières images
        $lastPics = array_slice($allPics, 0, 6);

        $categories = $categoryRepository->findBy([], ['title' => 'ASC']);

        $homePage = $homePageRepository->findOneBy([]);

        $socials = $socialRepository->findBy(['active' => true], []);

        return $this->render('home/index.html.twig', [
            'settings' => $settings,
            'articles' => $articles,
            'lastArticles' => $lastArticles,
            'lastFiles' => $lastFiles,
            'lastPics' => $lastPics,
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

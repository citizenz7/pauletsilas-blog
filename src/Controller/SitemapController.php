<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\SettingRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SitemapController extends AbstractController
{
    #[Route('/sitemap.xml', name: 'app_sitemap', defaults: ['_format', 'xml'])]
    public function index(
        Request $request,
        SettingRepository $settingRepository,
        CategoryRepository $categoryRepository,
        ArticleRepository $articleRepository,
        UserRepository $userRepository
    ): Response
    {
        $settings = $settingRepository->findOneBy([]);

        $categories = $categoryRepository->findAll();

        $articles = $articleRepository->findBy(['active' => true], []);

        $hostname = $request->getSchemeAndHttpHost();

        $users = $userRepository->findBy(['active' => true], ['lastname' => 'ASC']);

        $lastmod = date('Y-m-d');

        // Initialisation du tableau des URL
        $urls = [];

        /* **************************************************
            Tableau des URL des pages simples du site
        ************************************************** */
        // Homepage
        $urls[] = ['loc' => $this->generateUrl('app_home'), 'lastmod' => $lastmod];

        // Contact
        $urls[] = ['loc' => $this->generateUrl('app_contact'), 'lastmod' => $lastmod];

        // A propos
        $urls[] = ['loc' => $this->generateUrl('app_apropos'), 'lastmod' => $lastmod];

        // Recherche
        $urls[] = ['loc' => $this->generateUrl('app_recherche'), 'lastmod' => $lastmod];

        // CGU
        $urls[] = ['loc' => $this->generateUrl('app_cgu'), 'lastmod' => $lastmod];

        // Confidentialite
        $urls[] = ['loc' => $this->generateUrl('app_confidentialite'), 'lastmod' => $lastmod];

        // Blog
        $urls[] = ['loc' => $this->generateUrl('app_article_index'), 'lastmod' => $lastmod];

        // Boucle sur toutes les catégories
        foreach($categoryRepository->findAll() as $category) {
            $urls[] = ['loc' => $this->generateUrl('app_category_show', ['slug' => $category->getSlug()]), 'lastmod' => $lastmod];
        }

        // Boucle sur tous les articles
        foreach($articleRepository->findAll() as $article) {
            $urls[] = ['loc' => $this->generateUrl('app_article_show', ['slug' => $article->getSlug()]), 'lastmod' => $lastmod];
        }

        // Boucle sur tous les utilisateurs authors
        foreach($users as $user) {
            $urls[] = ['loc' => $this->generateUrl('app_user_show', ['slug' => $user->getSlug()]), 'lastmod' => $lastmod];
        }

        // Create the XML response
        $response = new Response(
            $this->renderView('sitemap/index.html.twig', [
                'urls' => $urls,
                'hostname' => $hostname,
                'settings' => $settings,
                'categories' => $categories,
                'articles' => $articles
            ]),
            200
        );

        // Add headers
        $response->headers->set('Content-Type', 'application/xml');

        // Send the response
        return $response;
    }
}

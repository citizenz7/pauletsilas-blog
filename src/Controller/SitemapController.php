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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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

        $lastmodPage = date('Y-m-d');

        // Initialisation du tableau des URL
        $urls = [];

        /* **************************************************
            Tableau des URL des pages simples du site
        ************************************************** */
        // Homepage
        $urls[] = [
            'loc' => $this->generateUrl('app_home'),
            'lastmod' => $lastmodPage,
            'changefreq' => 'weekly',
            'priority' => '0.5',
        ];

        // Contact
        $urls[] = [
            'loc' => $this->generateUrl('app_contact'),
            'lastmod' => $lastmodPage,
            'changefreq' => 'weekly',
            'priority' => '0.5',
        ];

        // A propos
        $urls[] = [
            'loc' => $this->generateUrl('app_apropos'),
            'lastmod' => $lastmodPage,
            'changefreq' => 'weekly',
            'priority' => '0.5',
        ];

        // Recherche
        $urls[] = [
            'loc' => $this->generateUrl('app_recherche'),
            'lastmod' => $lastmodPage,
            'changefreq' => 'weekly',
            'priority' => '0.5',
        ];

        // CGU
        $urls[] = [
            'loc' => $this->generateUrl('app_cgu'),
            'lastmod' => $lastmodPage,
            'changefreq' => 'weekly',
            'priority' => '0.5',
        ];

        // Confidentialite
        $urls[] = [
            'loc' => $this->generateUrl('app_confidentialite'),
            'lastmod' => $lastmodPage,
            'changefreq' => 'weekly',
            'priority' => '0.5',
        ];

        // Blog
        $urls[] = [
            'loc' => $this->generateUrl('app_article_index'),
            'lastmod' => $lastmodPage,
            'changefreq' => 'weekly',
            'priority' => '0.5',
        ];

        // Boucle sur toutes les catégories
        foreach($categoryRepository->findAll() as $category) {
            $urls[] = [
                'loc' => $this->generateUrl('app_category_show', ['slug' => $category->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL),
                'lastmod' => $lastmodPage,
                'changefreq' => 'weekly',
                'priority' => '0.5',
            ];
        }

        // Boucle sur tous les articles
        foreach($articleRepository->findAll() as $article) {
            // Set lastmod to the updatedAt of the article
            $lastmod = $article->getUpdatedAt()->format('Y-m-d');
            // If updatedAt does not exists
            if ($lastmod === null) {
                $lastmod = $article->getPostedAt()->format('Y-m-d');
            }
            $urls[] = [
                'loc' => $this->generateUrl('app_article_show', ['slug' => $article->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL),
                'lastmod' => $lastmod,
                'changefreq' => 'weekly',
                'priority' => '0.5',
            ];
        }

        // Boucle sur tous les utilisateurs authors
        foreach($users as $user) {
            $urls[] = [
                'loc' => $this->generateUrl('app_user_show', ['slug' => $user->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL),
                'lastmod' => $lastmodPage,
                'changefreq' => 'weekly',
                'priority' => '0.5',
            ];
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

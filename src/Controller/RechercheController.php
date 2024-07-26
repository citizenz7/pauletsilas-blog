<?php

namespace App\Controller;

use App\Form\RechercheArticleType;
use App\Repository\ArticleRepository;
use App\Repository\SettingRepository;
use App\Repository\SearchPageRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RechercheController extends AbstractController
{
    #[Route('/recherche', name: 'app_recherche')]
    public function index(
        SettingRepository $settingRepository,
        SearchPageRepository $searchPageRepository,
        ArticleRepository $articleRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response
    {
        $settings = $settingRepository->findOneBy([]);
        $recherche = $searchPageRepository->findOneBy([]);

        $searchForm = $this->createForm(RechercheArticleType::class);
        $searchForm->handleRequest($request);

        $donnees = $articleRepository->findArticles();

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $title = $searchForm->getData()->getTitle();
            $donnees = $articleRepository->search($title);
        }

        $articles = $paginator->paginate(
            $donnees, // Doctrine Query, not results
            $request->query->getInt('page', 1), // Define the page parameter
            10 // Items per page
        );

        return $this->render('recherche/index.html.twig', [
            'settings' => $settings,
            'recherche' => $recherche,
            'searchForm' => $searchForm,
            'articles' => $articles,
            'seoTitle' => html_entity_decode($recherche->getSeoTitle()),
            'seoDescription' => html_entity_decode($recherche->getSeoDescription()),
            'seoUrl' => $recherche->getSlug(),
            'pageTitle' => $recherche->getTitle()
        ]);
    }
}

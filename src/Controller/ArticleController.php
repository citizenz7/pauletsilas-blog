<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\SettingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/articles')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article_index', methods: ['GET'])]
    public function index(
        ArticleRepository $articleRepository,
        CategoryRepository $categoryRepository,
        SettingRepository $settingRepository,
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ): Response {
        $settings = $settingRepository->findOneBy([]);

        $categories = $categoryRepository->findAll();

        // Pagination
        $dql = "SELECT a FROM App\Entity\Article a WHERE a.active = true ORDER BY a.postedAt DESC";
        $query = $em->createQuery($dql);

        $articles = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            6 /*limit per page*/
        );

        // Group articles by author's first name
        $allArticles = $articleRepository->findBy(['active' => true], []);

        $groupedArticles = [];

        foreach ($allArticles as $article) {
            $authorFirstName = $article->getAuthor()->getFirstName();
            if (!isset($groupedArticles[$authorFirstName])) {
                $groupedArticles[$authorFirstName] = [];
            }
            $groupedArticles[$authorFirstName][] = $article;
        }

        return $this->render('article/index.html.twig', [
            'settings' => $settings,
            'articles' => $articles,
            'categories' => $categories,
            'groupedArticles' => $groupedArticles
        ]);
    }

    #[Route('/articles/asc', name: 'app_article_index_asc', methods: ['GET'])]
    public function indexAsc(
        ArticleRepository $articleRepository,
        CategoryRepository $categoryRepository,
        SettingRepository $settingRepository,
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ): Response {
        $settings = $settingRepository->findOneBy([]);

        $categories = $categoryRepository->findAll();

        // Pagination
        $dql = "SELECT a FROM App\Entity\Article a WHERE a.active = true ORDER BY a.postedAt ASC";
        $query = $em->createQuery($dql);

        $articles = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            6 /*limit per page*/
        );

        // Group articles by author's first name
        $allArticles = $articleRepository->findBy(['active' => true], []);

        $groupedArticles = [];

        foreach ($allArticles as $article) {
            $authorFirstName = $article->getAuthor()->getFirstName();
            if (!isset($groupedArticles[$authorFirstName])) {
                $groupedArticles[$authorFirstName] = [];
            }
            $groupedArticles[$authorFirstName][] = $article;
        }

        return $this->render('article/index.html.twig', [
            'settings' => $settings,
            'articles' => $articles,
            'categories' => $categories,
            'groupedArticles' => $groupedArticles
        ]);
    }


    #[Route('/articles/desc', name: 'app_article_index_desc', methods: ['GET'])]
    public function indexDesc(
        ArticleRepository $articleRepository,
        CategoryRepository $categoryRepository,
        SettingRepository $settingRepository,
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ): Response {
        $settings = $settingRepository->findOneBy([]);

        $categories = $categoryRepository->findAll();

        // Pagination
        $dql = "SELECT a FROM App\Entity\Article a WHERE a.active = true ORDER BY a.postedAt DESC";
        $query = $em->createQuery($dql);

        $articles = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            6 /*limit per page*/
        );

        // Group articles by author's first name
        $allArticles = $articleRepository->findBy(['active' => true], []);

        $groupedArticles = [];

        foreach ($allArticles as $article) {
            $authorFirstName = $article->getAuthor()->getFirstName();
            if (!isset($groupedArticles[$authorFirstName])) {
                $groupedArticles[$authorFirstName] = [];
            }
            $groupedArticles[$authorFirstName][] = $article;
        }

        return $this->render('article/index.html.twig', [
            'settings' => $settings,
            'articles' => $articles,
            'categories' => $categories,
            'groupedArticles' => $groupedArticles
        ]);
    }


    // #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $article = new Article();
    //     $form = $this->createForm(ArticleType::class, $article);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($article);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('article/new.html.twig', [
    //         'article' => $article,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{slug}', name: 'app_article_show', methods: ['GET'])]
    public function show(
        // Article $article
        ArticleRepository $articleRepository,
        SettingRepository $settingRepository,
        string $slug
    ): Response
    {
        $settings = $settingRepository->findOneBy([]);

        $article = $articleRepository->findOneBy(['slug' => $slug]);

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'settings' => $settings
        ]);
    }

    // #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(ArticleType::class, $article);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('article/edit.html.twig', [
    //         'article' => $article,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    // public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->getPayload()->getString('_token'))) {
    //         $entityManager->remove($article);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    // }
}

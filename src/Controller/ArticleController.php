<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\SettingRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        SettingRepository $settingRepository
    ): Response {
        $settings = $settingRepository->findOneBy([]);

        $articles = $articleRepository->findBy(['active' => true], ['postedAt' => 'DESC']);

        return $this->render('article/index.html.twig', [
            'settings' => $settings,
            'articles' => $articles
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
        $slug
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

<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\SettingRepository;
use App\Repository\SocialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/commentaires')]
class CommentController extends AbstractController
{
    // #[Route('/', name: 'app_comment_index', methods: ['GET'])]
    // public function index(CommentRepository $commentRepository): Response
    // {
    //     return $this->render('comment/index.html.twig', [
    //         'comments' => $commentRepository->findAll(),
    //     ]);
    // }

    // #[Route('/new', name: 'app_comment_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $comment = new Comment();
    //     $form = $this->createForm(Comment1Type::class, $comment);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($comment);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_comment_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('comment/new.html.twig', [
    //         'comment' => $comment,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{id}', name: 'app_comment_show', methods: ['GET'])]
    public function show(
        Comment $comment,
        SettingRepository $settingRepository,
        CategoryRepository $categoryRepository,
        SocialRepository $socialRepository
    ): Response{
        $settings = $settingRepository->findOneBy([]);

        $categories = $categoryRepository->findBy([], ['title' => 'ASC']);

        $socials = $socialRepository->findBy(['active' => true], []);

        return $this->render('comment/show.html.twig', [
            'comment' => $comment,
            'settings' => $settings,
            'categories' => $categories,
            'socials' => $socials,
            'seoTitle' => html_entity_decode($comment->getArticle()->getSeoTitle()),
            'seoDescription' => html_entity_decode($comment->getArticle()->getSeoDescription()),
            'seoUrl' => $comment->getArticle()->getSlug(),
            'pageTitle' => $comment->getArticle()->getTitle()
        ]);
    }

    #[Route('/{id}/edit', name: 'app_comment_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Comment $comment,
        EntityManagerInterface $entityManager,
        SettingRepository $settingRepository,
        CategoryRepository $categoryRepository,
        SocialRepository $socialRepository
    ): Response {
        $settings = $settingRepository->findOneBy([]);

        $categories = $categoryRepository->findBy([], ['title' => 'ASC']);

        $socials = $socialRepository->findBy(['active' => true], []);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // return $this->redirectToRoute('app_article_show', ['slug' => $comment->getArticle()->getSlug()], Response::HTTP_SEE_OTHER) . '#comment' . $comment->getId();
            return $this->redirect($this->generateUrl('app_article_show', ['slug' => $comment->getArticle()->getSlug()], Response::HTTP_SEE_OTHER) . '#comment' . $comment->getId());
        }

        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form,
            'settings' => $settings,
            'categories' => $categories,
            'socials' => $socials,
            'pageTitle' => $comment->getArticle()->getTitle(),
            'seoTitle' => html_entity_decode($comment->getArticle()->getSeoTitle() . ' - Commentaire'),
            'seoDescription' => html_entity_decode($comment->getArticle()->getSeoDescription()),
            'seoUrl' => $comment->getArticle()->getSlug()
        ]);
    }

    #[Route('/{id}', name: 'app_comment_delete', methods: ['POST'])]
    public function delete(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        // return $this->redirectToRoute('app_comment_index', [], Response::HTTP_SEE_OTHER);
        return $this->redirect($this->generateUrl('app_article_show', ['slug' => $comment->getArticle()->getSlug()], Response::HTTP_SEE_OTHER) . '#commentsarticle' . $comment->getId());
    }
}

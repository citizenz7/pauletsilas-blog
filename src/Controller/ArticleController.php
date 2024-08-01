<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticlePageRepository;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use App\Repository\ArticleRepository;
use App\Repository\SettingRepository;
use App\Repository\CategoryRepository;
use App\Repository\SocialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
        PaginatorInterface $paginator,
        ArticlePageRepository $articlePageRepository,
        SocialRepository $socialRepository
    ): Response {
        $settings = $settingRepository->findOneBy([]);

        $categories = $categoryRepository->findBy([], ['title' => 'ASC']);

        $articlePage = $articlePageRepository->findOneBy([]);

        $socials = $socialRepository->findBy(['active' => true], []);

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
            'articlePage' => $articlePage,
            'groupedArticles' => $groupedArticles,
            'socials' => $socials,
            'seoTitle' => html_entity_decode($articlePage->getSeoTitle()),
            'seoDescription' => html_entity_decode($articlePage->getSeoDescription()),
            'seoUrl' => $articlePage->getSlug(),
            'pageTitle' => $articlePage->getTitle()
        ]);
    }

    #[Route('/articles/asc', name: 'app_article_index_asc', methods: ['GET'])]
    public function indexAsc(
        ArticleRepository $articleRepository,
        CategoryRepository $categoryRepository,
        SettingRepository $settingRepository,
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator,
        ArticlePageRepository $articlePageRepository,
        SocialRepository $socialRepository
    ): Response {
        $settings = $settingRepository->findOneBy([]);

        $categories = $categoryRepository->findBy([], ['title' => 'ASC']);

        $articlePage = $articlePageRepository->findOneBy([]);

        $socials = $socialRepository->findBy(['active' => true], []);

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
            'articlePage' => $articlePage,
            'groupedArticles' => $groupedArticles,
            'socials' => $socials,
            'seoTitle' => html_entity_decode($articlePage->getSeoTitle()),
            'seoDescription' => html_entity_decode($articlePage->getSeoDescription()),
            'seoUrl' => $articlePage->getSlug(),
            'pageTitle' => $articlePage->getTitle()
        ]);
    }


    #[Route('/articles/desc', name: 'app_article_index_desc', methods: ['GET'])]
    public function indexDesc(
        ArticleRepository $articleRepository,
        CategoryRepository $categoryRepository,
        SettingRepository $settingRepository,
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator,
        ArticlePageRepository $articlePageRepository,
        SocialRepository $socialRepository
    ): Response {
        $settings = $settingRepository->findOneBy([]);

        $categories = $categoryRepository->findBy([], ['title' => 'ASC']);

        $articlePage = $articlePageRepository->findOneBy([]);

        $socials = $socialRepository->findBy(['active' => true], []);

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
            'articlePage' => $articlePage,
            'groupedArticles' => $groupedArticles,
            'socials' => $socials,
            'seoTitle' => html_entity_decode($articlePage->getSeoTitle()),
            'seoDescription' => html_entity_decode($articlePage->getSeoDescription()),
            'seoUrl' => $articlePage->getSlug(),
            'pageTitle' => $articlePage->getTitle()
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

    #[Route('/{slug}', name: 'app_article_show', methods: ['GET', 'POST'])]
    public function show(
        // Article $article
        ArticleRepository $articleRepository,
        SettingRepository $settingRepository,
        ArticlePageRepository $articlePageRepository,
        CategoryRepository $categoryRepository,
        SocialRepository $socialRepository,
        EntityManagerInterface $em,
        Request $request,
        MailerInterface $mailer,
        string $slug
    ): Response
    {
        $settings = $settingRepository->findOneBy([]);

        $article = $articleRepository->findOneBy(['slug' => $slug]);

        $categories = $categoryRepository->findBy([], ['title' => 'ASC']);

        $articlePage = $articlePageRepository->findOneBy([]);

        $socials = $socialRepository->findBy(['active' => true], []);

        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactFormData = $form->getData();

            $user = $this->getUser();

            $comment = new Comment();

            $comment->setContent($contactFormData['content']);
            $comment->setArticle($article);
            $comment->setAuthor($user);
            $comment->setCreatedAt(new \DateTime());

            $em->persist($comment);
            $em->flush();

            $siteName = $settings->getSiteName();
            $siteEmail = $settings->getSiteEmail();

            $email = (new Email())
            ->from($siteEmail)
            ->to(new Address($siteEmail, $siteName))
            ->subject('Nouveau commentaire sur ' . $siteName . ' pour l\'article : ' . $article->getTitle())
            ->html(
                '<h4 style="color: #00A19A;">Nouveau commentaire sur ' . $siteName . ' pour l\'article : ' . $article->getTitle() . '</h4>' .
                '<span style="color: #00A19A; font-weight: bold;">De :</span> ' . $user . '<br>' .
                '<span style="font-weight: bold; color: #00A19A;">E-mail :</span> ' . $user->getUserIdentifier(). '<br>' .
                '<p><span style="font-weight: bold; color: #00A19A;">Message</span> : <br>' . trim(nl2br($contactFormData['content'])) . '</p>',
                'text/plain'
            );

            $mailer->send($email);

            $this->addFlash('success', 'Le message a bien été envoyé. Il sera visible après validation du modérateur.');
            return $this->redirect(
                $this->generateUrl('app_article_show', ['slug' => $slug]) . '#success'
            );
        }

        $previousArticle = $articleRepository->previousArticle($article);
        $nextArticle = $articleRepository->nextArticle($article);

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'categories' => $categories,
            'settings' => $settings,
            'articlePage' => $articlePage,
            'previousArticle' => $previousArticle,
            'nextArticle' => $nextArticle,
            'form' => $form,
            'socials' => $socials,
            'seoTitle' => html_entity_decode($article->getSeoTitle()),
            'seoDescription' => html_entity_decode($article->getSeoDescription()),
            'seoUrl' => 'articles/' . $article->getSlug(),
            'pageTitle' => $article->getTitle()
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

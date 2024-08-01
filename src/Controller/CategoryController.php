<?php

namespace App\Controller;

use App\Repository\CategoryPageRepository;
use App\Repository\CategoryRepository;
use App\Repository\SettingRepository;
use App\Repository\SocialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/categories')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'app_category_index', methods: ['GET'])]
    public function index(
        CategoryRepository $categoryRepository,
        SettingRepository $settingRepository,
        CategoryPageRepository $categoryPageRepository,
        SocialRepository $socialRepository
    ): Response {
        $settings = $settingRepository->findOneBy([]);

        $categories = $categoryRepository->findBy([], ['title' => 'ASC']);

        $categoryPage = $categoryPageRepository->findOneBy([]);

        $socials = $socialRepository->findBy(['active' => true], []);

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
            'settings' => $settings,
            'categoryPage' => $categoryPage,
            'socials' => $socials,
            'seoTitle' => html_entity_decode($categoryPage->getSeoTitle()),
            'seoDescription' => html_entity_decode($categoryPage->getSeoDescription()),
            'seoUrl' => $categoryPage->getSlug(),
            'pageTitle' => $categoryPage->getTitle()
        ]);
    }

    // #[Route('/new', name: 'app_category_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $category = new Category();
    //     $form = $this->createForm(CategoryType::class, $category);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($category);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('category/new.html.twig', [
    //         'category' => $category,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{slug}', name: 'app_category_show', methods: ['GET'])]
    public function show(
        // Category $category,
        CategoryRepository $categoryRepository,
        SettingRepository $settingRepository,
        SocialRepository $socialRepository,
        string $slug
    ): Response{
        $settings = $settingRepository->findOneBy([]);

        $category = $categoryRepository->findOneBy(['slug' => $slug]);

        $categories = $categoryRepository->findBy([], ['title' => 'ASC']);

        $socials = $socialRepository->findBy(['active' => true], []);

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'categories' => $categories,
            'settings' => $settings,
            'socials' => $socials,
            'seoTitle' => html_entity_decode($category->getSeoTitle()),
            'seoDescription' => html_entity_decode($category->getSeoDescription()),
            'seoUrl' => 'categories/' . $category->getSlug(),
            'pageTitle' => $category->getTitle()
        ]);
    }

    // #[Route('/{id}/edit', name: 'app_category_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(CategoryType::class, $category);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('category/edit.html.twig', [
    //         'category' => $category,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_category_delete', methods: ['POST'])]
    // public function delete(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->getPayload()->getString('_token'))) {
    //         $entityManager->remove($category);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    // }
}

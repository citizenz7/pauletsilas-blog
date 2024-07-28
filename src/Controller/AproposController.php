<?php

namespace App\Controller;

use App\Repository\AproposPageRepository;
use App\Repository\CategoryRepository;
use App\Repository\SettingRepository;
use App\Repository\SocialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AproposController extends AbstractController
{
    #[Route('/apropos', name: 'app_apropos')]
    public function index(
        SettingRepository $settingRepository,
        AproposPageRepository $aproposPageRepository,
        CategoryRepository $categoryRepository,
        SocialRepository $socialRepository
    ): Response
    {
        $settings = $settingRepository->findOneBy([]);

        $aproposPage = $aproposPageRepository->findOneBy([]);

        $categories = $categoryRepository->findBy([], ['title' => 'ASC']);

        $socials = $socialRepository->findBy(['active' => true], []);

        return $this->render('apropos/index.html.twig', [
            'settings' => $settings,
            'apropos' => $aproposPage,
            'categories' => $categories,
            'socials' => $socials,
            'seoTitle' => html_entity_decode($aproposPage->getSeoTitle()),
            'seoDescription' => html_entity_decode($aproposPage->getSeoDescription()),
            'seoUrl' => $aproposPage->getSlug(),
            'pageTitle' => $aproposPage->getTitle()
        ]);
    }
}

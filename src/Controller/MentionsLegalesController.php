<?php

namespace App\Controller;

use App\Repository\SocialRepository;
use App\Repository\SettingRepository;
use App\Repository\CategoryRepository;
use App\Repository\MentionsPageRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MentionsLegalesController extends AbstractController
{
    #[Route('/mentions-legales', name: 'app_mentions_legales')]
    public function index(
        SettingRepository $settingRepository,
        MentionsPageRepository $mentionsPageRepository,
        CategoryRepository $categoryRepository,
        SocialRepository $socialRepository
    ): Response
    {
        $settings = $settingRepository->findoneBy([]);

        $mentionsPage = $mentionsPageRepository->findOneBy([]);

        $categories = $categoryRepository->findBy([], ['title' => 'ASC']);

        $socials = $socialRepository->findBy(['active' => true], []);

        return $this->render('mentions_legales/index.html.twig', [
            'settings' => $settings,
            'mentions' => $mentionsPage,
            'categories' => $categories,
            'socials' => $socials,
            'seoTitle' => html_entity_decode($mentionsPage->getSeoTitle()),
            'seoDescription' => html_entity_decode($mentionsPage->getSeoDescription()),
            'seoUrl' => $mentionsPage->getSlug(),
            'pageTitle' => $mentionsPage->getTitle()
        ]);
    }
}

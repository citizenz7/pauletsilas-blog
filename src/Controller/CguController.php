<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\CguPageRepository;
use App\Repository\SettingRepository;
use App\Repository\SocialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CguController extends AbstractController
{
    #[Route('/cgu', name: 'app_cgu')]
    public function index(
        SettingRepository $settingRepository,
        CguPageRepository $cguPageRepository,
        CategoryRepository $categoryRepository,
        SocialRepository $socialRepository
    ): Response
    {
        $settings = $settingRepository->findOneBy([]);

        $cguPage = $cguPageRepository->findOneBy([]);

        $categories = $categoryRepository->findBy([], ['title' => 'ASC']);

        $socials = $socialRepository->findBy(['active' => true], []);

        return $this->render('cgu/index.html.twig', [
            'settings' => $settings,
            'cguPage' => $cguPage,
            'categories' => $categories,
            'socials' => $socials,
            'seoTitle' => html_entity_decode($cguPage->getSeoTitle()),
            'seoDescription' => html_entity_decode($cguPage->getSeoDescription()),
            'seoUrl' => $cguPage->getSlug(),
            'pageTitle' => $cguPage->getTitle()
        ]);
    }
}

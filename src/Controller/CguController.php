<?php

namespace App\Controller;

use App\Repository\CguPageRepository;
use App\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CguController extends AbstractController
{
    #[Route('/cgu', name: 'app_cgu')]
    public function index(
        SettingRepository $settingRepository,
        CguPageRepository $cguPageRepository
    ): Response
    {
        $settings = $settingRepository->findOneBy([]);

        $cguPage = $cguPageRepository->findOneBy([]);

        return $this->render('cgu/index.html.twig', [
            'settings' => $settings,
            'cguPage' => $cguPage,
            'seoTitle' => html_entity_decode($cguPage->getSeoTitle()),
            'seoDescription' => html_entity_decode($cguPage->getSeoDescription()),
            'seoUrl' => $cguPage->getSlug(),
            'pageTitle' => $cguPage->getTitle()
        ]);
    }
}

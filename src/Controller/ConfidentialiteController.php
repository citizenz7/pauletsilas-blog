<?php

namespace App\Controller;

use App\Repository\ConfidentialitePageRepository;
use App\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ConfidentialiteController extends AbstractController
{
    #[Route('/confidentialite', name: 'app_confidentialite')]
    public function index(
        SettingRepository $settingRepository,
        ConfidentialitePageRepository $confidentialitePageRepository
    ): Response
    {
        $settings = $settingRepository->findOneBy([]);

        $confidentialite = $confidentialitePageRepository->findOneBy([]);

        return $this->render('confidentialite/index.html.twig', [
            'settings' => $settings,
            'confidentialite' => $confidentialite
        ]);
    }
}

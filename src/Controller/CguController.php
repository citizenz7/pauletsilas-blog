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

        $cgu = $cguPageRepository->findOneBy([]);

        return $this->render('cgu/index.html.twig', [
            'settings' => $settings,
            'cgu' => $cgu
        ]);
    }
}

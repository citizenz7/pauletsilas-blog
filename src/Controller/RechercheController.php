<?php

namespace App\Controller;

use App\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RechercheController extends AbstractController
{
    #[Route('/recherche', name: 'app_recherche')]
    public function index(
        SettingRepository $settingRepository
    ): Response
    {
        $settings = $settingRepository->findOneBy([]);

        return $this->render('recherche/index.html.twig', [
            'settings' => $settings
        ]);
    }
}

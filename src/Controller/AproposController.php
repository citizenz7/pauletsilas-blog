<?php

namespace App\Controller;

use App\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AproposController extends AbstractController
{
    #[Route('/apropos', name: 'app_apropos')]
    public function index(
        SettingRepository $settingRepository
    ): Response
    {
        $settings = $settingRepository->findOneBy([]);

        return $this->render('apropos/index.html.twig', [
            'settings' => $settings
        ]);
    }
}

<?php

namespace App\Controller;

use App\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(
        SettingRepository $settingRepository
    ): Response
    {
        $settings = $settingRepository->findOneBy([]);

        return $this->render('contact/index.html.twig', [
            'settings' => $settings
        ]);
    }
}

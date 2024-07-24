<?php

namespace App\Controller;

use App\Repository\ContactPageRepository;
use App\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(
        SettingRepository $settingRepository,
        ContactPageRepository $contactPageRepository
    ): Response
    {
        $settings = $settingRepository->findOneBy([]);

        $contact = $contactPageRepository->findOneBy([]);

        return $this->render('contact/index.html.twig', [
            'settings' => $settings,
            'contact' => $contact
        ]);
    }
}

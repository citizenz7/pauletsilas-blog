<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        SettingRepository $settingRepository,
        ArticleRepository $articleRepository
    ): Response
    {
        $settings = $settingRepository->findOneBy([]);

        $articles = $articleRepository->findBy(['active' => true], ['postedAt' => 'DESC'], 6);

        return $this->render('home/index.html.twig', [
            'settings' => $settings,
            'articles' => $articles
        ]);
    }
}

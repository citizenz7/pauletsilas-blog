<?php

namespace App\Service;

use App\Entity\Article;
use App\Repository\SettingRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;

class EmailService
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly UserRepository $userRepository,
        private readonly SettingRepository $settingRepository
    ) {}

    public function sendEmailUserArticleActivated(Article $article): void
    {
        $activeUsers = $this->userRepository->findBy(['active' => true]);

        $settings = $this->settingRepository->findOneBy([]);

        if (!$settings) {
            throw new \Exception('Settings not found.');
        }

        $siteEmail = $settings->getSiteEmail();
        $siteName = $settings->getSiteName();
        $siteUrlfull = $settings->getSiteUrlfull();
        $siteUrl = $settings->getSiteUrl();

        foreach ($activeUsers as $user) {
            $email = (new Email())
                ->from($siteEmail)
                ->to($user->getEmail())
                ->subject('Un nouvel article vient d\'être publié sur ' . $siteUrl . ' : ' . $article->getTitle())
                ->html(
                    '<p>Bonjour,</p>' .
                    '<p>Un nouvel article vient d\'être publié sur ' . $siteName . ' : <a href="' . $siteUrlfull . '/articles/' . $article->getSlug() . '">' . $article->getTitle() . '</a></p>' .
                    '<p>Fraternellement, <br> l\'équipe de <a href="' . $siteUrlfull . '">' . $siteName . '</a></p>'
                    ,'text/plain'
                );

            $this->mailer->send($email);
        }
    }
}
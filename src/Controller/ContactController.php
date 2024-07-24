<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use App\Repository\SettingRepository;
use App\Repository\ContactPageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(
        SettingRepository $settingRepository,
        ContactPageRepository $contactPageRepository,
        Request $request,
        MailerInterface $mailer
    ): Response
    {
        $settings = $settingRepository->findOneBy([]);
        $contact = $contactPageRepository->findOneBy([]);

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        $siteEmail = $settings->getSiteEmail();
        $siteName = $settings->getSiteName();

        if ($form->isSubmitted() && $form->isValid()) {
            $contactFormData = $form->getData();

            $email = (new Email())
            ->from($siteEmail)
            ->to(new Address($siteEmail, $siteName))
            ->subject('Message depuis votre site web : '. $siteName)
            ->html(
                '<h4 style="color: #007bff;">Message envoyé depuis le site web : '. $siteName . '</h4>' .
                '<span style="color: #007bff; font-weight: bold;">De :</span> ' . $contactFormData['nom'] . '<br>' .
                '<span style="font-weight: bold; color: #007bff;">E-mail :</span> ' . $contactFormData['email'] . '<br>' .
                '<p><span style="font-weight: bold; color: #007bff;">Message</span> : <br>' . trim(nl2br($contactFormData['message'])) . '</p>',
                'text/plain'
            );

            $mailer->send($email);

            $this->addFlash('success', 'Le message a bien été envoyé !');
            return $this->redirect(
                $this->generateUrl('app_contact') . '#success'
            );
        }

        return $this->render('contact/index.html.twig', [
            'settings' => $settings,
            'contact' => $contact,
            'form' => $form,
            'seoTitle' => html_entity_decode($contact->getSeoTitle()),
            'seoDescription' => html_entity_decode($contact->getSeoDescription()),
            'seoUrl' => $contact->getSlug()
        ]);
    }
}

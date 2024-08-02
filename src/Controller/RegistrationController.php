<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use App\Repository\SocialRepository;
use App\Repository\SettingRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\RequestVerifyUserEmailFormType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        SettingRepository $settingRepository,
        CategoryRepository $categoryRepository,
        SocialRepository $socialRepository
    ): Response {
        $settings = $settingRepository->findOneBy([]);

        $categories = $categoryRepository->findBy([], ['title' => 'ASC']);

        $socials = $socialRepository->findBy(['active' => true], []);

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // On recherche l'email et le nom du site dans Setting pour injecter dans le mail
            $siteEmail = $settings->getSiteEmail();
            $siteName = $settings->getSiteName();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address($siteEmail, $siteName))
                    ->to($user->getEmail())
                    ->subject('Veuillez confirmer votre adresse email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            // do anything else you need here, like send an email
            // flash message
            $this->addFlash('success', 'Votre compte a bien été créé. Un email de confirmation vous a été envoyé.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
            'settings' => $settings,
            'categories' => $categories,
            'socials' => $socials,
            'seoTitle' => 'Inscription',
            'seoDescription' => 'Inscription',
            'seoUrl' => 'register',
            'pageTitle' => 'Inscription'
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(
        Request $request,
        TranslatorInterface $translator,
        UserRepository $userRepository
    ): Response {
        $id = $request->query->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Votre adresse email a bien été vérifiée. Vous pouvez vous connecter.');

        return $this->redirectToRoute('app_home');
    }

    #[Route('/request-verify-email', name: 'app_request_verify_email')]
    public function requestVerifyUserEmail(
        Request $request,
        UserRepository $userRepository,
        SettingRepository $settingRepository,
        SocialRepository $socialRepository,
        CategoryRepository $categoryRepository
    ): Response {
        $settings = $settingRepository->findOneBy([]);

        $socials = $socialRepository->findBy(['active' => true], []);

        $categories = $categoryRepository->findBy([], ['title' => 'ASC']);

        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(RequestVerifyUserEmailFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // generate a signed url and email it to the user
            $user =  $userRepository->findOneByEmail($form->get('email')->getData());

            if ($user->isVerified() === true) {
                $this->addFlash('error', 'Votre adresse e-mail est déjà vérifiée.');
                return $this->redirectToRoute('app_request_verify_email');
            }

            // On recherche l'email et le nom du site dans Setting pour injecter dans le mail
            $siteEmail = $settings->getSiteEmail();
            $siteName = $settings->getSiteName();

            if ($user) {
                $this->emailVerifier->sendEmailConfirmation(
                    'app_verify_email',
                    $user,
                    (new TemplatedEmail())
                        ->from(new Address($siteEmail, $siteName))
                        ->to($user->getEmail())
                        ->subject('Veuillez confirmer votre adresse email')
                        ->htmlTemplate('registration/confirmation_email.html.twig')
                );
                // do anything else you need here, like flash message
                $this->addFlash('success', 'Un email de validation vous a été envoyé.');
                return $this->redirectToRoute('app_home');
            } else {
                $this->addFlash('error',  'Email inconnu.');
            }
        }

        return $this->render('registration/request_email.html.twig', [
            'form' => $form,
            'settings' => $settings,
            'socials' => $socials,
            'categories' => $categories,
            'pageTitle' => 'Demande de confirmation d\'adresse e-mail',
            'seoTitle' => 'Demande de confirmation d\'adresse e-mail',
            'seoDescription' => 'Demande de confirmation d\'adresse e-mail',
            'seoUrl' => 'request-verify-email'
        ]);
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\AproposPage;
use App\Entity\User;
use App\Entity\Media;
use App\Entity\Article;
use App\Entity\BlogPage;
use App\Entity\Fichier;
use App\Entity\Setting;
use App\Entity\Category;
use App\Entity\CguPage;
use App\Entity\ConfidentialitePage;
use App\Entity\ContactPage;
use App\Entity\HomePage;
use App\Entity\SearchPage;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;


class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private UserRepository $userRepository,
        private ArticleRepository $articleRepository,
        private Security $security
    )
    {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        //return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('admin/dashboard.html.twig', [
            'users' => $this->userRepository->findBy(['active' => true], []),
            'articles' => $this->articleRepository->findBy(['active' => true], []),
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            // ->setTitle('Pauletsilas2024');
            ->setTitle('<div style="width: 90%; padding: 2px 5px; border: solid 1px #fff; border-radius: 8px; background-color: #ffffff79;"><h1 style="font-size: clamp(20px, 2vw, 30px); font-weight: 800; color: #fff; text-align: center; margin: 0;">Pauletsilas</h1></div>')
            ->renderContentMaximized()
            ->setFaviconPath('favicon.ico');
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        if (!$user instanceof User) {
            throw new \Exception('Mauvais utilisateur.');
        }

        $image = 'uploads/img/users/' . $user->getImage();

        return parent::configureUserMenu($user)
            ->setAvatarUrl($image);
    }

    public function configureMenuItems(): iterable
    {
        $user = $this->security->getUser();

        yield MenuItem::linkToRoute('Visiter le site', 'fas fa-home', 'app_home');
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-cog');

        // -------------------------------------
        // PAGES
        // -------------------------------------
        yield MenuItem::section('Pages')
            ->setCssClass('text-warning fw-bold shadow')
            ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Page d\'accueil', 'fas fa-file-alt', HomePage::class)
            ->setAction('detail')
            ->setEntityId(1)
            ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Page du blog', 'fas fa-newspaper', BlogPage::class)
            ->setAction('detail')
            ->setEntityId(1)
            ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('A propos', 'fas fa-info', AproposPage::class)
            ->setAction('detail')
            ->setEntityId(1)
            ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Page Contact', 'fas fa-file-alt', ContactPage::class)
            ->setAction('detail')
            ->setEntityId(1)
            ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Page Recherche', 'fas fa-search', SearchPage::class)
            ->setAction('detail')
            ->setEntityId(1)
            ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Page CGU', 'fas fa-file-alt', CguPage::class)
            ->setAction('detail')
            ->setEntityId(1)
            ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Page Confidentialité', 'fas fa-file-alt', ConfidentialitePage::class)
            ->setAction('detail')
            ->setEntityId(1)
            ->setPermission('ROLE_ADMIN');

        // -------------------------------------
        // SECTIONS
        // -------------------------------------
        yield MenuItem::section('Sections')
            ->setCssClass('text-warning fw-bold shadow');
        yield MenuItem::linkToCrud('Articles', 'fas fa-newspaper', Article::class);
        yield MenuItem::linkToCrud('Catégories', 'fas fa-list', Category::class);
        yield MenuItem::linkToCrud('Images', 'fas fa-images', Media::class);
        yield MenuItem::linkToCrud('Fichiers PDF', 'fas fa-file', Fichier::class);

        // -------------------------------------
        // PARAMETRES
        // -------------------------------------
        yield MenuItem::section('Paramètres du site')
            ->setCssClass('text-warning fw-bold shadow');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class)
            ->setPermission('ROLE_ADMIN');

        if (!$user instanceof User || !$this->security->isGranted('ROLE_ADMIN')) {
            yield MenuItem::linkToCrud('Mon profil', 'fas fa-user', User::class);
        }
        yield MenuItem::linkToCrud('Configuration du site', 'fa fa-cogs', Setting::class)->setPermission('ROLE_ADMIN');
    }

        public function configureAssets(): Assets
    {
        return parent::configureAssets()
            ->addAssetMapperEntry('admin');
    }
}

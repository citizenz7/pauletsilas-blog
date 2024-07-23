<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof User) {
            $this->hashUserPassword($entityInstance);
        }
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof User) {
            $this->hashUserPassword($entityInstance);
        }
        parent::updateEntity($entityManager, $entityInstance);
    }

    private function hashUserPassword($user)
    {
        if ($user->getPassword()) {
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    $user->getPassword()
                )
            );
        }
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('lastname', 'Nom')
                ->setColumns(3),
            TextField::new('firstname', 'Prénom')
                ->setColumns(3),
            EmailField::new('email', 'Adresse e-mail')
                ->setColumns(6)
                ->setRequired(false),
            ChoiceField::new('roles', 'Rôle')
                ->setColumns(3)
                ->setChoices([
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN'
                ])
                ->allowMultipleChoices()
                ->renderAsBadges([
                    'ROLE_USER' => 'info',
                    'ROLE_ADMIN' => 'success'
                ]),
            TextField::new('password', 'Mot de passe')
                ->setColumns(3)
                ->onlyWhenCreating()
                ->setFormType(PasswordType::class),
            ImageField::new('image', 'Image de profile')
                ->setColumns(6)
                ->setBasePath('uploads/img/users')
                ->setUploadDir('public/uploads/img/users')
                ->setRequired(false)
                ->setUploadedFileNamePattern('[name]-[uuid].[extension]'),
            BooleanField::new('active', 'Actif ?')
                ->setpermission('ROLE_ADMIN')
                ->hideOnForm(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Liste des utilisateurs')
            ->setPageTitle('edit', 'Modifier un utilisateur')
            ->setPageTitle('new', 'Ajouter un utilisateur')
            ->setDefaultSort(['lastname' => 'ASC'])
            ->setPaginatorPageSize(15)
            ->showEntityActionsInlined(true)
            ->setSearchFields(['lastname', 'firstname', 'telephone', 'email'])
            ->setEntityLabelInSingular('utilisateur')
            ->setEntityLabelInPlural('utilisateurs')
            ->renderContentMaximized()
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL, Action::NEW, Action::DELETE)
            // ->add(Crud::PAGE_INDEX, Action::DETAIL, Action::DELETE)
            // On DESACTIVE le bouton NEW
            // ->disable(Action::NEW)
            ->update(Crud::PAGE_INDEX,Action::NEW,function(Action $action){
                return $action->setIcon('fas fa-newspaper pe-1')->setLabel('Ajouter un utilisateur');
            })
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function(Action $action){
                return $action->setIcon('fas fa-eye text-info')->setLabel('')->addCssClass('text-dark');
            })
            ->update(Crud::PAGE_INDEX,Action::EDIT,function(Action $action){
                return $action->setIcon('fas fa-edit text-warning')->setLabel('')->addCssClass('text-dark');
            })
            ->update(Crud::PAGE_INDEX,Action::DELETE,function(Action $action){
                return $action->setIcon('fas fa-trash text-danger')->setLabel('')->addCssClass('text-dark');
            });
    }
}
<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use Symfony\Component\Validator\Constraints\Image;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private Security $security
    )
    {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        if ($this->isGranted('ROLE_ADMIN')) {
            return $queryBuilder;
        }

        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
            ->andWhere('entity.email = :email')
            ->setParameter('email', $this->security->getUser()->getUserIdentifier());
        return $queryBuilder;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addTab('Informations'),
            BooleanField::new('active', 'Actif ?')
                ->setColumns(12)
                ->setpermission('ROLE_ADMIN'),
            TextField::new('firstname', 'Prénom')
                ->setColumns(3),
            TextField::new('lastname', 'Nom')
                ->setColumns(3),
            TextField::new('slug', 'Adresse page auteur')
                ->onlyOnIndex()
                ->setDisabled(true),
            EmailField::new('email', 'Adresse e-mail')
                ->setColumns(3),
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
                ])
                ->setPermission('ROLE_ADMIN'),

            FormField::addTab('Image de profil'),
            ImageField::new('image', 'Image de profil')
                ->setColumns(6)
                ->setBasePath('uploads/img/users')
                ->setUploadDir('public/uploads/img/users')
                ->setRequired(false)
                ->setUploadedFileNamePattern('[name]-[uuid].[extension]')
                ->setFileConstraints(new Image(
                    maxWidth: 300,
                    maxWidthMessage: 'L\'image est trop large. La largeur max est 300 px.',
                    maxHeight: 300,
                    maxHeightMessage: 'L\'image est trop grande. La hauteur max est 300 px.',
                    maxSize: '50k',
                    maxSizeMessage: 'L\'image est trop volumineuse. Le poids max est 50 Ko.',
                    mimeTypes: ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'],
                    mimeTypesMessage: 'Seuls les formats jpeg, jpg, png, webp sont acceptés.'
                )),

            FormField::addTab('Bio'),
            TextareaField::new('authorBio', 'Biographie courte')
                ->setColumns(12)
                ->hideOnIndex(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Liste des utilisateurs')->setEntityPermission('ROLE_ADMIN')
            ->setPageTitle('edit', 'Modifier un utilisateur')->setEntityPermission('ROLE_ADMIN')
            ->setPageTitle('detail', 'Profil')->setEntityPermission('ROLE_USER')

            ->setPageTitle('index', 'Mon profil')->setEntityPermission('ROLE_USER')
            ->setPageTitle('edit', 'Modifier mon profil')->setEntityPermission('ROLE_USER')

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
            ->add(Crud::PAGE_INDEX, Action::DETAIL, Action::DELETE)
            // On DESACTIVE le bouton NEW
            ->disable(Action::NEW)

            // On ajuste les permissions
            ->setPermission(Action::DELETE, 'ROLE_ADMIN')

            ->update(Crud::PAGE_INDEX, Action::DETAIL, function(Action $action){
                return $action->setIcon('fas fa-eye text-info')->setLabel('');
            })
            ->update(Crud::PAGE_INDEX,Action::EDIT, function(Action $action){
                return $action->setIcon('fas fa-edit text-warning')->setLabel('');
            })
            ->update(Crud::PAGE_INDEX,Action::DELETE, function(Action $action){
                return $action->setIcon('fas fa-trash text-danger')->setLabel('');
            });
    }
}
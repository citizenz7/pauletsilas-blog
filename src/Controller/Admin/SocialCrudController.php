<?php

namespace App\Controller\Admin;

use App\Entity\Social;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use Symfony\Component\Validator\Constraints\Image;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SocialCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Social::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            //IdField::new('id'),
            TextField::new('title', 'Titre')
                ->setColumns(6),
            TextField::new('link', 'Lien')
                ->setColumns(6),
            ImageField::new('image', 'Image')
                ->setColumns(6)
                ->setUploadDir('public/uploads/img/social')
                ->setBasePath('uploads/img/social')
                ->setUploadedFileNamePattern('[name]-[uuid].[extension]')
                ->setFileConstraints(new Image(
                    maxWidth: 250,
                    maxWidthMessage: 'L\'image est trop large. La largeur max est 250 px.',
                    maxHeight: 250,
                    maxHeightMessage: 'L\'image est trop grande. La hauteur max est 250 px.',
                    maxSize: '50k',
                    maxSizeMessage: 'L\'image est trop volumineuse. Le poids max est 50 Ko.',
                    mimeTypes: ['image/png'],
                    mimeTypesMessage: 'Seul le format png est accepté.'
                )),
            TextField::new('imageAlt', 'Texte alternatif de l\'image')
                ->setColumns(6),
            BooleanField::new('active', 'Active')
                ->setColumns(2),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Liste des Réseaux Sociaux')
            ->setPageTitle('edit', 'Modifier un Réseau Social')
            ->setPageTitle('new', 'Ajouter un Réseau Social')
            ->setPageTitle('detail', 'Voir un Réseau Social')
            ->setDefaultSort(['id' => 'DESC'])
            ->setEntityLabelInPlural('Fichiers')
            ->setEntityLabelInSingular('Fichier')
            ->showEntityActionsInlined(true)
            ->setPaginatorPageSize(7)
            ->setEntityPermission('ROLE_ADMIN');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->setPermission(Action::DELETE, 'ROLE_ADMIN')
            ->setPermission(Action::EDIT, 'ROLE_ADMIN')
            ->setPermission(Action::NEW, 'ROLE_ADMIN')
            ->setPermission(Action::DETAIL, 'ROLE_ADMIN')

            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::NEW, function(Action $action){
                return $action->setIcon('fas fa-plus text-success')->setLabel('Ajouter un Réseau Social');
            })
            ->update(Crud::PAGE_INDEX, ACtion::DELETE, function(Action $action){
                return $action->setIcon('fas fa-trash text-danger')->setLabel('');
            })
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function(Action $action){
                return $action->setIcon('fas fa-eye text-info')->setLabel('');
            })
            // On DESACTIVE le bouton DELETE et le bouton NEW
            // ->disable(Action::DELETE, Action::NEW, Action::EDIT)
            ->update(Crud::PAGE_INDEX,Action::EDIT,function(Action $action){
                return $action->setIcon('fas fa-edit text-warning')->setLabel('');
            })
            ;
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\Fichier;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\Validator\Constraints\File;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class FichierCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Fichier::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // IdField::new('id'),
            TextField::new('title', 'Titre')
                ->setColumns(6),
            ImageField::new('fichierFile', 'Fichiers')
                ->setColumns(6)
                ->hideOnIndex()
                ->hideOnDetail()
                ->setBasePath('uploads/files/articles')
                ->setUploadDir('public/uploads/files/articles')
                ->setUploadedFileNamePattern('[name]-[uuid].[extension]')
                ->setHelp('Formats acceptés : pdf - 1 Mo maxi')
                ->setRequired(false)
                ->setFileConstraints(
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                        ],
                        'mimeTypesMessage' => 'Veuillez choisir un fichier PDF',
                        'maxSizeMessage' => 'Veuillez choisir un fichier de moins de 1 Mo',
                    ])
                ),
            TextField::new('fichierFile', 'Fichiers')
                ->setColumns(4)
                ->hideOnForm()
                ->setTemplatePath('admin/fields/documents.html.twig'),
            AssociationField::new('article', 'Article')
                ->setColumns(6),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Liste des Fichiers PDF')
            ->setPageTitle('edit', 'Modifier un Fichier PDF')
            ->setPageTitle('new', 'Ajouter un Fichier PDF')
            ->setPageTitle('detail', 'Voir un Fichier PDF')
            ->setDefaultSort(['id' => 'DESC'])
            ->setEntityLabelInPlural('Fichiers')
            ->setEntityLabelInSingular('Fichier')
            ->showEntityActionsInlined(true)
            ->setPaginatorPageSize(12);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::NEW, function(Action $action){
                return $action->setIcon('fas fa-plus text-success')->setLabel('Ajouter un Fichier PDF');
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

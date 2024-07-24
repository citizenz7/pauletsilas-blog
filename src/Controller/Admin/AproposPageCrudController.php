<?php

namespace App\Controller\Admin;

use App\Entity\AproposPage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class AproposPageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AproposPage::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addTab('Infos générales de la page'),
            TextField::new('title', 'Titre de la page')
                ->setColumns(6),
            SlugField::new('slug')
                ->setTargetFieldName('title')
                ->setColumns(6),

            FormField::addTab('Titres & textes'),
            TextField::new('mainTitle', 'Titre principal')
                ->setColumns(6)
                ->hideOnIndex(),
            TextEditorField::new('content', 'Texte principal')
                ->setColumns(12)
                ->hideOnIndex(),
            ImageField::new('image', 'Image')
                ->setColumns(6)
                ->setBasePath('uploads/img/apropos')
                ->setUploadDir('public/uploads/img/apropos')
                ->setUploadedFileNamePattern('[name]-[uuid].[extension]')
                ->setRequired(false),
            TextField::new('imageAlt', 'Texte alternatif de l\'image')
                ->setColumns(6)
                ->hideOnIndex(),

            FormField::addTab('SEO'),
            TextField::new('seoTitle','Balise SEO Titre')
                ->setColumns(12)
                ->hideOnIndex(),
            TextareaField::new('seoDescription','Balise SEO Description')
                ->setColumns(12)
                ->hideOnIndex(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Page A propos')
            ->setPageTitle('edit', 'Modifier la page A propos')
            ->setPageTitle('detail', 'Page A propos')
            ->showEntityActionsInlined(true)
            ->setEntityPermission('ROLE_ADMIN');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function(Action $action){
                return $action->setIcon('fas fa-eye text-info')->setLabel('')->addCssClass('text-dark');
            })
            // On DESACTIVE le bouton DELETE et le bouton NEW
            ->disable(Action::DELETE, Action::NEW)
            ->update(Crud::PAGE_INDEX,Action::EDIT,function(Action $action){
                return $action->setIcon('fas fa-edit text-warning')->setLabel('')->addCssClass('text-dark');
            });
    }
}

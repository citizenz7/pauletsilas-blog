<?php

namespace App\Controller\Admin;

use App\Entity\Setting;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SettingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Setting::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addTab('Général')
                ->setIcon('fas fa-info')
                ->setHelp('Informations générales'),
            FormField::addPanel('Titre'),
            TextField::new('siteName', 'Titre du site')
                ->setColumns(6),

            FormField::addPanel('Logos'),
            ImageField::new('siteLogo', 'Logo du site')
                ->setColumns(6)
                ->setRequired(false)
                ->setBasePath('uploads/img')
                ->setUploadDir('public/uploads/img')
                ->setUploadedFileNamePattern('[name]-[uuid].[extension]'),

            FormField::addTab('Url')
                ->setIcon('fas fa-link')
                ->setHelp('Adresses du site'),
            FormField::addPanel('Url'),
            TextField::new('siteUrl', 'Url courte du site')
                ->setColumns(6),
            TextField::new('siteUrlfull', 'Url complète du site')
                ->setColumns(6),

            FormField::addTab('Coordonnées')
                ->setIcon('fas fa-map-marker-alt')
                ->setHelp('Email, adresse, téléphone'),
            FormField::addPanel('Coordonnées'),
            EmailField::new('siteEmail', 'E-mail du site')
                ->setColumns(3),
            TextField::new('siteAdresse', 'Adresse postale')
                ->setColumns(5)
                ->hideOnIndex(),
            TextField::new('siteCp', 'Code Postal')
                ->setColumns(1)
                ->hideOnIndex(),
            TextField::new('siteVille', 'Ville')
                ->setColumns(3)
                ->hideOnIndex(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Paramètres du site')
            ->setPageTitle('detail', 'Paramètres du site')
            ->showEntityActionsInlined(true)
            ->setPageTitle('edit', 'Modifier les paramètres')
            ;
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
                return $action->setIcon('fas fa-edit text-warning')->setLabel('');
            });
    }
}

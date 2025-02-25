<?php

namespace App\Controller\Admin;

use App\Entity\ArticlePage;
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

class ArticlePageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ArticlePage::class;
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
            FormField::addPanel('Article'),
            TextField::new('mainTitle', 'Titre principal')
                ->setColumns(6)
                ->hideOnIndex(),
            TextField::new('galerieTitle', 'Titre galerie images')
                ->setColumns(6)
                ->hideOnIndex(),
            TextField::new('documentsTitle', 'Titre documents')
                ->setColumns(6)
                ->hideOnIndex(),
            FormField::addPanel('commentaires'),
            TextField::new('commentsNewTitle', 'Titre Poster un nouveau commentaire')
                ->setColumns(6)
                ->hideOnIndex(),
            TextField::new('commentsArticleTitle', 'Titre Les commentaires de l\'article')
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
            ->setPageTitle('index', 'Page Articles')
            ->setPageTitle('edit', 'Modifier la page Articles')
            ->setPageTitle('detail', 'Page Articles')
            ->showEntityActionsInlined(true)
            ->setEntityPermission('ROLE_ADMIN');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function(Action $action){
                return $action->setIcon('fas fa-eye text-info')->setLabel('');
            })
            // On DESACTIVE le bouton DELETE et le bouton NEW
            ->disable(Action::DELETE, Action::NEW)
            ->update(Crud::PAGE_INDEX,Action::EDIT,function(Action $action){
                return $action->setIcon('fas fa-edit text-warning')->setLabel('');
            });
    }
}

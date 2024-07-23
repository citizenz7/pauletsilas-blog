<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Controller\Admin\MediaCrudController;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addTab('Infos générales'),
            TextField::new('title', 'Titre de l\'article')
                ->setColumns(6),
            SlugField::new('slug', 'Slug')
                ->setTargetFieldName('title')
                ->setColumns(6)
                ->hideOnIndex(),
            AssociationField::new('categories', 'Catégories')
                ->setRequired(true)
                ->setColumns(6)
                ->formatValue(function ($value, $entity) {
                    return implode(", ",$entity->getCategories()->toArray());
                }),
            AssociationField::new('author', 'Auteur')
                ->setColumns(3)
                ->onlyWhenUpdating(),
            DateField::new('postedAt', 'Publié')
                ->setColumns(2)
                ->hideOnForm(),
            DateField::new('updatedAt', 'Modifié')
                ->setColumns(2)
                ->hideOnForm(),

            FormField::addTab('Textes'),
            TextEditorField::new('intro', 'Intro')
                ->setColumns(12)
                ->setFormType(CKEditorType::class)
                ->hideOnIndex()
                ->hideOnDetail(),
            TextareaField::new('intro', 'Intro')
                ->hideOnForm()
                ->hideOnIndex()
                ->setTemplatePath('admin/fields/text.html.twig'),
            TextEditorField::new('content', 'Contenu')
                ->setColumns(12)
                ->setFormType(CKEditorType::class)
                ->hideOnIndex()
                ->hideOnDetail(),
            TextareaField::new('content', 'Contenu')
                ->hideOnForm()
                ->hideOnIndex()
                ->setTemplatePath('admin/fields/text.html.twig'),

            FormField::addTab('Images'),
            ImageField::new('mainImage', 'Image principale')
                ->setRequired(false)
                ->setColumns(6)
                ->setBasePath('uploads/img/articles')
                ->setUploadDir('public/uploads/img/articles')
                ->setUploadedFileNamePattern('[name]-[uuid].[extension]'),
            TextField::new('mainImageAlt', 'Description de l\'image principale')
                ->setColumns(6)
                ->hideOnIndex(),
            CollectionField::new('mediapic', 'Galerie d\'images')
                ->setRequired(false)
                ->hideOnIndex()
                ->setEntryIsComplex(true)
                ->useEntryCrudForm(MediaCrudController::class),

            FormField::addTab('Documents'),
            CollectionField::new('fichiers', 'Fichiers')
                ->setRequired(false)
                ->setHelp('Format accepté : PDF - 1 Mo Maxi')
                ->setEntryIsComplex(true)
                ->useEntryCrudForm(FichierCrudController::class),

            FormField::addTab('SEO'),
            TextField::new('seoTitle', 'Titre SEO')
                ->setColumns(12)
                ->hideOnIndex(),
            TextareaField::new('seoDescription', 'Description SEO')
                ->setColumns(12)
                ->hideOnIndex(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Liste des articles')
            ->setPageTitle('edit', 'Modifier un article')
            ->setPageTitle('new', 'Ajouter un article')
            ->setPageTitle('detail', 'Voir un article')
            ->setDefaultSort(['id' => 'DESC'])
            ->setEntityLabelInPlural('Articles')
            ->setEntityLabelInSingular('Article')
            ->showEntityActionsInlined(true)
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
            ->setPaginatorPageSize(15);
    }

    public function configureActions(Actions $actions): Actions{
        return $actions
            ->update(Crud::PAGE_INDEX,Action::NEW,function(Action $action){
                return $action->setIcon('fas fa-tags pe-1')->setLabel('Ajouter un article');
            })
            ->update(Crud::PAGE_INDEX,Action::EDIT,function(Action $action){
                return $action->setIcon('fas fa-edit text-warning')->setLabel('')->addCssClass('text-dark');
            })
            ->add(Crud::PAGE_INDEX,Action::DETAIL)
            ->update(Crud::PAGE_INDEX,Action::DETAIL,function(Action $action){
                return $action->setIcon('fas fa-eye text-primary')->setLabel('')->addCssClass('text-dark');
            })
            ->update(Crud::PAGE_INDEX,Action::DELETE,function(Action $action){
                return $action->setIcon('fas fa-trash text-danger')->setLabel('')->addCssClass('text-dark');
            });
    }
}

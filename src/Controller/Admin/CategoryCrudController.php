<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use Symfony\Component\Validator\Constraints\Image;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // IdField::new('id'),
            TextField::new('title', 'Titre de la catégorie')
                ->setColumns(6),
            SlugField::new('slug')
                ->setTargetFieldName('title')
                ->hideOnIndex()
                ->setColumns(6),
            ImageField::new('image', 'Image')
                ->setColumns(6)
                ->setBasePath('uploads/img/categories')
                ->setUploadDir('public/uploads/img/categories')
                ->setUploadedFileNamePattern('[name]-[uuid].[extension]')
                ->setFileConstraints(new Image(
                    maxWidth: 1920,
                    maxWidthMessage: 'L\'image est trop large. La largeur max est 1920 px.',
                    maxHeight: 1080,
                    maxHeightMessage: 'L\'image est trop grande. La hauteur max est 1080 px.',
                    maxSize: '500k',
                    maxSizeMessage: 'L\'image est trop volumineuse. Le poids max est 500 Ko.',
                    mimeTypes: ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'],
                    mimeTypesMessage: 'Seuls les formats jpeg, jpg, png, webp sont acceptés.'
                )),
            TextEditorField::new('description', 'Description')
                ->setColumns(12)
                ->hideOnIndex()
                ->hideOnDetail(),
            TextareaField::new('description', 'Description')
                ->hideOnForm()
                ->hideOnIndex()
                ->setTemplatePath('admin/fields/text.html.twig'),
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
            ->setPageTitle(Crud::PAGE_INDEX, 'Catégories')
            ->setPageTitle(Crud::PAGE_NEW, 'Ajouter une catégorie')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier une catégorie')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Voir une catégorie')
            ->setEntityLabelInSingular('Categorie')
            ->setEntityLabelInPlural('Categories')
            ->setSearchFields(['title', 'slug'])
            ->setDefaultSort(['title' => 'ASC'])
            ->showEntityActionsInlined(true);
    }

    public function configureActions(Actions $actions): Actions{
        return $actions
            ->setPermission(Action::DELETE, 'ROLE_ADMIN')
            ->setPermission(Action::EDIT, 'ROLE_ADMIN')
            ->setPermission(Action::NEW, 'ROLE_ADMIN')

            ->update(Crud::PAGE_INDEX,Action::NEW,function(Action $action){
                return $action->setIcon('fas fa-tags pe-1')->setLabel('Ajouter une catégorie');
            })
            ->update(Crud::PAGE_INDEX,Action::EDIT,function(Action $action){
                return $action->setIcon('fas fa-edit text-warning')->setLabel('');
            })
            ->add(Crud::PAGE_INDEX,Action::DETAIL)
            ->update(Crud::PAGE_INDEX,Action::DETAIL,function(Action $action){
                return $action->setIcon('fas fa-eye text-info')->setLabel('');
            })
            ->update(Crud::PAGE_INDEX,Action::DELETE,function(Action $action){
                return $action->setIcon('fas fa-trash text-danger')->setLabel('');
            });
    }
}

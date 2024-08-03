<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;
use App\Controller\Admin\MediaCrudController;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use Symfony\Component\Validator\Constraints\Image;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class ArticleCrudController extends AbstractCrudController
{
    public function __construct(private Security $security)
    {
    }

    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        // dd($this->security->getUser()->getId());

        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        if ($this->isGranted('ROLE_ADMIN')) {
            return $queryBuilder;
        }

        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
            ->andWhere('entity.author = :author')
            ->setParameter('author', $this->security->getUser()->getId());
        return $queryBuilder;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addTab('Infos générales'),
            BooleanField::new('active', 'Publié')
                ->setColumns(12)
                ->setPermission('ROLE_ADMIN'),
            TextField::new('title', 'Titre de l\'article')
                ->setHelp('Titre principal de l\'article. 90 caractères max.')
                ->setColumns(6),
            SlugField::new('slug', 'Slug')
                ->setHelp('Adresse finale de l\'article basée sur le titre. Automatique. Vous n\'avez rien à faire.')
                ->setTargetFieldName('title')
                ->setColumns(6)
                ->hideOnIndex(),
            AssociationField::new('categories', 'Catégories')
                ->setHelp('Choisissez une ou plusieurs catégories pour l\'article.')
                ->setRequired(true)
                ->setColumns(6)
                ->formatValue(function ($value, $entity) {
                    return implode(", ",$entity->getCategories()->toArray());
                }),
            AssociationField::new('author', 'Auteur')
                ->setColumns(3)
                ->onlyOnForms()
                ->onlyWhenUpdating(),
            AssociationField::new('author', 'Auteur')
                ->onlyOnIndex(),
            DateField::new('postedAt', 'Publié')
                ->setColumns(2)
                ->hideOnForm(),
            DateField::new('updatedAt', 'Modifié')
                ->setColumns(2)
                ->hideOnForm(),

            FormField::addTab('Textes'),
            TextEditorField::new('intro', 'Intro')
                ->setHelp('Introduction de l\'article. 200 caractères max.')
                ->setColumns(12)
                ->setFormType(CKEditorType::class)
                ->hideOnIndex()
                ->hideOnDetail(),
            TextareaField::new('intro', 'Intro')
                ->hideOnForm()
                ->hideOnIndex()
                ->setTemplatePath('admin/fields/text.html.twig'),
            TextEditorField::new('content', 'Contenu')
                ->setHelp('Texte principal de l\'article.')
                ->setColumns(12)
                ->setFormType(CKEditorType::class)
                ->setFormTypeOption('config', ['height' => '350px'])
                ->hideOnIndex()
                ->hideOnDetail(),
            TextareaField::new('content', 'Contenu')
                ->hideOnForm()
                ->hideOnIndex()
                ->setTemplatePath('admin/fields/text.html.twig'),

            FormField::addTab('Images'),
            ImageField::new('mainImage', 'Image principale')
                ->setHelp('Image principale de l\'article. Taille de l\'image 1920x1080 maxi. Poids de l\'image 500 Ko maxi.')
                ->setRequired(false)
                ->setColumns(6)
                ->setBasePath('uploads/img/articles')
                ->setUploadDir('public/uploads/img/articles')
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
            TextField::new('mainImageAlt', 'Description de l\'image principale')
                ->setHelp('Texte alternatif de l\'image. 90 caractères max.')
                ->setColumns(6)
                ->hideOnIndex(),
            IntegerField::new('views', 'Vues')
                ->onlyOnIndex(),
            CollectionField::new('mediapic', 'Galerie d\'images (facultatif)')
                ->setHelp('Ajoutez des images à l\'article (galerie d\'images). Taille de l\'image 1920x1080 maxi. Poids de l\'image 500 Ko maxi.')
                ->setColumns(12)
                ->setRequired(false)
                ->hideOnIndex()
                ->setEntryIsComplex(true)
                ->useEntryCrudForm(MediaCrudController::class),

            FormField::addTab('Documents'),
            CollectionField::new('fichiers', 'Fichiers')
                ->setRequired(false)
                ->setHelp('Format accepté : PDF - 1 Mo Maxi')
                ->setEntryIsComplex(true)
                ->useEntryCrudForm(FichierCrudController::class)
                ->setColumns(6)
                ->hideOnIndex(),
            // CollectionField::new('fichiers', 'Fichiers')
            //     ->setColumns(6)
            //     ->hideOnForm()
            //     ->setTemplatePath('admin/fields/document-fichier.html.twig'),

            FormField::addTab('SEO')
                ->setHelp('Informations pour les moteurs de recherche. Les deux champs suivants sont obligatoires.'),
            TextField::new('seoTitle', 'Titre SEO')
                ->setColumns(12)
                ->onlyOnForms()
                ->setHelp('Le titre affiché dans les moteurs de recherche. Idéal : 70 caractères maxi. Vous pouvez reprendre le titre de l\'article et l\'adapter si besoin.'),
            TextareaField::new('seoDescription', 'Description SEO')
                ->setColumns(12)
                ->onlyOnForms()
                ->setHelp('La description affichée dans les moteurs de recherche. 160 caractères maxi. Il s\'agit d\'une description en 1 phrase ou 2 qui va mettre en valeur au moins un mot-clé important de l\'article.'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Liste des articles')
            ->setPageTitle('edit', 'Modifier un article')
            ->setPageTitle('new', 'Ajouter un article')
            ->setPageTitle('detail', 'Voir un article')
            ->setHelp('index', 'Vous devez avoir confirmé votre adresse e-mail pour pouvoir ajouter un article.')
            ->setDefaultSort(['id' => 'DESC'])
            ->setEntityLabelInPlural('Articles')
            ->setEntityLabelInSingular('Article')
            ->showEntityActionsInlined(true)
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
            ->setPaginatorPageSize(15);
    }

    public function configureActions(Actions $actions): Actions{
        $user = $this->getUser();

        return $actions
            // ->update(Crud::PAGE_INDEX,Action::NEW,function(Action $action){
            //     return $action->setIcon('fas fa-tags pe-1')->setLabel('Ajouter un article');
            // })
            // on n'affiche pas le bouton "Ajouter un article" si l'adresse e-mail n'est pas confirmée
            ->update(Crud::PAGE_INDEX,Action::NEW,function(Action $action) use ($user){
                if ($user && !$user->isVerified()) {
                    return $action->displayIf(fn () => false);
                } else {
                    return $action->setIcon('fas fa-tags pe-1')->setLabel('Ajouter un article');
                }
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

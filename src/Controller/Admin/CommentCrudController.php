<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextareaField::new('content', 'Commentaire')
                ->setColumns(12),
            AssociationField::new('article', 'Article')
                ->setColumns(6)
                ->setQueryBuilder(function ($qb) {
                    return $qb->orderBy('entity.id', 'DESC');
                }),
            AssociationField::new('author', 'Auteur')
                ->setColumns(6)
                ->setQueryBuilder(function ($qb) {
                    return $qb->orderBy('entity.firstname', 'ASC');
                }),
            DateTimeField::new('createdAt', 'Posté le')
                ->setColumns(3),
            BooleanField::new('approved', 'Validé'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Liste des Commentaires')
            ->setPageTitle('edit', 'Modifier un Commentaire')
            ->setPageTitle('new', 'Ajouter un Commentaire')
            ->setPageTitle('detail', 'Voir un Commentaire')
            ->setDefaultSort(['id' => 'DESC'])
            ->setEntityLabelInPlural('Commentaires')
            ->setEntityLabelInSingular('Commentaire')
            ->showEntityActionsInlined(true)
            ->setPaginatorPageSize(12)
            ->setEntityPermission('ROLE_ADMIN');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::NEW, function(Action $action){
                return $action->setIcon('fas fa-plus text-success')->setLabel('Ajouter un Commentaire');
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

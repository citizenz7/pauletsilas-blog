<?php

namespace App\Entity;

use App\Repository\ArticlePageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticlePageRepository::class)]
class ArticlePage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    private ?string $seoTitle = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $seoDescription = null;

    #[ORM\Column(length: 255)]
    private ?string $mainTitle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $galerieTitle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $documentsTitle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commentsNewTitle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commentsArticleTitle = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getSeoTitle(): ?string
    {
        return $this->seoTitle;
    }

    public function setSeoTitle(string $seoTitle): static
    {
        $this->seoTitle = $seoTitle;

        return $this;
    }

    public function getSeoDescription(): ?string
    {
        return $this->seoDescription;
    }

    public function setSeoDescription(string $seoDescription): static
    {
        $this->seoDescription = $seoDescription;

        return $this;
    }

    public function getMainTitle(): ?string
    {
        return $this->mainTitle;
    }

    public function setMainTitle(string $mainTitle): static
    {
        $this->mainTitle = $mainTitle;

        return $this;
    }

    public function getGalerieTitle(): ?string
    {
        return $this->galerieTitle;
    }

    public function setGalerieTitle(?string $galerieTitle): static
    {
        $this->galerieTitle = $galerieTitle;

        return $this;
    }

    public function getDocumentsTitle(): ?string
    {
        return $this->documentsTitle;
    }

    public function setDocumentsTitle(?string $documentsTitle): static
    {
        $this->documentsTitle = $documentsTitle;

        return $this;
    }

    public function getCommentsNewTitle(): ?string
    {
        return $this->commentsNewTitle;
    }

    public function setCommentsNewTitle(?string $commentsNewTitle): static
    {
        $this->commentsNewTitle = $commentsNewTitle;

        return $this;
    }

    public function getCommentsArticleTitle(): ?string
    {
        return $this->commentsArticleTitle;
    }

    public function setCommentsArticleTitle(?string $commentsArticleTitle): static
    {
        $this->commentsArticleTitle = $commentsArticleTitle;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\HomePageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HomePageRepository::class)]
class HomePage
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

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    private ?string $imageAlt = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastArticlesTitle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastFilesTitle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastPicsTitle = null;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getImageAlt(): ?string
    {
        return $this->imageAlt;
    }

    public function setImageAlt(string $imageAlt): static
    {
        $this->imageAlt = $imageAlt;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getLastArticlesTitle(): ?string
    {
        return $this->lastArticlesTitle;
    }

    public function setLastArticlesTitle(?string $lastArticlesTitle): static
    {
        $this->lastArticlesTitle = $lastArticlesTitle;

        return $this;
    }

    public function getLastFilesTitle(): ?string
    {
        return $this->lastFilesTitle;
    }

    public function setLastFilesTitle(?string $lastFilesTitle): static
    {
        $this->lastFilesTitle = $lastFilesTitle;

        return $this;
    }

    public function getLastPicsTitle(): ?string
    {
        return $this->lastPicsTitle;
    }

    public function setLastPicsTitle(?string $lastPicsTitle): static
    {
        $this->lastPicsTitle = $lastPicsTitle;

        return $this;
    }
}

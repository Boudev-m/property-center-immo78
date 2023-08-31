<?php

namespace App\Entity;

use App\Repository\NewsRepository;
use Cocur\Slugify\Slugify;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: NewsRepository::class)]
#[Vich\Uploadable]
class News
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image_name = null;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'news_image', fileNameProperty: 'image_name')]
    #[Assert\Image(
        mimeTypes: ['image/jpeg', 'image/png'],
        mimeTypesMessage: 'Fichier invalide ({{ type }}). Fichier(s) accepté(s) : {{ types }}'
    )]
    private ?File $image_file = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    public function __construct()
    {
        $this->created_at = new DateTimeImmutable('now', new DateTimeZone('Europe/Paris'));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        $slug = new Slugify();
        return $slug->slugify($this->getTitle());
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->image_name;
    }

    public function setImageName(?string $image_name): self
    {
        $this->image_name = $image_name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * Get the value of image_file
     *
     * @return ?File
     */
    public function getImageFile(): ?File
    {
        return $this->image_file;
    }

    /**
     * Set the value of image_file
     *
     * @param ?File $image_file
     *
     * @return self
     */
    public function setImageFile(?File $image_file): void
    {
        $this->image_file = $image_file;

        if ($this->image_file instanceof UploadedFile) {
            // update updated_at
            $this->updated_at = new \DateTimeImmutable('now', new DateTimeZone('Europe/Paris'));
        }
    }
}

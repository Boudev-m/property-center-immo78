<?php

namespace App\Entity;

use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PictureRepository;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: PictureRepository::class)]
#[Vich\Uploadable]
class Picture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'pictures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Property $property = null;

    #[ORM\Column(length: 255)]
    private ?string $imageName = null;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'property_image', fileNameProperty: 'imageName')]
    #[Assert\Image(
        mimeTypes: ['image/jpeg', 'image/png'],
        mimeTypesMessage: 'Fichier invalide ({{ type }}). Fichier(s) accepté(s) : {{ types }}'
    )]
    private ?File $imageFile = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProperty(): ?Property
    {
        return $this->property;
    }

    public function setProperty(?Property $property): self
    {
        $this->property = $property;

        return $this;
    }

    /**
     * Get the value of imageName
     *
     * @return ?string
     */
    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    /**
     * Set the value of imageName
     *
     * @param ?string $imageName
     *
     * @return self
     */
    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * Get the value of imageFile
     *
     * @return ?File
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * Set the value of imageFile
     *
     * @param ?File $imageFile
     *
     * @return self
     */
    public function setImageFile(?File $imageFile): self
    {
        $this->imageFile = $imageFile;

        return $this;
    }
}

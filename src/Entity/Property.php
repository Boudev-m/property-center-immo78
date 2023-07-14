<?php

namespace App\Entity;

use App\Repository\PropertyRepository;
use Cocur\Slugify\Slugify;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: PropertyRepository::class)]
#[UniqueEntity('title')]    // unique for title field
class Property
{

    const HEAT = [
        1 => 'Electrique',
        2 => 'Gaz'
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 5, max: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotNull]
    #[Assert\Length(min: 3)]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Range(
        min: 10,
        max: 200,
        notInRangeMessage: 'La surface doit être situé entre {{ min }}m² et {{ max }}m².',
    )]
    private ?int $surface = null;

    #[ORM\Column]
    #[Assert\NotNull]
    private ?int $rooms = null;

    #[ORM\Column]
    #[Assert\NotNull]
    private ?int $bedrooms = null;

    #[ORM\Column]
    #[Assert\NotNull]
    private ?int $floor = null;

    #[ORM\Column]
    #[Assert\NotNull]
    private ?int $price = null;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Choice([1, 2])]
    private ?int $heat = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Assert\Regex('/^[0-9]{5}$/')]
    private ?string $postal_code = null;

    #[ORM\Column(options: ["default" => false])]
    private ?bool $sold = false;

    #[ORM\Column]
    #[Assert\NotNull]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToMany(targetEntity: Option::class, inversedBy: 'properties')]
    private Collection $options;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\OneToMany(mappedBy: 'property', targetEntity: Picture::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $pictures;

    // All : constraints for each element of pictureFiles array
    #[Assert\All([
        new Assert\Image(mimeTypes: ['image/jpeg'], mimeTypesMessage: 'Veuillez séléctionner un/des fichier(s) au format JPEG de moins de 2mo. ')
    ])]
    private $pictureFiles;

    // scale: number of decimals, precision: number total of digits (ex: 34.5205)
    #[ORM\Column(scale: 4, precision: 6)]
    private ?float $latitude = null;

    #[ORM\Column(scale: 4, precision: 7)]
    private ?float $longitude = null;

    public function __construct()
    {
        // Date = at the creation of instance
        $this->created_at = new DateTimeImmutable('now', new DateTimeZone('Europe/Paris'));
        $this->options = new ArrayCollection();
        $this->pictures = new ArrayCollection();
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

    // Slug = formatted title using for Url
    public function getSlug(): ?string
    {
        $slugify = (new Slugify());
        return $slugify->slugify($this->title);
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSurface(): ?int
    {
        return $this->surface;
    }

    public function setSurface(int $surface): self
    {
        $this->surface = $surface;

        return $this;
    }

    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    public function setRooms(int $rooms): self
    {
        $this->rooms = $rooms;

        return $this;
    }

    public function getBedrooms(): ?int
    {
        return $this->bedrooms;
    }

    public function setBedrooms(int $bedrooms): self
    {
        $this->bedrooms = $bedrooms;

        return $this;
    }

    public function getFloor(): ?int
    {
        return $this->floor;
    }

    public function setFloor(int $floor): self
    {
        $this->floor = $floor;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function getFormattedPrice(): string
    {
        return number_format($this->price, 0, '', ' ');
    }


    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getHeat(): ?int
    {
        return $this->heat;
    }

    public function getHeatType(): ?string
    {
        return self::HEAT[$this->heat];
    }

    public function setHeat(int $heat): self
    {
        $this->heat = $heat;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(string $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function isSold(): ?bool
    {
        return $this->sold;
    }

    public function setSold(bool $sold): self
    {
        $this->sold = $sold;

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

    /**
     * @return Collection<int, Option>
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(Option $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options->add($option);
            $option->addProperty($this);
        }

        return $this;
    }

    public function removeOption(Option $option): self
    {
        if ($this->options->removeElement($option)) {
            $option->removeProperty($this);
        }

        return $this;
    }


    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection<int, Picture>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    /**
     * @return ?Picture
     */
    public function getFirstPicture(): ?Picture
    {
        if ($this->pictures->isEmpty()) {
            return null;
        }
        return $this->pictures->get(0);
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures->add($picture);
            $picture->setProperty($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getProperty() === $this) {
                $picture->setProperty(null);
            }
        }

        return $this;
    }

    /**
     * Get the value of pictureFiles
     */
    public function getPictureFiles()
    {
        return $this->pictureFiles;
    }

    /**
     * Set the value of pictureFiles
     */
    public function setPictureFiles($pictureFiles): self
    {
        // For each picture file
        foreach ($pictureFiles as $file) {
            $picture = new Picture();
            $picture->setImageFile($file);
            $this->addPicture($picture);
        }

        $this->pictureFiles = $pictureFiles;
        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }
}

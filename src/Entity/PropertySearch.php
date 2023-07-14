<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

class PropertySearch
{
    /**
     * @var int|null
     */
    private $maxPrice;

    /**
     * @var int|null
     */
    #[Assert\Range(
        min: 10,
        max: 200,
        notInRangeMessage: 'La surface doit être situé entre {{ min }}m² et {{ max }}m².',
    )]
    private $minSurface;

    /**
     * @var ArrayCollection
     */
    private $options;

    private ?string $address = null;
    private ?int $distance = null;
    private ?float $latitude = null;
    private ?float $longitude = null;


    public function __construct()
    {
        $this->options = new ArrayCollection();
    }

    public function getMaxPrice(): ?int
    {
        return $this->maxPrice;
    }

    public function setMaxPrice(int $maxPrice): PropertySearch
    {
        $this->maxPrice = $maxPrice;
        return $this;
    }

    public function getMinSurface(): ?int
    {
        return $this->minSurface;
    }

    public function setMinSurface(int $minSurface): PropertySearch
    {
        $this->minSurface = $minSurface;
        return $this;
    }

    public function getOptions(): ArrayCollection
    {
        return $this->options;
    }

    public function setOptions(ArrayCollection $options): void
    {
        $this->options = $options;
    }

    /**
     * Get the value of distance
     *
     * @return ?int
     */
    public function getDistance(): ?int
    {
        return $this->distance;
    }

    /**
     * Set the value of distance
     *
     * @param ?int $distance
     *
     * @return self
     */
    public function setDistance(?int $distance): self
    {
        $this->distance = $distance;

        return $this;
    }

    /**
     * Get the value of latitude
     *
     * @return ?float
     */
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    /**
     * Set the value of latitude
     *
     * @param ?float $latitude
     *
     * @return self
     */
    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get the value of longitude
     *
     * @return ?float
     */
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    /**
     * Set the value of longitude
     *
     * @param ?float $longitude
     *
     * @return self
     */
    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get the value of address
     *
     * @return ?string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * Set the value of address
     *
     * @param ?string $address
     *
     * @return self
     */
    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }
}

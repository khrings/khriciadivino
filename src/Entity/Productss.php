<?php

namespace App\Entity;
use App\Repository\ProductssRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Category;

#[ORM\Entity(repositoryClass: ProductssRepository::class)]
class Productss
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 100)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'productsses')]
    #[ORM\Column(length: 255)]
    private ?string $category = null;

   #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagefilename = null;
   
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }
    
public function getCategory(): ?string
{
    return $this->category;
}

public function setCategory(?string $category): static
{
    $this->category = $category;
    return $this;
}

    public function getImagefilename(): ?string
    {
        return $this->imagefilename;
    }

    public function setImagefilename(string $imagefilename): static
    {
        $this->imagefilename = $imagefilename;

        return $this;
    }



   
}
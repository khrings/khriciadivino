<?php

namespace App\Entity;
use App\Repository\ProductssRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

   #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagefilename = null;

   #[ORM\ManyToOne(inversedBy: 'productsses')]
   private ?Category $category = null;

   #[ORM\Column]
   private ?float $quantity = null;

   /**
    * @var Collection<int, Stocks>
    */
   #[ORM\OneToMany(targetEntity: Stocks::class, mappedBy: 'productss')]
   private Collection $Stocks;

   public function __construct()
   {
       $this->Stocks = new ArrayCollection();
   }
   
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


    public function getImagefilename(): ?string
    {
        return $this->imagefilename;
    }

    public function setImagefilename(string $imagefilename): static
    {
        $this->imagefilename = $imagefilename;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return Collection<int, Stocks>
     */
    public function getStocks(): Collection
    {
        return $this->Stocks;
    }

    public function addStock(Stocks $stock): static
    {
        if (!$this->Stocks->contains($stock)) {
            $this->Stocks->add($stock);
            $stock->setProductss($this);
        }

        return $this;
    }

    public function removeStock(Stocks $stock): static
    {
        if ($this->Stocks->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getProductss() === $this) {
                $stock->setProductss(null);
            }
        }

        return $this;
    }



   
}
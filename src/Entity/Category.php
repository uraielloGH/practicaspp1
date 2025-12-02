<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    /**
     * ID autogenerado de la categoría.
     * Clave primaria.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Nombre de la categoría.
     * Ej.: "Café", "Tostados", "Infusiones".
     */
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * Descripción de la categoría.
     * Texto libre.
     */
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    /**
     * Nombre del archivo de imagen (opcional).
     * Debe existir dentro de /public/images.
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    /**
     * Relación 1 -> N con Product.
     * Una categoría puede tener muchos productos.
     *
     * mappedBy: "category" en Product.
     */
    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'category')]
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    // getters y setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return Collection<int, Product>
     * Colección de productos pertenecientes a esta categoría.
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * Agrega un producto a la categoría
     * y actualiza la relación inversa.
     */
    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setCategory($this);
        }

        return $this;
    }

    /**
     * Elimina un producto de la categoría
     * y actualiza la relación inversa si corresponde.
     */
    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }

        return $this;
    }
}

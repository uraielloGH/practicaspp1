<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entidad Product
 *
 * Representa un producto del catálogo de Brekky.
 * Utilizada en el CU007 – Gestionar productos, donde el administrador
 * puede crear, editar, listar y eliminar productos.
 *
 * Atributos principales:
 *  - name: nombre visible del producto
 *  - description: descripción extendida
 *  - price: precio del producto
 *  - image: archivo de imagen dentro de /public/images
 *  - category: categoría a la que pertenece (relación ManyToOne)
 *
 * Reglas de negocio relacionadas:
 *  - RN01: Todos los productos deben pertenecer a una categoría válida.
 *  - RN02: El precio debe ser positivo.
 *
 * Esta entidad es gestionada por ProductRepository.
 */
#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    /**
     * Identificador único del producto (PK).
     * Generado automáticamente por Doctrine.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Nombre del producto.
     * Campo obligatorio, longitud máxima 100 caracteres.
     */
    #[ORM\Column(length: 100)]
    private ?string $name = null;

    /**
     * Descripción del producto.
     * Campo opcional, permite texto largo.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * Precio del producto.
     * Representa un valor monetario en ARS.
     * Reglas:
     *  - Debe ser mayor a cero.
     */
    #[ORM\Column]
    private ?float $price = null;

    /**
     * Nombre del archivo de imagen del producto.
     * Opcional. El archivo debe existir dentro de /public/images.
     * Ejemplos: “cafe1.jpg”, “te-matcha.webp”.
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    /**
     * Categoría a la que pertenece el producto.
     * Relación ManyToOne: muchos productos a una categoría.
     * No puede ser nulo (todo producto debe tener una categoría asignada).
     */
    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    /**
     * Obtiene el ID del producto.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Obtiene el nombre del producto.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Establece el nombre del producto.
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Obtiene la descripción del producto.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Establece la descripción del producto.
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Obtiene el precio del producto.
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * Establece el precio del producto.
     * Debe respetar las reglas:
     *  - Valor positivo
     */
    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    /**
     * Obtiene el nombre del archivo de imagen.
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * Establece el archivo de imagen del producto.
     * El archivo debe existir en /public/images.
     */
    public function setImage(?string $image): self
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Obtiene la categoría del producto.
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Establece la categoría del producto.
     * No puede ser nula.
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;
        return $this;
    }
}

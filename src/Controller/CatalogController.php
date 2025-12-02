<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CatalogController extends AbstractController
{
    /**
     * CU003 – Paso 1:
     * Mostrar todas las categorías disponibles para que el usuario elija.
     *
     * El usuario entra a /catalog y buscamos todas las categorías en la BD
     * y se las pasamos al template categories.html.twig para que las liste.
     */
    #[Route('/catalog', name: 'catalog_categories')]
    public function categories(CategoryRepository $categoryRepository): Response
    {
        // Traemos todas las categorías del repositorio
        $categories = $categoryRepository->findAll();

        // Enviamos la lista al Twig para mostrarla
        return $this->render('catalog/categories.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * CU003 – Paso 2:
     * Cuando el usuario selecciona una categoría, entramos a /catalog/category/{id}
     * y mostramos todos los productos que pertenecen a esa categoría.
     *
     * Si la categoría no existe → error 404.
     * Si existe pero no tiene productos → el Twig mostrará “No hay productos”.
     */
    #[Route('/catalog/category/{id}', name: 'catalog_products_by_category')]
    public function productsByCategory(int $id, CategoryRepository $categoryRepository): Response
    {
        // Buscamos la categoría seleccionada
        $category = $categoryRepository->find($id);

        // Si no existe, devolvemos un 404
        if (!$category) {
            throw $this->createNotFoundException('Categoría no encontrada');
        }

        // Renderizamos la vista con la categoría y sus productos
        // getProducts() viene de la relación OneToMany en la entidad Category
        return $this->render('catalog/products.html.twig', [
            'category' => $category,
            'products' => $category->getProducts(),
        ]);
    }

    /**
     * CU003 – Paso 3:
     * Cuando el usuario elige un producto → mostramos su detalle.
     *
     * /catalog/product/{id}
     *
     * El repositorio busca el producto por su ID.
     * Si no existe → error 404.
     * Si existe → mostramos nombre, descripción, imagen, precio, etc.
     */
    #[Route('/catalog/product/{id}', name: 'catalog_product_detail')]
    public function productDetail(int $id, ProductRepository $productRepository): Response
    {
        // Buscamos el producto seleccionado
        $product = $productRepository->find($id);

        // Manejo de error si no existe
        if (!$product) {
            throw $this->createNotFoundException('Producto no encontrado');
        }

        // Renderizamos el detalle del producto
        return $this->render('catalog/product_detail.html.twig', [
            'product' => $product,

            // lo pasamos para poder mostrar el nombre de la categoría en el template
            'category' => $product->getCategory(),
        ]);
    }
}

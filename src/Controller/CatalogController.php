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
     * Lista todas las categorías
     */
    #[Route('/catalog', name: 'catalog_categories')]
    public function categories(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('catalog/categories.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * Lista los productos de una categoría
     */
    #[Route('/catalog/category/{id}', name: 'catalog_products_by_category')]
    public function productsByCategory(int $id, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Categoría no encontrada');
        }

        return $this->render('catalog/products.html.twig', [
            'category' => $category,
            'products' => $category->getProducts(),
        ]);
    }

    /**
     * Muestra el detalle de un producto
     */
    #[Route('/catalog/product/{id}', name: 'catalog_product_detail')]
    public function productDetail(int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Producto no encontrado');
        }

        return $this->render('catalog/product_detail.html.twig', [
            'product' => $product,
            'category' => $product->getCategory(),
        ]);
    }
}

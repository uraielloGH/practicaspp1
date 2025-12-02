<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[Route('/product')]
#[IsGranted('ROLE_ADMIN')] // Solo administradores pueden acceder a la gestión de productos
class ProductController extends AbstractController
{
    /**
     * Listado de productos para el administrador.
     * Acá se ve la grilla con todos los productos y las acciones de ABM.
     */
    #[Route('/', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * Alta de producto (CU007 – Agregar producto).
     * Muestra el formulario y, si es válido, guarda en BD y vuelve al listado.
     */
    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductRepository $productRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Guardamos el nuevo producto
            $productRepository->save($product, true);

            // Mensaje de éxito para el admin
            $this->addFlash('success', 'Producto creado correctamente.');

            // Volvemos al listado de productos
            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        // Primera carga o formulario con errores
        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form'    => $form->createView(),
        ]);
    }

    /**
     * Vista de detalle de un producto (no es el foco del CU, pero queda disponible).
     */
    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * Edición de producto (CU007 – Modificar producto).
     */
    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Guardamos cambios sobre el producto existente
            $productRepository->save($product, true);

            $this->addFlash('success', 'Producto actualizado correctamente.');

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        // Mostrar formulario de edición (con errores si los hay)
        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form'    => $form->createView(),
        ]);
    }

    /**
     * Baja de producto (CU007 – Eliminar producto).
     */
    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            // Eliminamos de la base
            $productRepository->remove($product, true);
            $this->addFlash('success', 'Producto eliminado correctamente.');
        } else {
            // Por si alguna vez falla el token CSRF
            $this->addFlash('error', 'No se pudo eliminar el producto. Intentá nuevamente.');
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }
}

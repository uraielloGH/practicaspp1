<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repositorio de la entidad Product.
 *
 * Participa en:
 *  - CU003 Consultar productos (lectura de productos desde el catálogo)
 *  - CU007 Gestionar productos (alta, edición y baja de productos por ADMIN)
 *
 * A través de ServiceEntityRepository ya tenemos disponibles:
 *  - find($id)
 *  - findAll()
 *  - findBy(...)
 *  - findOneBy(...)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        // Este repositorio trabaja con la entidad Product
        parent::__construct($registry, Product::class);
    }

    /**
     * Guarda un producto en la base de datos.
     *
     * Se usa en:
     *  - ProductController::new()  (crear producto)
     *  - ProductController::edit() (actualizar producto)
     */
    public function save(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Elimina un producto de la base de datos.
     *
     * Se usa en:
     *  - ProductController::delete() (eliminar producto)
     */
    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /*
     * Consultas personalizadas (ejemplos generados por Symfony).
     * No las usamos en este trabajo práctico, pero quedan como base
     * por si en el futuro queremos filtrar productos por distintos criterios.
     */

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    // public function findByExampleField($value): array
    // {
    //     return $this->createQueryBuilder('p')
    //         ->andWhere('p.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('p.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }

    // public function findOneBySomeField($value): ?Product
    // {
    //     return $this->createQueryBuilder('p')
    //         ->andWhere('p.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->getQuery()
    //         ->getOneOrNullResult()
    //     ;
    // }
}

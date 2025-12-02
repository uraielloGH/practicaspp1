<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Este repositorio se encarga de acceder a la tabla Category.
 *
 * En el CU003 lo usamos principalmente para:
 *  - traer todas las categorías cuando el usuario entra al catálogo (/catalog)
 *  - buscar una categoría por su ID cuando quiere ver sus productos (/catalog/category/{id})
 *
 * Las funciones find(), findAll(), findBy() y findOneBy() ya vienen listas gracias
 * a ServiceEntityRepository, así que no necesitamos agregar nada extra.
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        // Le decimos al repositorio que esta clase representa a la entidad Category
        parent::__construct($registry, Category::class);
    }

    // Abajo quedaron ejemplos generados por Symfony, pero no los usamos en este proyecto.
    // Los dejamos comentados por si en algún momento queremos agregar consultas personalizadas.

    /*
    public function findByExampleField($value): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneBySomeField($value): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

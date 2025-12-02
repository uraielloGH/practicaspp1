<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * Repositorio de la entidad User.
 * Symfony usa esta clase automáticamente para buscar usuarios por email
 * cuando alguien intenta iniciar sesión (CU002).
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Symfony llama a este método si en algún momento necesita
     * volver a hashear la contraseña del usuario con un algoritmo más nuevo.
     * Nosotros no lo usamos directamente, pero queda implementado por compatibilidad.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf(
                'El objeto "%s" no es un usuario válido.',
                $user::class
            ));
        }

        // Actualizamos la contraseña en la base de datos
        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    // El resto de los métodos generados por Symfony para búsquedas personalizadas
    // fueron eliminados porque no los usamos en este proyecto.
}

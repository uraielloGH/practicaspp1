<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(
    fields: ['email'],
    message: 'Ya existe una cuenta registrada con este correo electrónico.'
)]
// Entidad que representa al usuario registrado en el sistema.
// Se usa tanto para el registro (CU001) como para el login (CU002).
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;          // Id interno del usuario

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;    // Correo que usa para loguearse

    #[ORM\Column]
    private array $roles = [];        // Roles del usuario (por ahora solo ROLE_USER)

    #[ORM\Column]
    private ?string $password = null; // Contraseña hasheada

    #[ORM\Column(length: 100)]
    private ?string $nombre = null;   // Nombre completo mostrado en la app

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    // Identificador principal del usuario en el sistema (lo usamos como username)
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        // nos aseguramos de que siempre tenga al menos ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    // Devuelve la contraseña ya hasheada (no se guarda nunca en texto plano)
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    // Acá podríamos limpiar datos sensibles temporales si los usáramos
    public function eraseCredentials(): void
    {
        // $this->plainPassword = null;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }
}

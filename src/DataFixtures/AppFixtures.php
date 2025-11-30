<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // ==== USUARIOS ====

        // Admin
        $admin = new User();
        $admin->setEmail('admin@brekky.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword(
            $this->hasher->hashPassword($admin, 'Brekky123')
        );
        $admin->setNombre('Administrador');
        $manager->persist($admin);

        // Usuario normal
        $user = new User();
        $user->setEmail('user@brekky.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword(
            $this->hasher->hashPassword($user, 'User1234')
        );
        $user->setNombre('Usuario');
        $manager->persist($user);

        // ==== CATEGORÍAS ====

        $bebidas = new Category();
        $bebidas->setName('Bebidas');
        $bebidas->setDescription('Bebidas frías y calientes.');
        $manager->persist($bebidas);

        $snacks = new Category();
        $snacks->setName('Snacks');
        $snacks->setDescription('Snacks y acompañamientos.');
        $manager->persist($snacks);

        $desayunos = new Category();
        $desayunos->setName('Desayunos');
        $desayunos->setDescription('Opciones de desayunos variados.');
        $manager->persist($desayunos);

        // ==== PRODUCTOS BEBIDAS ====

        $PB1 = new Product();
        $PB1->setName('Café Latte');
        $PB1->setDescription('Café con leche espumosa.');
        $PB1->setPrice(1200);
        $PB1->setImage('latte.jpg');
        $PB1->setCategory($bebidas);
        $manager->persist($PB1);

        $PB2 = new Product();
        $PB2->setName('Jugo de Naranja');
        $PB2->setDescription('Jugo exprimido natural.');
        $PB2->setPrice(1500);
        $PB2->setImage('jugo_naranja.jpg');
        $PB2->setCategory($bebidas);
        $manager->persist($PB2);

        // ==== PRODUCTOS SNACKS ====

        $PS1 = new Product();
        $PS1->setName('Chips de papa');
        $PS1->setDescription('Papas fritas crocantes.');
        $PS1->setPrice(900);
        $PS1->setImage('chips_papa.jpg');
        $PS1->setCategory($snacks);
        $manager->persist($PS1);

        $PS2 = new Product();
        $PS2->setName('Barrita de cereal');
        $PS2->setDescription('Barrita de cereal con miel.');
        $PS2->setPrice(700);
        $PS2->setImage('barrita_cereal.jpg');
        $PS2->setCategory($snacks);
        $manager->persist($PS2);

        // ==== PRODUCTOS DESAYUNOS ====

        $PD1 = new Product();
        $PD1->setName('Tostado de Jamón y Queso');
        $PD1->setDescription('Pan de molde con jamón y queso derretido.');
        $PD1->setPrice(1800);
        $PD1->setImage('tostado.jpg');
        $PD1->setCategory($desayunos);
        $manager->persist($PD1);

        $PD2 = new Product();
        $PD2->setName('Medialuna');
        $PD2->setDescription('Medialuna clásica de manteca.');
        $PD2->setPrice(700);
        $PD2->setImage('medialuna.jpg');
        $PD2->setCategory($desayunos);
        $manager->persist($PD2);

        $manager->flush();
    }
}

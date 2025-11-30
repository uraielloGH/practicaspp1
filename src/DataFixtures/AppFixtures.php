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

        // ==== PRODUCTOS ====

        $p1 = new Product();
        $p1->setName('Café Latte');
        $p1->setDescription('Café con leche espumosa.');
        $p1->setPrice(1200);
        $p1->setImage('latte.jpg');
        $p1->setCategory($bebidas);
        $manager->persist($p1);

        $p2 = new Product();
        $p2->setName('Jugo de Naranja');
        $p2->setDescription('Jugo exprimido natural.');
        $p2->setPrice(1500);
        $p2->setImage('jugo_naranja.jpg');
        $p2->setCategory($bebidas);
        $manager->persist($p2);

        $p3 = new Product();
        $p3->setName('Chips de papa');
        $p3->setDescription('Papas fritas crocantes.');
        $p3->setPrice(900);
        $p3->setImage('chips_papa.jpg');
        $p3->setCategory($snacks);
        $manager->persist($p3);

        $p4 = new Product();
        $p4->setName('Barrita de cereal');
        $p4->setDescription('Barrita de cereal con miel.');
        $p4->setPrice(700);
        $p4->setImage('barrita_cereal.jpg');
        $p4->setCategory($snacks);
        $manager->persist($p4);

        $manager->flush();
    }
}

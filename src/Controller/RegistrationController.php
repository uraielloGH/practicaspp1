<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controlador responsable del caso de uso CU001 - Registrarse.
 *
 * Flujo principal:
 *  - GET /register: mostrar formulario de registro.
 *  - POST /register: procesar datos, validar y, si son válidos,
 *    persistir el nuevo usuario y redirigir al login con mensaje de éxito.
 */
class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        // Crear una nueva instancia de usuario (a completar con los datos del formulario)
        $user = new User();

        // Crear el formulario de registro y asociarlo a la entidad User
        $form = $this->createForm(RegistrationFormType::class, $user);

        // Procesar la petición HTTP (GET: solo muestra, POST: intenta cargar y validar datos)
        $form->handleRequest($request);

        // [Datos válidos] Cuando el formulario se envió y pasó las validaciones
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Hashear la contraseña ingresada por el usuario antes de guardarla
                $password = $form->get('password')->getData();
                $hashedPassword = $passwordHasher->hashPassword($user, $password);
                $user->setPassword($hashedPassword);

                // Guardar el nuevo usuario en la base de datos
                $em->persist($user);
                $em->flush();

                // Confirmación de guardado: mensaje de éxito para el usuario
                $this->addFlash('success', 'Cuenta creada con éxito.');

                // Redirigir al formulario de login, completando el caso de uso CU001
                return $this->redirectToRoute('app_login');
            } catch (UniqueConstraintViolationException $e) {
                // Manejo explícito del caso en que ya existe un usuario con el mismo email
                $this->addFlash(
                    'error',
                    'Ya existe un usuario registrado con este correo electrónico.'
                );
            }
        }

        // [Datos inválidos] o primera visita (GET): mostrar el formulario o los errores de validación
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}

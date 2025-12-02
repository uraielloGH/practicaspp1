<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/perfil', name: 'profile_edit')]
    public function edit(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        // Usuario debe estar logueado para editar su perfil
        $user = $this->getUser();
        if (!$user instanceof User) {
            // Si no hay usuario, lo mandamos al login
            return $this->redirectToRoute('app_login');
        }

        // Creamos el formulario con los datos actuales del perfil
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        // Procesamos el formulario cuando el usuario lo envía
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string|null $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // Si escribió una nueva contraseña, la encriptamos antes de guardar
            if (!empty($plainPassword)) {
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }

            // Nombre y email se actualizan solos porque están mapeados al User
            $entityManager->flush();

            // Avisamos que el perfil se guardó bien
            $this->addFlash('success', 'Perfil actualizado correctamente.');

            // Recargamos la página para mostrar los datos actualizados
            return $this->redirectToRoute('profile_edit');
        }

        // Primera carga o formulario con errores
        return $this->render('profile/edit.html.twig', [
            'profileForm' => $form->createView(),
        ]);
    }
}

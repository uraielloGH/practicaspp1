<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Controlador encargado del proceso de autenticación (CU002).
 * Básicamente muestra el formulario de login y maneja posibles errores.
 */
class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si hubo un intento de login fallido, Symfony nos da el error acá.
        $error = $authenticationUtils->getLastAuthenticationError();

        // Symfony también recuerda el último email que el usuario escribió.
        $lastUsername = $authenticationUtils->getLastUsername();

        // Renderizamos la vista de login, pasando el último email y el error.
        // Esto coincide con la parte del diagrama: "Renderizar login.html.twig".
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Symfony maneja el logout automáticamente, así que este método nunca se ejecuta.
        // Por eso solo dejamos esta excepción para que quede claro.
        throw new \LogicException('Symfony maneja el logout automáticamente desde el firewall.');
    }

    #[Route(path: '/forgot-password', name: 'app_forgot_password')]
    public function forgotPassword(Request $request): Response
    {
        // Si viene un POST, significa que el usuario envió el formulario.
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');

            if ($email) {
                // Como es un trabajo académico, no enviamos mails reales.
                // Simplemente mostramos un aviso general para simular el proceso.
                $this->addFlash(
                    'success',
                    'Si el correo está registrado, te enviamos un email con instrucciones para restablecer tu contraseña.'
                );
            } else {
                // Caso donde el usuario envía el formulario vacío.
                $this->addFlash('error', 'Por favor ingresá un correo electrónico válido.');
            }
        }

        // Mostramos la pantalla de "olvidé mi contraseña".
        return $this->render('security/forgot_password.html.twig');
    }
}

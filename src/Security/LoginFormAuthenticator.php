<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * Autenticador principal del sistema.
 * Este archivo se encarga de tomar los datos del formulario de login
 * y validar las credenciales del usuario (CU002).
 */
class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    /**
     * Este método se ejecuta cuando el usuario hace POST en /login.
     * Acá tomamos email y contraseña del formulario y armamos el "passport"
     * que Symfony usa para validar si las credenciales son correctas.
     */
    public function authenticate(Request $request): Passport
    {
        // Guardamos el email para mostrarlo de nuevo en caso de error
        $email = $request->request->get('email', '');
        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            // Buscar usuario por email (esto llama al UserRepository internamente)
            new UserBadge($email),

            // Verificar contraseña con la guardada en la BD
            new PasswordCredentials($request->request->get('password', '')),

            // Badges: extras que Symfony valida automáticamente
            [
                // Verifica que el token CSRF del formulario sea válido
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),

                // Habilita la opción “recordarme”
                new RememberMeBadge(),
            ]
        );
    }

    /**
     * Si el login fue exitoso, este método decide a dónde redirigir.
     * Coincide con la parte “autenticación exitosa → redirigir a página principal”
     * del diagrama del CU002.
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Si el usuario estaba intentando entrar a otra página antes del login,
        // Symfony lo redirige ahí.
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // Si no, lo mandamos a la página principal de la app
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }

    /**
     * Este método indica cuál es la ruta del login cuando Symfony la necesita.
     */
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}

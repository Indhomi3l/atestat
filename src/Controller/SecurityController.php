<?php

namespace App\Controller;

use App\Security\GroovyAuthenticator;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class SecurityController extends AbstractController
{
    public function __construct(
        private readonly SpotifyWebAPI $spotifyWebAPI,
        private readonly Session $session,
        private readonly GroovyAuthenticator $authenticator
    ){}

    #[Route(path: '/login', name: 'app_login')]
    public function login(): Response
    {
        return $this->render('security/login.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @throws \Exception
     */
    #[Route(path: '/spotify/redirect', name: 'app_index_redirect_from_spotify')]
    public function callbackFromSpotify(Request $request): Response
    {
        try {
            $this->session->requestAccessToken($request->query->get('code'));
        } catch (Throwable) {
            return $this->redirectToRoute('app_index_authorize_with_spotify');
        }

        $this->spotifyWebAPI->setAccessToken($this->session->getAccessToken());
        $me = $this->spotifyWebAPI->me();
        $this->authenticator->authenticateWithSpotify($me);
        return $this->redirectToRoute('app_index_home');
    }

    #[Route(path: '/spotify/authorize', name: 'app_index_authorize_with_spotify')]
    public function authorizeWithSpotify(): Response
    {
        $options = [
            'scope' => [
                'user-read-email',
            ],
        ];

        return $this->redirect($this->session->getAuthorizeUrl($options));
    }
}

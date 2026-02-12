<?php

namespace App\Controller;

use App\Form\ResetPasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\UserRepository;
use App\Services\JWTService;
use App\Services\SendEmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    #[Route(path: '/forgot_password', name: 'app_forgot_password_request')]
    public function forgotPassword(
        Request $request,
        JWTService $jwtService,
        SendEmailService $sendEmailService,
        UserRepository $userRepository
    ): Response {
        $formForgotPassword = $this->createForm(ResetPasswordRequestFormType::class);
        $formForgotPassword->handleRequest($request);


        if ($formForgotPassword->isSubmitted() && $formForgotPassword->isValid()) {
            $user = $userRepository->findOneByEmail(
                $formForgotPassword->get('email')->getData()
            );
            //génère un jwt token et envoie un email avec le lien de réinitialisation
            $header = ['typ' => 'JWT', 'alg' => 'HS256'];
            $payload = ['user_id' => $user->getId(), 'email' => $user->getEmail(), 'aud' => 'reset_password'];
            $token = $jwtService->generate($header, $payload, $this->getParameter('app.jwt_secret_key'));

            $sendEmailService->send(
                'no-reply@myapp.com',
                $user->getEmail(),
                'Réinitialisation de votre mot de passe',
                'password_reset',
                compact('user', 'token')
            );
            $this->addFlash('success', 'Un email vous a été envoyé avec un lien pour réinitialiser votre mot de passe.');

            return $this->redirectToRoute('app_login');
        }


        return $this->render('security/forgot_password.html.twig', [
            'formForgotPassword' => $formForgotPassword->createView()
        ]);
    }

    #[Route(path: '/reset_password/{token}', name: 'app_reset_password')]
    public function resetPassword(
        UserRepository $userRepository,
        JWTService $jwtService,
        string $token,
        Request $request
    ): Response {
        //vérifier et décoder le token jwt 
        if (
            $jwtService->isValid($token) &&
            !$jwtService->isExpired($token) &&
            $jwtService->check($token, $this->getParameter('app.jwt_secret_key'))
        ) {
            $payload = $jwtService->getPayload($token);
            //Récupérer l'utilisateur
            $user = $userRepository->find($payload['user_id']);

            if ($user) {
                $formResetPassword = $this->createForm(ResetPasswordFormType::class);
                $formResetPassword->handleRequest($request);
                if ($formResetPassword->isSubmitted() && $formResetPassword->isValid()) {
                    $user->setPassword(
                        password_hash(
                            $formResetPassword->get('password')->getData(),
                            PASSWORD_BCRYPT
                        )
                    );
                    $userRepository->save($user, true);
                    //invalider le token jwt sinon je peux réinitialiser le mot de passe plusieurs fois avec le même token et 
                    //je serai tjrs redirigé vers la page de réinitialisation de mot de passe
                    $request->getSession()->invalidate();

                    $this->addFlash('success', 'Votre mot de passe a bien été réinitialisé. Vous pouvez maintenant vous connecter.');
                    return $this->redirectToRoute('app_login');
                }
                return $this->render('security/reset_password.html.twig', [
                    'formResetPassword' => $formResetPassword->createView()
                ]);
            }
        }
        $this->addFlash('danger', 'Le token est invalide ou a expiré');
        return $this->redirectToRoute('app_login');
    }
}

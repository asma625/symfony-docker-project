<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\UsersAuthenticator;
use App\Services\JWTService;
use App\Services\SendEmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        Security $security,
        EntityManagerInterface $entityManager,
        JWTService $jwtService,
        SendEmailService $sendEmailService
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $entityManager->persist($user);
            $entityManager->flush();


            //Générer le jeton JWT à la création du compte
            $header = ['typ' => 'JWT', 'alg' => 'HS256'];
            $payload = ['user_id' => $user->getId(), 'email' => $user->getEmail(), 'aud' => 'email_confirmation'];
            $token = $jwtService->generate($header, $payload, $this->getParameter('app.jwt_secret_key'));
            $sendEmailService->send(
                'no-reply@myapp.com',
                $user->getEmail(),
                'Confirmation de votre compte',
                'register',
                compact('user', 'token')
            );

            //envoyer un email de confirmation
            if (null !== $token && !$user->isVerified()) {
                $this->addFlash('success', 'Votre compte a été créé ! Un email de confirmation vous a été envoyé.');
                return $this->redirectToRoute('app_login');
            }


            // return $security->login($user, UsersAuthenticator::class, 'main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
    #[Route('/verify/{token}', name: 'app_verify_email')]
    public function verifyUserEmail(
        $token,
        EntityManagerInterface $entityManager,
        JWTService $jwtService
    ): Response {
        //Vérifier le token
        if (
            $jwtService->isValid($token) &&
            !$jwtService->isExpired($token) &&
            $jwtService->check($token, $this->getParameter('app.jwt_secret_key'))
        ) {
            $payload = $jwtService->getPayload($token);


            //Récupérer l'utilisateur
            $user = $entityManager->getRepository(User::class)->find($payload['user_id']);

            if ($user && !$user->isVerified()) {
                $user->setIsVerified(true);
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Votre compte a bien été vérifié ! Vous pouvez maintenant vous connecter.');
                return $this->redirectToRoute('app_home');
            }
        }

        $this->addFlash('error', 'Le lien de vérification est invalide.');
        return $this->redirectToRoute('app_register');
    }
}

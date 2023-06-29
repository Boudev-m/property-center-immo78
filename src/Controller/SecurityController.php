<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authUtils): Response
    {
        $lastUserName = $authUtils->getLastUsername(); // get last username from input user
        $error = $authUtils->getLastAuthenticationError(); // get error auth
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUserName,
            'error' => $error
        ]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout(): Response
    {
        return $this->render('security/login.html.twig');
    }

    #[Route('/registration', name: 'registration')]
    public function registration(/*UserPasswordHasherInterface $passwordHasher*/)
    {
        // // ... e.g. get the user data from a registration form
        // $user = new User();
        // $plaintextPassword = 'ok';

        // // hash the password (based on the security.yaml config for the $user class)
        // $hashedPassword = $passwordHasher->hashPassword(
        //     $user,
        //     $plaintextPassword
        // );
        // $user->setPassword($hashedPassword);

        return $this->render('security/registration.html.twig');
    }
}

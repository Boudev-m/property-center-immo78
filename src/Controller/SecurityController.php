<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/connexion', name: 'login')]
    public function login(AuthenticationUtils $authUtils): Response
    {
        // check if an user is logged, redirect to home (or dashboard if the user is admin)
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('home');
        // }

        // get login error if there is one
        $error = $authUtils->getLastAuthenticationError();
        // get last username entered by the user
        $lastUserName = $authUtils->getLastUsername();
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUserName,
            'error' => $error
        ]);
    }


    #[Route('/deconnexion', name: 'logout')]
    public function logout(): void
    {
        // return $this->redirectToRoute('home');
        // This method can be blank - it will be intercepted by the logout key on your firewall.
    }


    // #[Route('/registration', name: 'registration')]
    // public function registration(UserPasswordHasherInterface $passwordHasher)
    // {
    //     // // ... e.g. get the user data from a registration form
    //     // $user = new User();
    //     // $plaintextPassword = 'ok';

    //     // // hash the password (based on the security.yaml config for the $user class)
    //     // $hashedPassword = $passwordHasher->hashPassword(
    //     //     $user,
    //     //     $plaintextPassword
    //     // );
    //     // $user->setPassword($hashedPassword);

    //     return $this->render('security/registration.html.twig');
    // }

}

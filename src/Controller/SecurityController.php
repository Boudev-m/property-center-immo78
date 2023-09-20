<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\UsersAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

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

        // if login successful
        if (!$error) {
            $this->addFlash('success', 'Vous êtes connecté.');
        }

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


    #[Route('/admin/inscription', name: 'admin.register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UsersAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            // For authenticate the new user after registration
            // return $userAuthenticator->authenticateUser(
            //     $user,
            //     $authenticator,
            //     $request
            // );
            $this->addFlash('success', 'Compte "' . $user->getUserIdentifier() . '" crée avec succès.');
            return $this->redirectToRoute('admin.property.index',);
        }

        return $this->render('admin/security/registration.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}

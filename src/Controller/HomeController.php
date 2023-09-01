<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Notification\ContactNotification;
use App\Repository\NewsRepository;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route({"/","/home", "/index"}, name="home")
     * @param PropertyRepository $repository
     * @return Response
     */
    #[Route(['/', '/home', '/index'], name: 'home')]
    public function index(PropertyRepository $propertyRepository, NewsRepository $newsRepository): Response
    {
        $lastProperties = $propertyRepository->findLatest();
        $lastNews = $newsRepository->findLatest();

        return $this->render('home/index.html.twig', [
            'properties' => $lastProperties,
            'news' => $lastNews
        ]);
    }

    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, ContactNotification $notification): Response
    {
        // create contact form for the current property
        $contact = new Contact;

        // $contact->setProperty($property);
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        // if contact form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Send mail
            $notification->notify($contact);
            $this->addFlash('success', 'Votre message a bien été envoyé. Nous vous contacterons dès que possible.');
        }

        return $this->render('home/contact.html.twig', ['contactForm' => $form->createView()]);
    }

    public function fallback(): Response
    {
        $response = new Response('Page not found', 404);
        return $this->render('home/error404.html.twig', [], $response);
    }
}

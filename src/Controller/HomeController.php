<?php

namespace App\Controller;

use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index(PropertyRepository $repository): Response
    {
        $lastProperties = $repository->findLatest();
        // dd($lastProperties[0]->getSlug(), $lastProperties[0]->getId());
        return $this->render('home/index.html.twig', [
            'properties' => $lastProperties
        ]);
    }

    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('home/contact.html.twig');
    }
}

<?php

namespace App\Controller;

use App\Repository\NewsRepository;
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
    public function contact(): Response
    {
        return $this->render('home/contact.html.twig');
    }

    public function fallback(): Response
    {
        $response = new Response('Page not found', 404);
        return $this->render('home/error404.html.twig', [], $response);
    }
}

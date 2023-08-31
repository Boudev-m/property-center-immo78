<?php

namespace App\Controller;

use App\Entity\News;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{

    private NewsRepository $repository;

    private EntityManagerInterface $em;

    public function __construct(NewsRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    // Shows all news
    #[Route('/actualites', name: 'news.index')]
    public function index(): Response
    {
        $news = $this->repository->findAll();
        return $this->render('news/index.html.twig', [
            'news' => $news
        ]);
    }

    // Shows one news article
    #[Route('/actualites/{id}-{slug}', name: 'news.show')]
    public function show(string $slug, News $article): Response
    {

        // if slug is different, so redirect with 301
        if ($article->getSlug() !== $slug) {
            return $this->redirectToRoute(
                'news.show',
                [
                    'id' => $article->getId(),
                    'slug' => $article->getSlug()
                ],
                301
            );
        }

        // get posts of the article
        $posts = $article->getPosts();
        return $this->render('news/show.html.twig', [
            'article' => $article,
            'posts' => $posts
        ]);
    }
}

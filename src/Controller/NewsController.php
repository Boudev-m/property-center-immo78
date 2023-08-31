<?php

namespace App\Controller;

use App\Entity\News;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function show(string $slug, News $article, Request $request): Response
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

        // create empty property
        $post = new Post();

        // get post form
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        // if post form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // set the news associated to the post
            $post->setNews($article);
            // the post entity must be tracked by EntityManager
            $this->em->persist($post); // prepare data backup
            $this->em->flush(); // data backup
            return $this->redirectToRoute('news.show', [
                'id' => $article->getId(),
                'slug' => $article->getSlug()
            ]);
        }

        // get posts of the article
        $posts = $article->getPosts();

        return $this->render('news/show.html.twig', [
            'article' => $article,
            'posts' => $posts,
            'form' => $form
        ]);
    }
}

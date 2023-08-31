<?php

namespace App\Controller\Admin;

use App\Entity\News;
use App\Form\NewsType;
use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')] // root route 
class NewsController extends AbstractController
{

    // Shows all news
    #[Route('/actualites', name: 'admin.news.index', methods: ['GET'])]
    public function index(NewsRepository $newsRepository): Response
    {
        return $this->render('admin/news/index.html.twig', [
            'news' => $newsRepository->findAll(),
        ]);
    }

    // Create a news article
    #[Route('/actualites/nouveau', name: 'admin.news.new', methods: ['GET', 'POST'])]
    public function new(Request $request, NewsRepository $newsRepository): Response
    {
        $article = new News();
        $form = $this->createForm(NewsType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newsRepository->save($article, true);
            $this->addFlash('success', 'Article ajouté avec succès.');
            return $this->redirectToRoute('admin.news.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/news/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    // Edit one news article
    #[Route('/actualites/{id}/editer', name: 'admin.news.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, News $article, NewsRepository $newsRepository): Response
    {
        $form = $this->createForm(NewsType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newsRepository->save($article, true);
            $this->addFlash('success', 'Article modifié avec succès.');
            return $this->redirectToRoute('admin.news.index');
        }

        return $this->render('admin/news/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    // Delete one news article
    #[Route('/actualites/{id}/supprimer', name: 'admin.news.delete', methods: ['POST'])]
    public function delete(Request $request, News $article, NewsRepository $newsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $newsRepository->remove($article, true);
            $this->addFlash('success', 'Article supprimé avec succès.');
        }

        return $this->redirectToRoute('admin.news.index', [], Response::HTTP_SEE_OTHER);
    }
}

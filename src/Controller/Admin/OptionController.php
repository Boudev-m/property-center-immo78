<?php

namespace App\Controller\Admin;

use App\Entity\Option;
use App\Form\OptionType;
use App\Repository\OptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')] // Racine 
class OptionController extends AbstractController
{

    // Shows all options
    #[Route('/options', name: 'admin.option.index', methods: ['GET'])]
    public function index(OptionRepository $optionRepository): Response
    {
        return $this->render('admin/option/index.html.twig', [
            'options' => $optionRepository->findAll(),
        ]);
    }

    // Create an option
    #[Route('/options/new', name: 'admin.option.new', methods: ['GET', 'POST'])]
    public function new(Request $request, OptionRepository $optionRepository): Response
    {
        $option = new Option();
        $form = $this->createForm(OptionType::class, $option);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $optionRepository->save($option, true);

            return $this->redirectToRoute('admin.option.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/option/new.html.twig', [
            'option' => $option,
            'form' => $form,
        ]);
    }

    // Shows one option (useless)
    // #[Route('/options/{id}', name: 'admin.option.show', methods: ['GET'])]
    // public function show(Option $option): Response
    // {
    //     return $this->render('admin/option/show.html.twig', [
    //         'option' => $option,
    //     ]);
    // }


    // Edit one option
    #[Route('/options/{id}/edit', name: 'admin.option.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Option $option, OptionRepository $optionRepository): Response
    {
        $form = $this->createForm(OptionType::class, $option);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $optionRepository->save($option, true);

            return $this->redirectToRoute('admin.option.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/option/edit.html.twig', [
            'option' => $option,
            'form' => $form,
        ]);
    }

    // Delete one option
    #[Route('/options/{id}', name: 'admin.option.delete', methods: ['POST'])]
    public function delete(Request $request, Option $option, OptionRepository $optionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $option->getId(), $request->request->get('_token'))) {
            $optionRepository->remove($option, true);
        }

        return $this->redirectToRoute('admin.option.index', [], Response::HTTP_SEE_OTHER);
    }
}

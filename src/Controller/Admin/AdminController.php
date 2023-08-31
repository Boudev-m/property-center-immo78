<?php

namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')] // Root route
class AdminController extends AbstractController
{

    /**
     * @var PropertyRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;



    public function __construct(PropertyRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }



    // Shows all properties to manage (visible or not)
    #[Route('/biens', name: 'admin.property.index')]
    public function index(): Response
    {
        $properties = $this->repository->findAll();
        return $this->render('admin/property/index.html.twig', compact('properties'));
    }



    // Create new property
    /**
     * @param Request $request
     */
    #[Route('/biens/nouveau', name: 'admin.property.new')]
    public function new(Request $request): Response
    {
        // create empty property
        $property = new Property();

        // create Property form
        $form = $this->createForm(PropertyType::class, $property);

        // get datas from request and transfer to $property
        // The handleRequest method processes the datas request
        $form->handleRequest($request);

        // check if form is submitted and valid, so save in DB and redirect to index
        if ($form->isSubmitted() && $form->isValid()) {
            // the property entity must be tracked by EntityManager
            $this->em->persist($property); // prepare data backup
            $this->em->flush(); // data backup
            $this->addFlash('success', 'Bien ajouté avec succès.');
            return $this->redirectToRoute('property.show', [
                'id' => $property->getId(),
                'slug' => $property->getSlug()
            ]);
        }

        return $this->render('admin/property/new.html.twig', [
            'property' => $property,
            'form' => $form->createView() // create view for the template
        ]);
    }



    // Edit one property
    /**
     * @param Property $property
     * @param Request $request
     */
    #[Route('/biens/{id}/editer', name: 'admin.property.edit', methods: ['GET', 'POST'])]
    public function edit(string $id, Property $property, Request $request): Response
    {
        // create Property form and pass $property datas to fill form
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        // check if form is submitted and valid, so save in DB and redirect to index
        // the verification is based on the Property entity
        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->flush();
            $this->addFlash('success', "Bien n°$id edité avec succès.");
            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin/property/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView() // create view for the template
        ]);
    }



    // Delete one property
    /**
     * @param Property $property
     * @param Request $request
     */
    #[Route('/biens/{id}/supprimer', name: 'admin.property.delete', methods: ['POST'])]
    public function delete(Property $property, Request $request): RedirectResponse
    {
        // Check if token in delete form is valid
        if ($this->isCsrfTokenValid('delete' . $property->getId(), $request->get('_token'))) {
            $this->em->remove($property);
            $this->em->flush();
            $this->addFlash('success', 'Bien supprimé avec succès.');
        }
        return $this->redirectToRoute('admin.property.index');
    }
}

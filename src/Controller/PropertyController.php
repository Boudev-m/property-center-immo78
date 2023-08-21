<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Picture;
use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\ContactType;
use App\Form\PropertySearchType;
use App\Notification\ContactNotification;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;     // for paginate the list of properties

class PropertyController extends AbstractController
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

    // Shows all unsold properties (12 per page)
    #[Route('/biens', name: 'property.index')]
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        // Search filter
        $search = new PropertySearch;
        $form = $this->createForm(PropertySearchType::class, $search);
        $form->handleRequest($request);

        // response
        return $this->render('property/index.html.twig', [
            'current_menu' => 'properties',
            'properties' => $this->repository->paginateAllVisible($search, $request->query->getInt('page', 1)),
            'form' => $form->createView()
        ]);
    }



    // Shows one property
    // using regex for slug in param
    /**
     * @param Property $property
     * @param string $slug
     * @return Response
     */
    #[Route('/biens/{id}-{slug}', name: 'property.show', requirements: ["slug" => "[a-z0-9\-]*"])]
    public function show(string $slug, Property $property, Request $request, ContactNotification $notification): Response
    {
        // if slug is different, so redirect with 301
        if ($property->getSlug() !== $slug) {
            return $this->redirectToRoute(
                'property.show',
                [
                    'id' => $property->getId(),
                    'slug' => $property->getSlug()
                ],
                301
            );
        }

        // create contact form for the current property
        $contact = new Contact;
        $contact->setProperty($property);
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        // if contact form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {

            // Send mail
            $notification->notify($contact);
            $this->addFlash('success', 'Message envoyé.');
            // return $this->redirectToRoute(
            //     'property.show',
            //     [
            //         'id' => $property->getId(),
            //         'slug' => $property->getSlug()
            //     ]
            // );
        }

        $pictures = $this->em->getRepository(Picture::class)->findForProperty($property);
        // dd($pictures);

        // check if the property has an image
        if ($pictures->containsKey($property->getId())) {
            $property->setPicture($pictures->get($property->getId()));
        }

        // not need find method here because link between id in param and Property
        // $property = $this->repository->find($id);
        return $this->render('property/show.html.twig', [
            'property' => $property,
            'current_menu' => 'properties',
            'contactForm' => $form->createView()
        ]);
    }
    // Voir les annotations S6
}

<?php

namespace App\Controller;

use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;     // for paginate the list of properties
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        $properties = $paginator->paginate(
            $this->repository->findAllVisible($search), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            12 /*limit per page*/
        );
        return $this->render('property/index.html.twig', [
            'current_menu' => 'properties',
            'properties' => $properties,
            'form' => $form->createView()
        ]);
    }



    // Shows one property
    // Utilisation des regex pour le param slug
    /**
     * @param Property $property
     * @param string $slug
     * @return Response
     */
    #[Route('/biens/{id}-{slug}', name: 'property.show', requirements: ["slug" => "[a-z0-9\-]*"])]
    public function show($id, $slug, Property $property): Response
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

        // get options of property
        //
        // not need find method here because link between id in param and Property
        // $property = $this->repository->find($id);
        return $this->render('property/show.html.twig', [
            'property' => $property,
            'current_menu' => 'properties'
        ]);
    }
    // Voir les annotations S6
}

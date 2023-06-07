<?php

namespace App\Controller;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    // Shows all unsold properties
    #[Route('/biens', name: 'property.index')]
    public function index(): Response
    {
        $properties = $this->repository->findAllVisible();
        return $this->render('property/index.html.twig', [
            'properties' => $properties,
            'current_menu' => 'properties'
        ]);
    }



    // Shows one property
    // Utilisation des regex pour le param slug
    /**
     * @param Property $property
     * @param string $slug
     * @return Response
     */
    #[Route('/biens/{slug}{id}', name: 'property.show', requirements: ["slug" => "[a-z0-9\-]*"])]
    public function show(Property $property, $slug, $id): Response
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

        // not need find method here because link between id in param and Property
        // $property = $this->repository->find($id);
        return $this->render('property/show.html.twig', [
            'property' => $property,
            'current_menu' => 'properties'
        ]);
    }
    // Voir les annotations S6
}

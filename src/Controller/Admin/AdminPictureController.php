<?php

namespace App\Controller\Admin;

use App\Entity\Picture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/picture')] // Racine 
class AdminPictureController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    // Delete one picture (dynamic method)
    /**
     * @param Picture $property
     * @param Request $request
     */
    #[Route('/{id}', name: 'admin.picture.delete', methods: ['DELETE'])]
    public function delete(Picture $picture, Request $request): JsonResponse
    {
        // Check if token in delete form is valid
        $datas = json_decode($request->getContent(), true);
        if ($this->isCsrfTokenValid('delete' . $picture->getId(), $datas['_token'])) {
            $this->em->remove($picture);
            $this->em->flush();
            return new JsonResponse(['success' => 'Image supprimée', 200]);
        }
        // return to edit page of property
        return new JsonResponse(['err' => 'Token invalide'], 400);
    }

    // // Delete one picture (classic method)
    // /**
    //  * @param Picture $property
    //  * @param Request $request
    //  */
    // #[Route('/{id}', name: 'admin.picture.delete', methods: ['POST'])]
    // public function delete(Picture $picture, Request $request): RedirectResponse
    // {
    //     // Check if token in delete form is valid
    //     if ($this->isCsrfTokenValid('delete' . $picture->getId(), $request->get('_token'))) {
    //         $this->em->remove($picture);
    //         $this->em->flush();
    //     }
    //     // return to edit page of property
    //     return $this->redirectToRoute('admin.property.edit', ['id' => $picture->getProperty()->getId()]);
    // }


}

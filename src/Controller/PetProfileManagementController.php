<?php

namespace App\Controller;

use App\Entity\PetProfileManagement;
use App\Form\PetProfileManagementType;
use App\Repository\PetProfileManagementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pet/profile/management')]
final class PetProfileManagementController extends AbstractController
{
    #[Route(name: 'app_pet_profile_management_index', methods: ['GET'])]
    public function index(PetProfileManagementRepository $petProfileManagementRepository): Response
    {
        return $this->render('pet_profile_management/index.html.twig', [
            'pet_profile_managements' => $petProfileManagementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pet_profile_management_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $petProfileManagement = new PetProfileManagement();
        $form = $this->createForm(PetProfileManagementType::class, $petProfileManagement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($petProfileManagement);
            $entityManager->flush();

            return $this->redirectToRoute('app_pet_profile_management_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pet_profile_management/new.html.twig', [
            'pet_profile_management' => $petProfileManagement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pet_profile_management_show', methods: ['GET'])]
    public function show(PetProfileManagement $petProfileManagement): Response
    {
        return $this->render('pet_profile_management/show.html.twig', [
            'pet_profile_management' => $petProfileManagement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pet_profile_management_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PetProfileManagement $petProfileManagement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PetProfileManagementType::class, $petProfileManagement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pet_profile_management_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pet_profile_management/edit.html.twig', [
            'pet_profile_management' => $petProfileManagement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pet_profile_management_delete', methods: ['POST'])]
    public function delete(Request $request, PetProfileManagement $petProfileManagement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$petProfileManagement->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($petProfileManagement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pet_profile_management_index', [], Response::HTTP_SEE_OTHER);
    }
}

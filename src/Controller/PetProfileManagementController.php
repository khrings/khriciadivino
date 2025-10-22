<?php

namespace App\Controller;

use App\Entity\PetProfileManagement;
use App\Form\PetProfileManagementType;
use App\Repository\PetProfileManagementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $petProfileManagement = new PetProfileManagement();
        $form = $this->createForm(PetProfileManagementType::class, $petProfileManagement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('pet_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // handle exception if something happens during file upload
                }

                $petProfileManagement->setImage($newFilename);
            }

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
    public function edit(Request $request, PetProfileManagement $petProfileManagement, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(PetProfileManagementType::class, $petProfileManagement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('pet_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // handle exception if something happens during file upload
                }

                $petProfileManagement->setImage($newFilename);
            }

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

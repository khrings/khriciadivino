<?php

namespace App\Controller;

use App\Entity\Productss;
use App\Form\ProductssType;
use App\Repository\ProductssRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;





#[Route('/productss')]
final class ProductssController extends AbstractController
{
    #[Route(name: 'app_productss_index', methods: ['GET'])]
    public function index(ProductssRepository $productssRepository): Response
    {
        return $this->render('productss/index.html.twig', [
            'productsses' => $productssRepository->findAll(),
        ]);
    }


#[Route('/new', name: 'app_productss_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
{
    $productss = new Productss();
    $form = $this->createForm(ProductssType::class, $productss);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $imageFile = $form->get('imagefilename')->getData();
        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

            $imageFile->move($this->getParameter('images_directory'), $newFilename);
            $productss->setImageFilename($newFilename);
        }
        $entityManager->persist($productss);
        $entityManager->flush();

        return $this->redirectToRoute('app_productss_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('productss/new.html.twig', [
        'productss' => $productss,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_productss_show', methods: ['GET'])]
    public function show(Productss $productss): Response
    {
        return $this->render('productss/show.html.twig', [
            'productss' => $productss,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_productss_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Productss $productss, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductssType::class, $productss);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_productss_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('productss/edit.html.twig', [
            'productss' => $productss,
            'form' => $form,
        ]);
    }

    
#[Route('/dashboard', name: 'app_dashboard')]
public function dashboard(ProductssRepository $productssRepository): Response
{
    // Count total products
    $totalProducts = $productssRepository->count([]);

    return $this->render('dashboard/index.html.twig', [
        'totalProducts' => $totalProducts,
    ]);
}

    #[Route('/{id}', name: 'app_productss_delete', methods: ['POST'])]
    public function delete(Request $request, Productss $productss, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$productss->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($productss);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_productss_index', [], Response::HTTP_SEE_OTHER);
    }
}
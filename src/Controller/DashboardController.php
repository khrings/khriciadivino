<?php

namespace App\Controller;

use App\Entity\Dashboard;
use App\Form\DashboardType;
use App\Repository\DashboardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/dashboard')]
final class DashboardController extends AbstractController
{
    #[Route(name: 'app_dashboard_index', methods: ['GET'])]
    public function index(DashboardRepository $dashboardRepository): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'dashboards' => $dashboardRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_dashboard_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dashboard = new Dashboard();
        $form = $this->createForm(DashboardType::class, $dashboard);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($dashboard);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/new.html.twig', [
            'dashboard' => $dashboard,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dashboard_show', methods: ['GET'])]
    public function show(Dashboard $dashboard): Response
    {
        return $this->render('dashboard/show.html.twig', [
            'dashboard' => $dashboard,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_dashboard_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Dashboard $dashboard, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DashboardType::class, $dashboard);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/edit.html.twig', [
            'dashboard' => $dashboard,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dashboard_delete', methods: ['POST'])]
    public function delete(Request $request, Dashboard $dashboard, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dashboard->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($dashboard);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dashboard_index', [], Response::HTTP_SEE_OTHER);
    }
}

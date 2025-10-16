<?php

namespace App\Controller;

use App\Entity\Stocks;
use App\Form\StocksType;
use App\Repository\StocksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/stocks')]
final class StocksController extends AbstractController
{
    #[Route(name: 'app_stocks_index', methods: ['GET'])]
    public function index(StocksRepository $stocksRepository): Response
    {
        return $this->render('stocks/index.html.twig', [
            'stocks' => $stocksRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_stocks_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $stock = new Stocks();
        $form = $this->createForm(StocksType::class, $stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $now = new \DateTimeImmutable();
            $stock->setCreateAt($now);
            $stock->setUpdateAt($now);

            // 🧩 Update related product quantity
            $product = $stock->getProductss();
            $quantityChange = $stock->getQuantityChange() ?? 0;

            if ($product) {
                $newQuantity = $product->getQuantity() + $quantityChange;
                $product->setQuantity($newQuantity);
                $entityManager->persist($product);
            }

            // Add change log entry
            $log = sprintf(
                "Stock updated for %s: %+d units (new total: %d)",
                $product ? $product->getName() : 'Unknown Product',
                $quantityChange,
                $product ? $product->getQuantity() : 0
            );
            $stock->setStockChangeLog($log);

            $entityManager->persist($stock);
            $entityManager->flush();

            $this->addFlash('success', 'Stock added and product quantity updated successfully.');

            return $this->redirectToRoute('app_stocks_index');
        }

        return $this->render('stocks/new.html.twig', [
            'stock' => $stock,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stocks_show', methods: ['GET'])]
    public function show(Stocks $stock): Response
    {
        return $this->render('stocks/show.html.twig', [
            'stock' => $stock,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_stocks_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Stocks $stock, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StocksType::class, $stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stock->setUpdateAt(new \DateTimeImmutable());

            $product = $stock->getProductss();
            $quantityChange = $stock->getQuantityChange() ?? 0;

            if ($product) {
                $newQuantity = $product->getQuantity() + $quantityChange;
                $product->setQuantity($newQuantity);
                $entityManager->persist($product);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Stock and product quantity updated.');

            return $this->redirectToRoute('app_stocks_index');
        }

        return $this->render('stocks/edit.html.twig', [
            'stock' => $stock,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stocks_delete', methods: ['POST'])]
    public function delete(Request $request, Stocks $stock, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stock->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($stock);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_stocks_index');
    }
}

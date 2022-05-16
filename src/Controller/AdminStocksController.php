<?php

namespace App\Controller;

use App\Entity\Stocks;
use App\Entity\Products;
use App\Form\StocksType;
use App\Form\FiltersType;
use App\filters\ProductsFilters;
use App\Repository\StocksRepository;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/stocks")
 */
class AdminStocksController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_stocks_index", methods={"GET", "POST"})
     */
    public function index(EntityManagerInterface $manager, Request $request, ProductsRepository $repoProduct): Response
    {
        $filter = new ProductsFilters;
        $form = $this->createForm(FiltersType::class, $filter);
        $form->handleRequest($request);

        $products = $repoProduct->findFilters($filter);


            // dd($products);

        return $this->render('admin_stocks/index.html.twig', [
            'products' => $products,
            'formFilter' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_admin_stocks_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Stocks $stock, StocksRepository $stocksRepository): Response
    {
        $form = $this->createForm(StocksType::class, $stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stocksRepository->add($stock, true);

            return $this->redirectToRoute('app_admin_stocks_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_stocks/edit.html.twig', [
            'stock' => $stock,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="app_admin_stocks_delete", methods={"GET", "POST"})
     */
    public function delete(Request $request, Products $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_products_index', [], Response::HTTP_SEE_OTHER);
    }
}

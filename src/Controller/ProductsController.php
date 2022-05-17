<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\FiltersType;
use App\filters\ProductsFilters;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductsController extends AbstractController
{
    /**
     * @Route("/products", name="app_products")
     */
    public function index(): Response
    {
        return $this->render('products/index.html.twig', [
            'controller_name' => 'ProductsController',
        ]);
    }

    /**
     * @Route("/catalogue", name="catalogue")
     */
    public function catalogue(ProductsRepository $repoProduct, Request $request) : Response
    {
       
        $filter = new ProductsFilters;
        $form = $this->createForm(FiltersType::class, $filter);


        $form->handleRequest($request);

        $products = $repoProduct->findFilters($filter);


        return $this->render("products/catalogue.html.twig", [
            "products" => $products,
            "formFilter" => $form->createView()
        ]);
    }

    /**
     * 
     * @Route("/fiche_produit/{id}", name="fiche_produit")
     */
    public function fiche_produit(Products $products, Request $request, EntityManagerInterface $manager) 
    {


        $form = $this->createForm(FormType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid())
        {
            $userCo = $this->getUser();

            $manager->persist($products);
            $manager->flush();

            return $this->redirectToRoute("fiche_produit", ['id' => $products->getId()]);

        }

        return $this->render("products/fiche_produit.html.twig", [
            "product" => $products,
        ]);
    }

}

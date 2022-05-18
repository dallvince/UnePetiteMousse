<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Products;
use App\Form\FiltersType;
use App\Form\CommentsType;
use App\filters\ProductsFilters;
use App\Repository\CommentsRepository;
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

        // dd($products);

        return $this->render("products/catalogue.html.twig", [
            "products" => $products,
            "formFilter" => $form->createView()
        ]);
    }

    /**
     * 
     * @Route("/fiche_produit/{id}", name="fiche_produit")
     */    
    public function fiche_produit($id, Products $products, Request $request, EntityManagerInterface $manager, CommentsRepository $repoComments, ProductsRepository $repoProducts) 
    {
        $products = $repoProducts->find($id);

        $messages = $repoComments->findBy(["products" => $products], array('id' => 'DESC'));

        $comments = new Comments;
        $formComments = $this->createForm(CommentsType::class, $comments);
        
        $formComments->handleRequest($request);

        if($formComments->isSubmitted() and $formComments->isValid())
        {
            $userCo = $this->getUser();

            $comments->setCreatedAt(new \DateTimeImmutable('now'));
            $comments->setProducts($products);
            $comments->setUsers($userCo); 

            $manager->persist($comments);
            $manager->flush();
        }

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
            "messages" => $messages,
            "commentsForm" => $formComments->createView()
        ]);
    }

}

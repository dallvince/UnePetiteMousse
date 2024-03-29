<?php

namespace App\Controller;

use App\Entity\Products;
use App\Entity\Stocks;
use App\Form\FiltersType;
use App\Form\ProductsType;
use App\filters\ProductsFilters;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/admin/products")
 */
class AdminProductsController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_products_index", methods={"GET", "POST"})
     */
    public function index(EntityManagerInterface $entityManager, ProductsRepository $repoProduct, Request $request): Response
    {

        $filter = new ProductsFilters;
        $form = $this->createForm(FiltersType::class, $filter);
        $form->handleRequest($request);

        $products = $repoProduct->findFiltersadmin($filter);

        return $this->render('admin_products/index.html.twig', [
            'products' => $products,
            'formFilter' => $form->createView()
        ]);
    }

    /**
     * @Route("/add_product", name="app_admin_products_new", methods={"GET", "POST"})
     */
    public function add_product(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Products();
        $form = $this->createForm(ProductsType::class, $product, ['add' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // $stock = new Stocks;
            // $stock->setUpdatedAt(new \DateTimeImmutable('now'));
            // $entityManager->persist($stock);
            // $entityManager->flush();

            $product->setCreatedAt(new \DateTimeImmutable('now'));
            $entityManager->persist($product);


            $pictureFile = $form->get('picture')->getData();

            if($pictureFile){
                $pictureName = date('YmdHis') . "-" . uniqid() . "." . $pictureFile->getClientOriginalExtension();
                
                $pictureFile->move($this->getParameter('pictureUpload'),  $pictureName);

                $product->setPicture($pictureName);
            }

            // $product->setStocks($stock);

            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash("success", "Produit N°" . $product->getId() . " ajouté !");

            return $this->redirectToRoute('app_admin_products_index', [], Response::HTTP_SEE_OTHER);
        }
        

        return $this->renderForm('admin_products/new.html.twig', [
            'product' => $product,
            'formProduct' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_products_show", methods={"GET"})
     */
    public function show(Products $product): Response
    {
        return $this->render('admin_products/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/{id}/edit_product", name="app_admin_products_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Products $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductsType::class, $product, ['edit' => true]);
        $form->handleRequest($request);

        if($form->isSubmitted() AND $form->isValid()){

            $pictureFile = $form->get('pictureUpdate')->getData();




            if($pictureFile){

                $PictureName = date('YmdHis') . "-" . uniqid() . "." . $pictureFile->getClientOriginalExtension();
                $pictureFile->move($this->getParameter('pictureUpload'),  $PictureName);

                if($product->getPicture()){ 
                    unlink($this->getParameter('pictureUpload') . "/" . $product->getPicture());
                    
                }  
                
                
                    $product->setPicture($PictureName);
                
            }

            $entityManager->persist($product);
            $entityManager->flush();
            $this->addFlash('success', 'Le produit N°' . $product->getiD() . ' "' . $product->getName() . '"' . ' a bien été modifié !');
            
            return $this->redirectToRoute('app_admin_products_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_products/edit.html.twig', [
            'product' => $product,
            'formProduct' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_products_delete", methods={"POST"})
     */
    public function delete(Request $request, Products $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_products_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/admin/product/picture/delete", name="picture_delete", methods={"POST"})
     */
    public function pictureDelete(Products $products, EntityManagerInterface $manager) : Response
    {
        if($products->getPicture())
        {
            unlink($this->getParameter('pictureUpload') . "/" . $products->getPicture());
            $products->setPicture(NULL);

            $manager->persist($products);
            $manager->flush();

            $this->addFlash("success", "L'image du produit" . "'" . $products->getName() . "'" . "à bien été supprimée");
            return $this->redirectToRoute('app_admin_products_edit', ["id" => $products->getId()]);
        } else {
            $this->addFlash("error", "Ce produit n'a pas d'image");
            return $this->redirectToRoute('app_admin_products_show');
        }
    }

    /**
     * @Route("/change/status/product", name="change_status_product", methods={"POST"})
     */
    public function change_status_product(Request $request, ProductsRepository $repoProduct, EntityManagerInterface $manager)
    {
        // request->request ===> $_POST[]
        $id = $request->request->get('id');

        $product = $repoProduct->find($id);

        if($product->getStatus() == 0)
        {
            $product->setStatus(1);
            // $message ='Produit activé';
        } else {
            $product->setStatus(0);
            // $message = 'Produit désactivé';
        }
        
        $manager->persist($product);
        $manager->flush();

        return new JsonResponse();
    }
}

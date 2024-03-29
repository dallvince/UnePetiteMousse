<?php

namespace App\Controller;

use App\Entity\Users;
use App\Service\Basket;
use App\Form\ProfilFormType;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

    /**
     * @Route("/basket")
     */

class BasketController extends AbstractController
{
    /**
     * @Route("/", name="app_basket")
     */
    public function index(SessionInterface $session, Basket $basket): Response
    {
        
        $basketSession = $session->get("basket");

        // dump($basketSession);

        if($session->get("basket"))
        {
            $basket->verification();
            $montant = $basket->montant();
        }
        else
        {
            $montant = 0;
        }

            return $this->render('basket/index.html.twig', [
                'basket' => $basketSession,
                "montant" => $montant
            ]);
    }

    /**
     * @Route("/add", name="basket_add")
     */
    public function add(Request $request, ProductsRepository $repoProduct, Basket $basket)
    {
        $productId = $request->request->get("product");
        $quantity = $request->request->get("quantity");

        // dump("product.id :" .$productId);

        $product = $repoProduct->find($productId);

        $basket->add($product->getName(), $product->getId(), $product->getPrice(), $quantity, $product->getPicture());

        return $this->redirectToRoute("app_basket");
    }

    /**
     * @Route("/remove_all", name="basket_remove_all")
     */
    public function remove_all(Basket $basket)
    {
        $basket->remove_all();
        return $this->redirectToRoute("app_basket");
    }

    /**
     * @Route("/remove/{id}", name="basket_remove")
     */
    public function remove($id, Basket $basket)
    {
        $basket->remove($id);
        return $this->redirectToRoute("app_basket");
    }

    /**
     * @Route("/change_quantity", name="change_quantity")
     */
    public function change_quantity(Request $request, Basket $basket, ProductsRepository $repoProduct)
    {
        $id = $request->request->get('id');
        $what = $request->request->get('what');

        $newQuantity = $basket->change($id, $what);

        $product = $repoProduct->find($id);

        $price = $product->getPrice();

        $montant = round($newQuantity * $price, 2);

        $max = ($newQuantity == $product->getStocks()->getQuantity()) ? true : false; // ? tu rentre dans le if et : tu rentre dans le else

        $array = [
            'value' => $newQuantity, 
            'montant' => $montant, 
            'montantTotal' => $basket->montant(),
            "max" => $max
        ];


        return new JsonResponse($array);
    }

    /**
     * @Route("/verification", name="verification")
     */
    public function verification(Request $request, Basket $basket, ProductsRepository $repoProduct)
    {
        // $id = $request->request->get('id');
        // $what = $request->request->get('what');


        return new JsonResponse();
    }

    /**
     * @Route("/paiement", name="basket_paiement")
     */
    public function paiement(Basket $basket)
    {
        $user = $this->getUser();

        $basket->paiement($user);

        return $this->redirectToRoute("app_basket");
    }

    /**
     * @Route("/paiement_page", name="paiement_page")
     */
    public function paiement_page(Request $request, EntityManagerInterface $entityManager)
    {

        $user = $this->getUser();
        $form = $this->createForm(ProfilFormType::class, $user);
        $form->handleRequest($request);

        
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a bien été modifié !');

            return $this->redirectToRoute('catalogue', [], Response::HTTP_SEE_OTHER);
        }
            

        
        
        return $this->render('profil/paiement_page.html.twig', [
            'users' => $user,
            'formProfil' => $form->createView()
            
        ]);

    }
}

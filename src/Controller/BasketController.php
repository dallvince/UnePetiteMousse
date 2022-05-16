<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use App\Service\Basket;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

    /**
     * @Route("/basket", name="app_basket")
     */

class BasketController extends AbstractController
{
    /**
     * @Route("/", name="app_basket")
     */
    public function index(): Response
    {
        return $this->render('basket/index.html.twig', [
            'controller_name' => 'BasketController',
        ]);
    }

    /**
     * @Route("/add", name="add_basket")
     */
    public function add(Request $request, ProductsRepository $repoProduct, Basket $basket)
    {
        $productId = $request->request->get("product");
        $quantity = $request->request->get("quantity");

        dump("product.id :" .$productId);

        $product = $repoProduct->find($productId);

        $basket->add($product->getName(), $product->getId(), $product->getPrice(), $quantity, $product->getPicture());

        return $this->redirectToRoute("app_basket");
    }
}

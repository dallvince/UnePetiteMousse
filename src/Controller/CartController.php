<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

    /**
     * @Route("/cart")
     */
class CartController extends AbstractController
{
    /**
     * @Route("/", name="app_cart")
     */
    public function index(SessionInterface $session, ProductsRepository $productRepository): Response
    {
        $cart = $session->get('cart', []);
        $cartWithProducts = [];

        foreach ($cart as $id => $quantity) {
            $cartWithProducts[] = [
                'product' => $productRepository->find($id),
                'quantity' => $quantity
            ];
        }
            // dd($cartWithProducts);

        $total = 0;

        foreach ( $cartWithProducts as $item) {
            $totalItem = $item['product']->getPrice() * $item['quantity'];
            $total += $totalItem;
        }

        return $this->render('cart/index.html.twig', [
            'items' => $cartWithProducts,
            'total' => $total
        ]);
    }

    /**
     * @Route("/add/{id}", name="cart_add")
     */
    public function cartAdd($id, Request $request, SessionInterface $session)
    {
        $session = $request->getSession();
        $cart = $session->get('cart', []);

        if(!empty($cart[$id]))
        {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $session->set('cart', $cart);
        
        return $this->redirectToRoute('app_cart', [ ]);
    }

    /**
     * @Route("/delete/{id}", name="cart_delete")
     */
    public function cartDelete($id, Request $request, SessionInterface $session)
    {
    $cart = $session->get('cart', []);
    
    if(!empty($cart[$id])) 
    {
        unset($cart[$id]);
    }

    $session->set('cart', $cart);

    return $this->redirectToRoute('app_cart');
        
    }
}

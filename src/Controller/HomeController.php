<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\ProductsType;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {

        return $this->render('home/index.html.twig', [
            
        ]);
    }


    /**
    * @Route("/", name="app_home")
    */
    public function findRand(ProductsRepository $repoProducts): Response
    {        
        $products = $repoProducts->findRand(5);
        
        return $this->render('home/index.html.twig', [
            'RandomProducts'     => $products,
        ]);
    }
}

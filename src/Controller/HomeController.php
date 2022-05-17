<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\ProductsType;
use App\Form\CookieAgeType;
use App\Form\CookieDataType;
use Doctrine\ORM\EntityManager;
use App\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(Request $request, ProductsRepository $repoProducts): Response
    {   
        // POUR SUPPRIMER TOUS LES COOKIES DU SITE :
        // $response = new Response();
        // $response->headers->clearCookie('age_legal', '/', null);   


        $formage = $this->createForm(CookieAgeType::class);
        $formage->handleRequest($request);

        if ($formage->isSubmitted() && $formage->isValid()) {

            $cookie_age = new Cookie('age_legal', //Nom cookie
            'majeur', //Valeur
            strtotime('tomorrow'), //expire le
            '/', //Chemin de serveur
            '', //Nom domaine
            false, //Https seulement
            true);

            $res1 = new Response();
            $res1->headers->setCookie( $cookie_age );
            $res1->send();

            return $this->redirectToRoute("app_home");
        }



        $formData = $this->createForm(CookieDataType::class);
        $formData->handleRequest($request);

        if ($formData->isSubmitted() && $formData->isValid()) {

            $cookieData = new Cookie('data', //Nom cookie
            'données', //Valeur
            time()+3600*24*31, //expire le
            '/', //Chemin de serveur
            '', //Nom domaine
            false, //Https seulement
            true);

            $res2 = new Response();
            $res2->headers->setCookie( $cookieData );
            $res2->send();

            return $this->redirectToRoute("app_home");
        }


        $products = $repoProducts->findRand(5, 1);
        
        return $this->render('home/index.html.twig', [
            'RandomProducts'     => $products,
            "formage" => $formage->createView(),
            "formData" => $formData->createView(),
        ],
        //  $response
        );
    }


}

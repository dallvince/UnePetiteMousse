<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FooterController extends AbstractController
{

    /**
     * @Route("/informations", name="app_infos")
     */
    public function footer_infos(): Response
    {
        return $this->render('footer/infos.html.twig', [
        ]);
    }

    /**
     * @Route("/mentions", name="app_mentions")
     */
    public function footer_mentions(): Response
    {
        return $this->render('footer/mentions.html.twig', [
        ]);
    }

    /**
     * @Route("/livraison", name="app_livraison")
     */
    public function footer_livraison(): Response
    {
        return $this->render('footer/livraison.html.twig', [
        ]);
    }

    /**
     * @Route("/conditions", name="app_conditions")
     */
    public function footer_conditions(): Response
    {
        return $this->render('footer/conditions.html.twig', [
        ]);
    }

     /**
     * @Route("/createurs", name="app_team")
     */
    public function footer_team(): Response
    {
        return $this->render('footer/team.html.twig', [
        ]);
    }
    
}

<?php

namespace App\Controller;

use App\Entity\Countries;
use App\Form\CountriesType;
use App\Repository\CountriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/countries")
 */
class AdminCountriesController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_countries_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $countries = $entityManager
            ->getRepository(Countries::class)
            ->findAll();

        return $this->render('admin_countries/index.html.twig', [
            'countries' => $countries,
        ]);
    }

    /**
     * @Route("/new", name="app_admin_countries_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $country = new Countries();
        $form = $this->createForm(CountriesType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($country);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_countries_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_countries/new.html.twig', [
            'country' => $country,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_countries_show", methods={"GET"})
     */
    public function show(Countries $country): Response
    {
        return $this->render('admin_countries/show.html.twig', [
            'country' => $country,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_admin_countries_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Countries $country, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CountriesType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_countries_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_countries/edit.html.twig', [
            'country' => $country,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_countries_delete", methods={"POST"})
     */
    public function delete(Request $request, Countries $country, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$country->getId(), $request->request->get('_token'))) {
            $entityManager->remove($country);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_countries_index', [], Response::HTTP_SEE_OTHER);
    }
}

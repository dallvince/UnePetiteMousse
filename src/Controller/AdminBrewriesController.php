<?php

namespace App\Controller;

use App\Entity\Brewries;
use App\Form\BrewriesType;
use App\Repository\BrewriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/brewries")
 */
class AdminBrewriesController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_brewries_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $brewries = $entityManager
            ->getRepository(Brewries::class)
            ->findAll();

        return $this->render('admin_brewries/index.html.twig', [
            'brewries' => $brewries,
        ]);
    }

    /**
     * @Route("/new", name="app_admin_brewries_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $brewry = new Brewries();
        $form = $this->createForm(BrewriesType::class, $brewry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($brewry);
            $entityManager->flush();

            $this->addFlash('success', 'La Brasserie a bien été ajoutée !');

            return $this->redirectToRoute('app_admin_brewries_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_brewries/new.html.twig', [
            'brewry' => $brewry,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_brewries_show", methods={"GET"})
     */
    public function show(Brewries $brewry): Response
    {
        return $this->render('admin_brewries/show.html.twig', [
            'brewry' => $brewry,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_admin_brewries_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Brewries $brewry, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BrewriesType::class, $brewry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'La Brasserie a bien été modifiée !');

            return $this->redirectToRoute('app_admin_brewries_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_brewries/edit.html.twig', [
            'brewry' => $brewry,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_brewries_delete", methods={"POST"})
     */
    public function delete(Request $request, Brewries $brewry, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$brewry->getId(), $request->request->get('_token'))) {
            $entityManager->remove($brewry);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_brewries_index', [], Response::HTTP_SEE_OTHER);
    }
}

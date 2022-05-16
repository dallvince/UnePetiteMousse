<?php

namespace App\Controller;

use App\Entity\Styles;
use App\Form\StylesType;
use App\Repository\StylesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/styles")
 */
class AdminStylesController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_styles_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $styles = $entityManager
            ->getRepository(Styles::class)
            ->findAll();

        return $this->render('admin_styles/index.html.twig', [
            'styles' => $styles,
        ]);
    }

    /**
     * @Route("/new", name="app_admin_styles_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $style = new Styles();
        $form = $this->createForm(StylesType::class, $style);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($style);
            $entityManager->flush();

            $this->addFlash('success', 'Le Style a bien été ajouté !');

            return $this->redirectToRoute('app_admin_styles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_styles/new.html.twig', [
            'style' => $style,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_styles_show", methods={"GET"})
     */
    public function show(Styles $style): Response
    {
        return $this->render('admin_styles/show.html.twig', [
            'style' => $style,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_admin_styles_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Styles $style, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StylesType::class, $style);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le Style a bien été modifié !');

            return $this->redirectToRoute('app_admin_styles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_styles/edit.html.twig', [
            'style' => $style,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_styles_delete", methods={"POST"})
     */
    public function delete(Request $request, Styles $style, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$style->getId(), $request->request->get('_token'))) {
            $entityManager->remove($style);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_styles_index', [], Response::HTTP_SEE_OTHER);
    }
}

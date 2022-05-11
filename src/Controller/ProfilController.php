<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\ProfilFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("/profil")
 */
class ProfilController extends AbstractController
{
    /**
     * @Route("/", name="app_profil")
     */
    public function profil(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager
            ->getRepository(Users::class)
            ->findAll();

		$user = $this->getUser();

        return $this->render('profil/index.html.twig', [
            'users' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit_profil", name="app_profil_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Users $users, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProfilFormType::class, $users, ["edit" => true]);
        $form->handleRequest($request);

        if($form->isSubmitted() AND $form->isValid()){

            $avatarFile = $form->get('avatarUpdate')->getData();

            if($avatarFile){

                $avatarName = date('YmdHis') . "-" . uniqid() . "." . $avatarFile->getClientOriginalExtension();
                $avatarFile->move($this->getParameter('avatarUpload'),  $avatarName);

                if($users->getAvatar()){ 
                    unlink($this->getParameter('avatarUpload') . "/" . $users->getAvatar());
                    
                }  
                
                
                    $users->setAvatar($avatarName);
                
            }

            $entityManager->persist($users);
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a bien été modifié !');
            
            return $this->redirectToRoute('app_profil', [], Response::HTTP_SEE_OTHER);
        }
            return $this->renderForm('profil/edit.html.twig', [
            'users' => $users,
            'formProfil' => $form,
        ]);
    }
}


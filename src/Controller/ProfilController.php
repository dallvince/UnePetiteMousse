<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Products;
use App\Form\ProfilFormType;
use App\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\ResetPasswordController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Contracts\Translation\TranslatorInterface;
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
     * @Route("{id}/modify_password", name="app_modify_password")
     */
    public function modify_password(Users $users, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, TranslatorInterface $translator, string $token = null): Response
    {
        
        // The token is valid; allow the user to change their password.
        $form_password = $this->createForm(ChangePasswordFormType::class);
        $form_password->handleRequest($request);


        if ($form_password->isSubmitted() && $form_password->isValid()) {
            // A password reset token should be used only once, remove it.

            // Encode(hash) the plain password, and set it.
            $encodedPassword = $userPasswordHasher->hashPassword(
                $users,
                $form_password->get('plainPassword')->getData()
            );

            $users->setPassword($encodedPassword);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('reset_password/reset.html.twig', [
            'resetForm' => $form_password->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit_profil", name="app_profil_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Users $users, EntityManagerInterface $entityManager): Response
      
    {
        $form = $this->createForm(ProfilFormType::class, $users, ["edit" => true]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

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

    /**
     * @Route("/{id}", name="app_profil_delete", methods={"POST"})
     */
    public function delete(Request $request, Users $users, EntityManagerInterface $entityManager): Response
    {
        // $currentUserId = $this->getUser();
        
        if ($this->isCsrfTokenValid('delete'.$users->getId(), $request->request->get('_token'))) {

            
            $session = new Session();
            $session->invalidate();

            $entityManager->remove($users);
            $entityManager->flush();

        }

        return $this->redirectToRoute('catalogue', [], Response::HTTP_SEE_OTHER);
    }

}


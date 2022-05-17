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
  
    public function edit(Request $request, Users $users, Products $product, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
      
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

            // $encodedPassword = $userPasswordHasher->hashPassword(
            //     $users,
            //     $form->get('plainPassword')->getData()
            // );

            // $users->setPassword($encodedPassword);
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

    
    
    //  /**
    //  * @Route("/{id}/edit_profil", name="app_profil_edit", methods={"GET", "POST"})
    //  */
    // public function passwordUpdate(Request $request, UserPasswordHasherInterface $userPasswordHasher,  ResetPasswordController $resetpassword, Users $users, EntityManagerInterface $entityManager){


    //     $formMDP = $this->createForm(ChangePasswordFormType::class);
    //     $formMDP->handleRequest($request);

    //     if ($formMDP->isSubmitted() && $formMDP->isValid()) {

    //         $encodedPassword = $userPasswordHasher->hashPassword(
    //             $users,
    //             $formMDP->get('plainPassword')->getData()
    //         );

    //         $users->setPassword($encodedPassword);
    //         $entityManager->persist($users);
    //         $entityManager->flush();
                

    //         return $this->redirectToRoute('app_profil');

    //     }

    //     return $this->renderForm('profil/edit.html.twig', [
    //         'formMDP' => $formMDP,
    //     ]);
    // }
}


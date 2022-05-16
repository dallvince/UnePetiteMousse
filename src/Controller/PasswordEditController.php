<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\PasswordEditType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


    /**
     * @Route("/password")
     */
class PasswordEditController extends AbstractController
{


    /**
     * @Route("/{id}/pw_edit", name="password_edit", methods={"GET", "POST"})
     */
    public function pwEdit(Request $request, Users $users, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = $this->createForm(PasswordEditType::class, $users, ["edit" => true]);
        $form->handleRequest($request);

        if($form->isSubmitted() AND $form->isValid()){

            $encodedPassword = $userPasswordHasher->hashPassword(
                $users,
                $form->get('plainPassword')->getData()
            );

            $users->setPassword($encodedPassword);
            $entityManager->persist($users);
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a bien été modifié !');
            
            return $this->redirectToRoute('app_profil', [], Response::HTTP_SEE_OTHER);
        }
            return $this->renderForm('profil/passwordEdit.html.twig', [
            'users' => $users,
            'formPasswordEdit' => $form,
        ]);
    }

}

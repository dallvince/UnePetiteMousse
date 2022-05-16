<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class PasswordEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder      
        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options' => [
                'attr' => [
                'autocomplete' => 'new-password',
                "placeholder" => "Nouveau mot de passe",
                                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un nouveau mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit être composé de {{ limit }} caractères au minimum',
                        // max length allowed by Symfony for security reasons
                        'max' => 200,
                    ]),
                ],
                'label' => 'Nouveau Mot de Passe',
            ],
            'second_options' => [
                'attr' => [
                    'autocomplete' => 'new-password',
                "placeholder" => "Confirmer le mot de passe"
                ],
                'label' => 'Confirmer Mot de Passe',
            ],
            'invalid_message' => 'Les deux saisies de mot de passe doivent être identiques',
            // Instead of being set onto the object directly,
            // this is read and encoded in the controller
            'mapped' => false,
            'required' => false 
        ]);

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
            "edit" => false,
        ]);
    }
}

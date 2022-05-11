<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProfilFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                "required" => false,
            ])

            ->add('email', TextType::class, [
                "required" => false,
            ])

            ->add('zip', TextType::class, [
                "required" => false,
            ])

            ->add('country', TextType::class, [
                "required" => false,
            ])

            ->add('adress', TextType::class, [
                "required" => false,
            ])

            ->add('info', TextareaType::class, [
                "required" => false,
                
            ]);
            
            if($options['ajouter'])
            {
                $builder->add('avatar', FileType::class, [
                    "required" => false,
                    "mapped" => false,
                    "attr" => [
                        "onchange" => "loadFile(event)"
                    ]
                ]);
            }
            elseif($options['edit'])
            {
                $builder->add('avatarUpdate', FileType::class, [
                    "required" => false,
                    "mapped" => false,
                    "attr" => [
                        "onchange" => "loadFile(event)"
                    ]
                ]);
            }


            // ->add('password', PasswordType::class, [    
            //     'mapped' => false,
            //     'attr' => ['autocomplete' => 'new-password'],
            //     'constraints' => [
            //         new NotBlank([
            //             'message' => 'Veuillez renseigner votre mot de passe',
            //         ]),
            //         new Length([
            //             'min' => 6,
            //             'minMessage' => 'Your password should be at least {{ limit }} characters',
            //             'max' => 4096,
            //         ]),
            //     ],
            // ])

            

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
            "edit" => false,
            'ajouter' => false,
        ]);
    }
}

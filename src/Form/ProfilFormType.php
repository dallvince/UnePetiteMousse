<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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
            // ->add('pseudo', TextType::class, [
            //     "required" => false,
            //     'attr' => [
            //         'readonly' => true
            //     ]
            // ])

            // ->add('email', EmailType::class, [
            //     "required" => false,
            // ])

            ->add('zip', TextType::class, [
                "label" => "Code Postal" ,
                "required" => false,
            ])

            ->add('country', TextType::class, [
                "label" => "Pays" ,
                "required" => false,
            ])

            ->add('adress', TextType::class, [
                "label" => "Adresse",
                "required" => false,
            ])

            ->add('info', TextareaType::class, [
                "label" => "Informations",
                "required" => false,
                
            ]);

            
            
            if($options['ajouter'])
            {
                $builder->add('avatar', FileType::class, [
                    "required" => false,
                    "mapped" => false,
                    "attr" => [
                        "onchange" => "loadFile(event)"
                    ],
                    'invalid_message' => 'L\avatar n\'a pas le bon format ou la bonne taille',
                ]);
            }
            elseif($options['edit'])
            {
                $builder->add('avatarUpdate', FileType::class, [
                    "required" => false,
                    "mapped" => false,
                    "attr" => [
                        "onchange" => "loadFile(event)"
                    ],
                    'invalid_message' => 'L\avatar n\'a pas le bon format ou la bonne taille',
                ]);
            }
            

            
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

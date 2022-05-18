<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Entity\Countries;

class ProfilFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('zip', TextType::class, [
                "label" => "Code Postal" ,
                "required" => false,
            ])
            ->add('countries', EntityType::class, [
                "required" => false,
                "class" => Countries::class,
                "mapped" => false,
                "placeholder" => "Choisir un pays",
                "choice_label" => function ($countries) 
                {
                    return $countries->getFlag() . " " . $countries->getName();
                }
            ])
            ->add('adress', TextType::class, [
                "label" => "Adresse",
                "required" => false,
            ])


            ->add('modifier', SubmitType::class, [
                "attr" => [
                    "value" => "Modifier profil",
                    "class" => "btn btn-warning col-md-2 mt-3 mx-auto"
                ],
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

<?php

namespace App\Form;

use App\Entity\Styles;
use App\Entity\Brewries;
use App\Entity\Countries;
use App\Entity\Products;
use App\Repository\StylesRepository;
use App\Repository\BrewriesRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProductsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                "label" => "Nom du produit",
                "required" => false,
                "attr" => [
                    "class" => "form_input",
                    "placeholder" => "Saisir le Nom du Produit"
                ],
            ])
            ->add('description', TextareaType::class, [
                "label" => "Description",
                "required" => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir une Description',
                    ]),
                    new Length([
                        'min' => 15,
                        'minMessage' => 'Votre description doit contenir {{ limit }} caractères au minimum',
                        'max' => 400,
                    ]),
                ],
                "attr" => [
                    "placeholder" => "Saisir une description"
                ]
            ])
            ->add('abv', TextType::class, [
                "label" => "Titre Alcoolique",                 
                "attr" => ["placeholder" => "Saisir le degré d'alcool"],
                "required" => false
            ])
            ->add('ebc', TextType::class, [
                "label" => "Degré d'amertume",                 
                "attr" => ["placeholder" => "Saisir le degré d'amertume"],
                "required" => false
            ])

            ->add('styles', EntityType::class, [
                "class" => Styles::class,
                "choice_label" => "name",
                "placeholder" => "Choisir un style",
                'query_builder' => function (StylesRepository $sr) {
                    return $sr->createQueryBuilder('p')
                    ->orderBy('p.name', 'ASC');
                } 
            ])
            ->add('brewries', EntityType::class, [
                "class" => Brewries::class,
                "choice_label" => "name",
                "placeholder" => "Choisir une Brasserie",
                'query_builder' => function (BrewriesRepository $br) {
                    return $br->createQueryBuilder('p')
                    ->orderBy('p.name', 'ASC'); 
                }
            ])
            ->add('countries', EntityType::class, [
                "class" => Countries::class,
                "mapped" => false,
                "choice_label" => function ($countries) 
                {
                    return $countries->getFlag() . " " . $countries->getName();
                },
                "required" => false
            ])

            ->add('glutenfree', ChoiceType::class, [
                "label" => "Sans Gluten",
                'choices'  => [
                    'Oui' => 'oui',
                    'Non' => 'non'
                ]
            ])
            ->add('organic', ChoiceType::class, [
                "label" => "Bio",
                'choices'  => [
                    'Oui' => 'oui',
                    'Non' => 'non'
                ]
            ])
            ->add('price', MoneyType::class, [
                "label" => "Prix",
                "required" => false,
                "attr" => [
                    "class" => "form_input",
                    "placeholder" => "Prix"
                ]
            ])

            // ->add("Ajouter", SubmitType::class)
        ;
        if ($options['add']) {
            $builder->add('picture', FileType::class, [
                "required" => false,
                "attr" => [
                    "onchange" => "loadFile(event)"
                ]
            ]);
        } elseif ($options['edit']) {
            $builder->add('pictureUpdate', FileType::class, [
                "required" => false,
                "mapped" => false,
                "attr" => [
                    "onchange" => "loadFile(event)"
                ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
            "add" => false,
            "edit" => false
        ]);
    }
}

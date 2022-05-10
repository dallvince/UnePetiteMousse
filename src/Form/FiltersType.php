<?php

namespace App\Form;

use App\Entity\Styles;
use App\Entity\Brewries;
use App\Entity\Countries;
use App\filters\ProductsFilters;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FiltersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('search', TextType::class, [
                "attr" => [
                    "placeholder" => "Mots-clés"
                    ],
                "required" => false
            ])
            ->add('minPrice', TextType::class, [
                "attr" => [
                    "placeholder" => "Prix Minimum"
                    ],
                "required" => false
            ])
            ->add('maxPrice', TextType::class, [
                "attr" => [
                "placeholder" => "Prix Maximum"
                ],
                "required" => false
            ])
            ->add('style', EntityType::class, [
                "placeholder" => "Filtrer par Styles",
                "class" => Styles::class,
                "choice_label" => "name",
                "required" => false
            ])
            ->add('country', EntityType::class, [
                "placeholder" => "Filtrer par Pays",
                "class" => Countries::class,
                "choice_label" => "name",
                "required" => false,
                // "multiple" => true,
            ])
            ->add('brewry', EntityType::class, [
                "class" => Brewries::class,
                "placeholder" => "Trier par Brasserie",
                "choice_label" => "name",
                "required" => false,
            ])
            ->add('order', ChoiceType::class, [
                "required" => false,
                "placeholder" => "Filtrer par :",
                "choices" => [
                    "Prix croissant" => 1,
                    "Prix décroissant" => 2,
                    "Les plus récents" => 3,
                    "Nom croissant" => 4,
                    "Nom décroissant" => 5
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "data_class" => ProductsFilters::class
        ]);
    }
}

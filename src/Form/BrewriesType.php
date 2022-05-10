<?php

namespace App\Form;

use App\Entity\Brewries;
use App\Entity\Countries;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class BrewriesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('countries', EntityType::class, [
                "class" => Countries::class,
                "choice_label" => function ($countries) 
                {
                    return $countries->getFlag() . " " . $countries->getName();
                },
                "required" => false,
                "placeholder" => "Choisissez un pays"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Brewries::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Styles;
use App\Entity\Brewries;
use App\Entity\Countries;
use App\filters\ProductsFilters;
use App\Repository\BrewriesRepository;
use App\Repository\CountriesRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FiltersType extends AbstractType
{
    private $brasseries;
    private $repoBrewries;
    public function __construct(BrewriesRepository $repo) 
    {
        $this->repoBrewries = $repo;
        $this->brasseries = $repo->findAll();
    }

    public function pays()
    {
        // $brewries = $this->repoBrewries->findAll();
        $array = [];
        foreach($this->brasseries as $object)
        {
            $array[$object->getCountries()->getName()] = $object->getCountries();
        }
        $array = array_unique($array);
        asort($array);
        // dd($array);
        return $array;
    }


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
                "choices" => $this->pays()
                // "query_builder" => function (CountriesRepository $cr)
                // {
                //     return $cr->createQueryBuilder('b')
                //     ->leftJoin('b.countries', 'c')
                //     ->andWhere()
                //     ->orderBy('c.name', "ASC");
                // }
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
            // ->add('test', ChoiceType::class, [
            //     "mapped" => false,
            //     "choices" => $this->pays() 
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "data_class" => ProductsFilters::class
        ]);
    }
}

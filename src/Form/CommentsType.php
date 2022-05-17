<?php

namespace App\Form;

use App\Entity\Comments;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('message', TextareaType::class, [
                'label' => 'Laissez votre avis :',
                'attr' => [
                    'rows' => 5,
                ],
                'required' => false
            ])
            ->add('rating', ChoiceType::class, [
                'label' => 'Evaluez le produit',
                'choices' => [
                    '0.5' => 0.5,
                    '1' => 1,
                    '1.5' => 1.5,
                    '2' => 2,
                    '2.5' => 2.5,
                    '3' => 3,
                    '3.5' => 3.5,
                    '4' => 4,
                    '4.5' => 4.5,
                    '5' => 5,
                ],
                'placeholder' => 'Votre note :'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comments::class,
        ]);
    }
}

<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                "label" => "Nom*",
                "required" => false
            ])
            ->add('firstname', TextType::class, [
                "label" => "Prénom*",
                "required" => false
            ])
            ->add('email', EmailType::class, [
                "label" => "email*",
                "required" => false
            ])
            ->add('message', TextareaType::class, [
                "label" => "Message*",
                "required" => false
            ])
            ->add('captcha', CaptchaType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse email *',
                'required' => true,
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom *',
                'required' => true,
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom *',
                'required' => true,
            ])
            ->add('plainPassword', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'Les 2 mots de passe doivent être identiques.',
                'first_options' => [
                    'label' => 'Mot de passe *',
                    'attr' => [
                        'data-toggle' => 'password',
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe *',
                    'attr' => [
                        'data-toggle' => 'password',
                    ],
                ],
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Pdf;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PdfType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('url', TextType::class, [
                'label' => 'URL',
                'required' => false,
            ])
            ->add('file', FileType::class, [
                'label' => 'Fichier',
                'mapped' => false,
                'required' => false,
                'attr' => ['accept' => '.html'],
            ])
            ->add('html', TextType::class, [
                'label' => 'HTML',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}

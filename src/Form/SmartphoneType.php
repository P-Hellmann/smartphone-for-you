<?php

namespace App\Form;

use App\Entity\Smartphone;
use App\Entity\Vendor;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SmartphoneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('vendor', EntityType::class, [
                'class' => Vendor::class,
                'choice_label' => 'name',
            ])
            ->add('type')
            ->add('memory')
            ->add('color', ChoiceType::class, [
                'choices' => [
                    'Black' => 'black',
                    'White' => 'white',
                    'Red' => 'red',
                    'Green' => 'green',
                    'Blue' => 'blue',
                    'Yellow' => 'yellow',
                    'Orange' => 'orange',
                ]
            ])
            ->add('price')
            ->add('description')
            ->add('picture', FileType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Submit',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Smartphone::class,
        ]);
    }
}

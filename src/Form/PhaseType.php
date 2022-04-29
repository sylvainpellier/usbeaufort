<?php

namespace App\Form;

use App\Entity\Phase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Name')
            ->add('TempsMatch')
            ->add('TempsEntreMatch')
            ->add('param')
            ->add('ordre')
            ->add('Type')
            ->add('PhasePrecedente')
            ->add('PhaseSuivante')
            ->add('category')
            ->add("btn",SubmitType::class, ["label"=>"Modifier","attr"=>["class"=>"btn btn-primary d-block w-100"]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Phase::class,
        ]);
    }
}

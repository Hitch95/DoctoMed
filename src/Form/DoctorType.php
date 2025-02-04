<?php

namespace App\Form;

use App\Entity\Doctor;
use App\Entity\HealthcareCenter;
use App\Entity\Skill;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DoctorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('photo', FileType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('skills', EntityType::class, [
                'class' => Skill::class,
                'choice_label' => 'name',
                'multiple' => true,
            ]);

        if ($options['is_admin']) {
            $builder->add('healthcareCenter', EntityType::class, [
                'class' => HealthcareCenter::class,
                'choice_label' => 'name',
            ]);
        };
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Doctor::class,
            'is_admin' => false
        ]);
    }
}
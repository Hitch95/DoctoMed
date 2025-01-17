<?php

namespace App\Form;

use App\Entity\Appointment;
use App\Entity\Skill;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $healthcareCenter = $options['healthcare_center'];

        $builder
            ->add('firstName', null, [
                'label' => 'Prénom',
            ])
            ->add('lastName', null, [
                'label' => 'Nom',
            ])
            ->add('email', EmailType::class)
            ->add('phone', TelType::class, [
                'label' => 'Téléphone',
            ])
            ->add('startAt', null, [
                'label' => 'Date et heure',
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d\TH:i'),
                ],
            ])
            ->add('skill', EntityType::class, [
                'class' => Skill::class,
                'query_builder' => function (EntityRepository $er) use ($healthcareCenter): QueryBuilder {
                    return $er->createQueryBuilder('s')
                        ->join('s.doctors', 'd')
                        ->where('d.healthcareCenter = :healthcareCenter')
                        ->orderBy('s.name', 'ASC')
                        ->setParameter('healthcareCenter', $healthcareCenter);
                },
                'choice_label' => 'name',
                'label' => 'Spécialité',
            ])
            ->add('message')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
            'healthcare_center' => null,
        ]);
    }
}
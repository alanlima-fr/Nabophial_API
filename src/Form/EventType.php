<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('lieu')
            ->add('beginTime', DateType::class,['widget' => 'single_text','format' => 'yyyy-MM-dd HH:mm:ss'])
            ->add('endDate', DateType::class,['widget' => 'single_text','format' => 'yyyy-MM-dd HH:mm:ss'])
            ->add('horaire')
            ->add('nbrMax')
            ->add('description')
            ->add('privateEvent')
            ->add('status')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
            'csrf_protection' => false,
        ]);
    }
}

<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class OficinaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('nombre', null, array(
            'label' => 'Nombre(*)',
            'attr' => array(
            'placeholder' => 'Ingrese un nombre para la oficina'
            )
        ))
        ->add('responsableOficina', null, array(
            'label' => 'Responsable de Oficina',
            'attr' => array(
            'placeholder' => 'Ingrese el responsable de oficina'
            )
        ))

        ->add('numeroCarpeta',IntegerType::class, array(
            'label' => 'Número de carpeta(*)',
            'attr' => array(
            'min' => 1,
            'placeholder' => 'Ingrese un número de carpeta para la oficina'
            ))
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Oficina',
            'edit' => true,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_oficina';
    }


}

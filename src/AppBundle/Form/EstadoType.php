<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ColorType;

class EstadoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['visibility']) {
          $builder
            ->add('nombre', null, array(
                'label' => 'Nombre(*)',
                'required' => false,
                'attr' => array(
                'placeholder' => 'Ingrese un nombre para el estado'
                )
            ))
            ->add('color', ColorType::class, array(
                'label' => 'Color(*)',
                'attr' => array(
                'placeholder' => 'Ingrese un color para el estado'
                )
            ))
            ->add('descripcion', null, array(
                'label' => 'Descripción',
                'attr' => array(
                  'placeholder' => 'Ingrese una descripción para el estado'
                )
          ));
        };
        if ($options['edit']) {
          $builder->add('habilitado');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Estado',
            'edit' => true,
            'visibility' => true,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_estado';
    }


}

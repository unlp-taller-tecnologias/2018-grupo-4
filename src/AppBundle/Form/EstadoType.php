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
        $builder
          ->add('nombre', null, array(
              'label' => 'Nombre del Estado*',
              'attr' => array(
              'placeholder' => 'Ingrese un nombre para el estado'
              )
          ))
          ->add('color', ColorType::class, array(
              'label' => 'Color para el Estado*',
              'attr' => array(
              'placeholder' => 'Ingrese un color para el estado'
              )
          ))
          ->add('descripcion', null, array(
              'label' => 'Descripción del Estado',
              'attr' => array(
                'placeholder' => 'Ingrese una descripción para el estado'
              )
        ));
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
            'edit' => false
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

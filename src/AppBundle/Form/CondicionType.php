<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CondicionType extends AbstractType
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
              'placeholder' => 'Ingrese una nombre para la condición'
            ))
      )
        ->add('descripcion', null, array(
            'label' => 'Descripción(*)',
            'attr' => array(
              'placeholder' => 'Ingrese una descripción para la condición'
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
            'data_class' => 'AppBundle\Entity\Condicion',
            'edit' => true
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_condicion';
    }


}

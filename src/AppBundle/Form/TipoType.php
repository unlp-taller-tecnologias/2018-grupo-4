<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TipoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['visibility']) {
          $builder
          ->add('codigo', null, array(
              'label' => 'Código(*)'
          ))
          ->add('concepto', null, array(
              'label' => 'Concepto(*)'
          ))
          ->add('nomenclador')
          ->add('cuenta', null, array(
              'label' => 'Cuenta',
              'attr' => array(
              'placeholder' => 'Ingrese una cuenta'
              )
          ))
          ->add('grupo', null, array(
              'label' => 'Grupo',
              'attr' => array(
              'placeholder' => 'Ingrese un grupo'
              )
          ))
          ->add('subgrupo', null, array(
              'label' => 'Subgrupo',
              'attr' => array(
              'placeholder' => 'Ingrese un subgrupo'
              )
          ))
          ->add('descripcion', null, array(
              'label' => 'Descripción',
              'attr' => array(
              'placeholder' => 'Ingrese una descripción'
              )
          ))
          ->add('vidaUtil', null, array(
              'label' => 'Vida útil'
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
            'data_class' => 'AppBundle\Entity\Tipo',
            'edit' => true,
            'visibility' => true,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_tipo';
    }


}

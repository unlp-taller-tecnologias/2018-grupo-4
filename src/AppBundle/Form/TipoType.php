<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TipoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('concepto', null, array(
            'label' => 'Concepto(*)',
            'required' => true,
            'attr' => array(
            'placeholder' => 'Ingrese un nombre para el código'
            )
        ))
        ->add('codigo', null, array(
            'label' => 'Código(*)',
            'required' => true,
            'attr' => array(
            'placeholder' => 'Ingrese un número para el código'
            )
        ))
        ->add('descripcion', null, array(
            'label' => 'Descripción(*)',
            'required' => true,
            'attr' => array(
            'placeholder' => 'Ingrese un número para el código'
            )
        ))
        ->add('nomenclador', null, array(
            'label' => 'Nomenclador(*)'
          ))
        ->add('cuenta', null, array(
            'label' => 'Cuenta(*)',
            'attr' => array(
            'placeholder' => 'Ingrese un número para el código'
            )
        ))
        ->add('grupo', null, array(
            'attr' => array(
            'placeholder' => 'Ingrese un grupo para el código'
            )
        ))
        ->add('subgrupo', null, array(
            'attr' => array(
            'placeholder' => 'Ingrese un subgrupo para el código'
            )
        ))
        ->add('vidaUtil', null, array(
            'label' => 'Vida útil',
            'attr' => array(
            'placeholder' => 'Ingrese un númer de vida útil'
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
            'data_class' => 'AppBundle\Entity\Tipo',
            'edit' => true
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

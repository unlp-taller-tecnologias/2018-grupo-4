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
        if ($options['visibility']) {
          $builder
          ->add('codigo', null, array(
              'label' => 'Código(*)'
          ))
          ->add('concepto', null, array(
              'label' => 'Concepto(*)',
              'attr' => array(
              'placeholder' => 'Ingrese un nombre para el tipo de artículo'
              )
          ))
          ->add('nomenclador', ChoiceType::class, array(
            'label' => 'Nomenclador(*)',
            'choices' => array(
                'Diaguita' => 'diaguita',
                '2017'  => '2017'
            ) ) )
          ->add('cuenta', ChoiceType::class, array(
            'label' => 'Cuenta(*)',
            'choices' => array(
                '400' => '400',
                '4.3.1'  => '4.3.1'
            ) ) )
          ->add('grupo', null, array(
              'label' => 'Grupo',
              'attr' => array(
              'placeholder' => 'Ingrese un grupo solo si el nomenclador es 2017'
              )
          ))
          ->add('subgrupo', null, array(
              'label' => 'Subgrupo',
              'attr' => array(
              'placeholder' => 'Ingrese un subgrupo solo si el nomenclador es 2017'
              )
          ))
          ->add('descripcion', null, array(
              'label' => 'Descripción',
              'attr' => array(
              'placeholder' => 'Ingrese una descripción si el nomenclador es 2017'
              )
          ))
          ->add('vidaUtil', null, array(
              'label' => 'Vida útil (*) Ingrese vida útil solo si el nomenclador es Diaguita'
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

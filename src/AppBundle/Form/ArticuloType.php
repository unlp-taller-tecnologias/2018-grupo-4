<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ArticuloType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('numInventario', null, array(
              'label' => 'Número de inventario*',
              'attr' => array(
              'placeholder' => 'Ingrese el número de inventario'
              )
          ))
          ->add('denominacion', null, array(
            'label' => 'Denominación*',
            'attr' => array('placeholder' => 'Ingrese denominación'
             )
          ))
          ->add('oficina', null, array(
            'label' => 'Oficina*',
            'attr' => array('placeholder' => 'Seleccione una oficina'
            )
          ))
          ->add('tipo', null, array(
            'label' => 'Tipo',
            'attr' => array('placeholder' => 'Seleccione un tipo'
            )
          ))
          // ->add('estado', null, array(
          //     'label'=> 'Estado'
          // ))
          ->add('material', null, array(
            'label' => 'Material',
            'attr' => array('placeholder' => 'Ingrese el material del artículo'
            )
          ))
          ->add('marca', null, array(
            'label' => 'Marca',
            'attr' => array('placeholder' => 'Ingrese la marca del artículo'
            )
          ))
          ->add('numFabrica',null, array(
            'label' => 'Número de Fábrica',
            'attr' => array('placeholder' => 'Ingrese el número de fábrica'
            )
          ))
          ->add('largo', null, array(
            'label' => 'Largo',
            'attr' => array('placeholder' => 'Ingrese el largo del artículo'
            )
          ))
          ->add('ancho', null, array(
            'label' => 'Ancho',
            'attr' => array('placeholder' => 'Ingrese el ancho del artículo'
            )
          ))
          ->add('alto', null, array(
            'label' => 'Alto',
            'attr' => array('placeholder' => 'Ingrese el alto del artículo'
            )
          ))
          ->add('numsEstantes', null, array(
            'label' => 'Número de estantes',
            'attr' => array('placeholder' => 'Ingrese la cantidad de estantes del artículo'
            )
          ))
          ->add('numsCajones', null, array(
            'label' => 'Número de cajones',
            'attr' => array('placeholder' => 'Ingrese la cantidad de cajones del artículo'
            )
          ))
          ->add('detalleOrigen', null, array(
            'label' => 'Detalle del origen',
            'attr' => array('placeholder' => 'Ingrese el detalle del origen del artículo'
            )
          ))
          ->add('importe', null, array(
            'label' => 'Importe',
            'attr' => array('placeholder' => 'Ingrese el importe del artículo'
            )
          ))
          ->add('fechaEntrada', null, array(
            'label' => 'Fecha de entrada',
            'widget' => 'single_text'
          ))
          ->add('codigoCuentaSubcuenta', null, array(
            'label' => 'Codigo de Cuenta/Subcuenta',
            'attr' => array('placeholder' => 'Ingrese el código de cuenta/subcuenta'
            )
          ))
          ->add('numExpediente', null, array(
            'label' => 'Número de Expediente',
            'attr' => array('placeholder' => 'Ingrese el número de expediente'
          )
          ))
          ->add('observaciones', null, array(
            'label' => 'Observaciones',
            'attr' => array('placeholder' => 'Ingrese observaciones sobre el artículo si asi lo require'
            )
          ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Articulo'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_articulo';
    }


}

<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use \DateTime;

class ArticuloType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('numInventario', null, array(
              'label' => 'Número de inventario(*)',
              'required' => true,
              'attr' => array(
              'placeholder' => 'Ingrese el número de inventario'
              )
          ))
          ->add('denominacion', null, array(
            'label' => 'Denominación(*)',
            'required' => true,
            'attr' => array('placeholder' => 'Ingrese denominación'
             )
          ))

          ->add('estadoAdicional', null, array(
               'label'=> 'Estado Adicional'
          ))

          ->add('oficina', null, array(
            'label' => 'Oficina(*)',
            'attr' => array('placeholder' => 'Seleccione una oficina'
            )
          ))
          ->add('fechaEntrada', DateType::class, array(
            'label' => 'Fecha de entrada',
            'widget' => 'single_text',
            'required' => true
          ))


          ->add('tipo', null, array(
               'label'=> 'Tipo'
          ))

          ->add('condicion', null, array(
               'label'=> 'Condición'
          ))



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
          ->add('moneda', ChoiceType::class, array(
                'choices' => array(
                    'Peso (ARS)' => 'peso',
                    'Dólar (USD)'  => 'dolar'
                ) ) )
          // ->add('moneda', null, array(
          //   'label' => 'Moneda'
          // ))
          ->add('importe', null, array(
            'label' => 'Importe',
            'attr' => array('placeholder' => 'Ingrese el importe del artículo'
            )
          ))
          ->add('codigoCuentaSubcuenta', null, array(
            'label' => 'Código de Cuenta/Subcuenta',
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

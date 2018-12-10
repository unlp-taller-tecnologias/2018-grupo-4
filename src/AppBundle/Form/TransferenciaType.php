<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ArticuloType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use \DateTime;


class TransferenciaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('fecha', DateType::class, array(
                  'label' => 'Fecha*',
                  'widget' => 'single_text',
                  'required' => true
                ))

                ->add('oficina_destino', null, array(
                  'label' => 'Oficina destino*',
                  'required' => true
                ))
                ->add('observaciones', TextareaType::class, array(
                  'attr' => array('class' => 'tinymce'),
                  'required' => false
                ));
                // ->add('finalizada', null, array(
                //   'data'=>'0'
                // ))
                //->add('oficina', CollectionType::class, array(
                  //  'entry_type' => ArticuloType::class,
                    //'entry_options' => array('label' => false),
                //))

    }



    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Transferencia'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_transferencia';
    }


}

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
use Symfony\Component\Form\Extension\Core\Type\CollectionType;



class TransferenciaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('observaciones', TextareaType::class, array(
                  'attr' => array('class' => 'tinymce'),
                ))
                ->add('finalizada', null, array(
                  'data'=>'0'
                ))
                ->add('fecha', null, array(
                  'format' => 'yyyy-MM-dd',
                ))
                //->add('oficina', CollectionType::class, array(
                  //  'entry_type' => ArticuloType::class,
                    //'entry_options' => array('label' => false),
                //))


                //->add('observaciones')
                //->add('finalizada')
                ->add('oficina_destino');

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

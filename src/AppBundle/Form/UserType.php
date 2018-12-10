<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('username', null, array(
          'label' => 'Nombre de usuario(*)',
          'required' => 'true'
        ))
        ->add('name', null, array(
          'label' => 'Nombre(*)',
          'required' => 'true'
        ))
        ->add('lastname', null, array (
          'label' => 'Apellido(*)',
          'required' => 'true'
        ))
        // ->add('email', null, array(
        //   'label' => 'E-mail',
        //   'required' => 'false'
        // ))
        ->add('password', null, array(
          'label' => 'Contraseña',
          'required' => 'true'
        ))
        ->add('roles', ChoiceType::class, array(
              'multiple'=> true,
              'choices' => array(
                  'ROLE_SUPER_ADMIN' => 'ROLE_SUPER_ADMIN',
                  'ROLE_ADMIN'  => 'ROLE_ADMIN',
                  'ROLE_USER'  => 'ROLE_USER'
              ) ) );
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }


}

<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      if ($options['visibility']) {
        $builder
        ->add('username', null, array(
          'label' => 'Nombre de usuario(*)',
          'required' => true
        ))
        ->add('name', null, array(
          'label' => 'Nombre(*)',
          'required' => true
        ))
        ->add('lastname', null, array (
          'label' => 'Apellido(*)',
          'required' => true
        ))
        ->add('plainPassword', PasswordType::class, array(
          'label' => 'Contraseña(*)',
          'required' => false)
        );
        if ($options['edit_role']) {
        $builder->add('roles', ChoiceType::class, array(
              'label' => 'Roles(*)',
              'multiple'=> true,
              'choices' => array(
                  'ROLE_SUPER_ADMIN' => 'ROLE_SUPER_ADMIN',
                  'ROLE_ADMIN'  => 'ROLE_ADMIN',
                  'ROLE_USER'  => 'ROLE_USER'
              ) ) );
        }
      };
      if ($options['edit']) {
        $builder->add('enabled', null, array(
          'label' => 'Habilitado',
        ));
      }

    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'edit_role' => false,
            'edit' => true,
            'visibility' => true,
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

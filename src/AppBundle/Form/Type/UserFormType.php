<?PHP
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder
          ->add('name', null, array(
          'label' => 'Nombre*',
          'required' => 'true'
          ))
          ->add('lastname', null, array(
          'label' => 'Apellido*',
          'required' => 'true'
          ))
          ->add('roles', ChoiceType::class, array(
                'multiple'=> true,
                'choices' => array(
                    'ROLE_SUPER_ADMIN' => 'ROLE_SUPER_ADMIN',
                    'ROLE_ADMIN'  => 'ROLE_ADMIN',
                    'ROLE_USER'  => 'ROLE_USER'
                ),
          ));
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';

        // Or for Symfony < 2.8
        // return 'fos_user_registration';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}

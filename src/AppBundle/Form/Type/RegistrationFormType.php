<?PHP
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder
          ->add('name', null, array( ))
          ->add('lastname', null, array( ))
          ->add('roles', ChoiceType::class, array(
                'multiple'=> true,
                'choices' => array(
                    'ROLE_SUPER_ADMIN' => 'Administración',
                    'ROLE_ADMIN'  => 'Edición y carga',
                    'ROLE_USER'  => 'Solo lectura'
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

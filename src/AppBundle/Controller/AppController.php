<?php

namespace AppBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use FOS\UserBundle\Model\User as BaseUser;
use AppBundle\Entity\Oficina;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AppController extends Controller {
    /**
     * @Route("/agregar_oficina", name="agregar_oficina")
     */
  /*  public function indexAction(Request $request){
      $oficina = new Oficina();
      $oficina->setNombre('');
      $oficina->setResponsable('');
      $form = $this->createFormBuilder($oficina)
            ->add('nombre', TextType::class)
            ->add('responsable', TextType::class)
            ->add('dadfadfa', SubmitType::class, array('label' => 'Crear oficina'))
            ->getForm();

        return $this->render('default/agregar_oficina.html', array(
            'form' => $form->createView(),
        ));

    }*/
}

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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class OficinaController extends Controller {
    /**
     * @Route("/agregar_oficina", name="agregar_oficina")
     ** @Security("has_role('ROLE_ADMINISTRADOR')")
     */
    public function indexAction(Request $request){
      $oficina = new Oficina();
      $nombre = $oficina->setNombre('');
      $carpeta = $oficina->setNumCarpeta('');
      $responsable = $oficina->setResponsable('');
      $form = $this->createFormBuilder($oficina)
            ->add('nombre', TextType::class)
            ->add('num_carpeta', TextType::class)
            ->add('responsable', TextType::class)
            ->add('dadfadfa', SubmitType::class, array('label' => 'Crear oficina'))
            ->getForm();
      $form->handleRequest($request);
      if($form->isSubmitted()){
        $this->crearOficina($nombre, $carpeta, $responsable, $oficina, $request);
      }

        return $this->render('default/agregar_oficina.html', array(
            'form' => $form->createView(),
        ));
    }

    public function crearOficina($nombre, $carpeta, $responsable, $oficina, Request $request){
        $em = $this->getDoctrine()->getManager();
        $em->persist($oficina);
        $em->flush();
    }


    /** Lista todas las oficinas
     * @Route("/ver_oficinas", name="ver_oficinas")
     * @Method({"GET","POST"})
     * @Security("has_role('ROLE_USUARIO')")
     */
    public function verOficinas(){
          $em = $this->getDoctrine()->getManager();
          $oficinas = $em->getRepository('AppBundle:Oficina')->findAll();
          return $this->render('default/ver_oficinas.html', array(
                      'oficina' => $oficinas,
          ));

    }




}

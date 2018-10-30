<?php

namespace AppBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use FOS\UserBundle\Model\User as BaseUser;
use AppBundle\Entity\Articulo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Session\Session;

class ArticuloController extends Controller {
    /**
     * @Route("/agregar_articulo", name="agregar_articulo")
     ** @Security("has_role('ROLE_ADMINISTRADOR')")
     */
    public function newAction(Request $request){
      $articulo = new Articulo();
      $form = $this->createFormBuilder($articulo)
            ->add('numInventario', TextType::class, array('label' => 'Numero de inventario(*):'))
            ->add('denominacion', TextType::class, array('label' => 'Denominacion (*):'))
            ->add('idEstado', TextType::class)
            ->add('material', TextType::class)
            ->add('marca', TextType::class)
            ->add('numFabrica', TextType::class)
            ->add('largo', TextType::class)
            ->add('ancho', TextType::class)
            ->add('alto', TextType::class)
            ->add('numEstantes', TextType::class)
            ->add('numCajones', TextType::class)
            ->add('detalleOrigen', TextType::class)
            ->add('importe', TextType::class)
            ->add('codCuenta', TextType::class)
            ->add('numExpediente', TextType::class)
            ->add('observaciones', TextType::class)
            ->add('idCondicion', TextType::class)
            ->add('idTipo', TextType::class)
            ->add('submit', SubmitType::class, array('label' => 'Crear articulo'))
            ->getForm();
      $form->handleRequest($request);
      if($form->isSubmitted()){
        $this->crearArticulo($articulo, $request);
      }

        return $this->render('default/agregar_articulo.html', array(
            'form' => $form->createView(),
        ));
    }

    public function crearArticulo($articulo, Request $request){
        $em = $this->getDoctrine()->getManager();
        $em->persist($articulo);
        $em->flush();
    }


    /** Lista todos los articulos
     * @Route("/ver_articulos", name="ver_articulos")
     * @Method({"GET","POST"})
     * @Security("has_role('ROLE_USUARIO')")
     */
    public function verArticulos(){
          $em = $this->getDoctrine()->getManager();
          $articulos = $em->getRepository('AppBundle:Articulo')->findAll();
          return $this->render('default/ver_oficinas.html', array(
                      'articulo' => $articulos,
          ));

    }




}

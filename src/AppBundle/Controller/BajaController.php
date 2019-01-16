<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Baja;
use AppBundle\Entity\Historial;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Oficina;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;
use Ps\PdfBundle\Annotation\Pdf;

/**
 * Baja controller.
 *
 * @Route("baja")
 */
class BajaController extends Controller
{
    /**
     * Lists all Baja entities.
     *
     * @Route("/", name="baja_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $bajas = $em->getRepository('AppBundle:Baja')->findAll();

        return $this->render('baja/index.html.twig', array(
            'bajas' => $bajas,
        ));
    }

    /**
     * Creates a new Baja entity.
     * @Route("/new/{id_oficina}", name="baja_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $id_oficina)
    {
        $em = $this->getDoctrine()->getManager();
        $oficina = $em->getRepository('AppBundle:Oficina')->findOneById($id_oficina);
        $bajaRepository = $em->getRepository('AppBundle:Baja');
        $baja = $bajaRepository->findOneBy(
              array('finalizada' => 2)
          );
          if ($baja == null ){
            $baja = new Baja($oficina);
          }else{
            // $historialesViejos = $em->getRepository('AppBundle:Historial')->findByBaja($baja);
            // foreach ($historialesViejos as $h) {
            //   $em->remove($h);
            // }
            //  $em->flush();
          }


        $form = $this->createForm('AppBundle\Form\BajaType', $baja);
        $form->handleRequest($request);
        $historiales = false;

      //  $allCondiciones = $em->getRepository('AppBundle:Condicion')->findAll();



        if ($form->isSubmitted() && $form->isValid()) {

            $articulosIds = $request->request->get('articsIds');
            //$condicionesArreglo = $request->request->get('condicionesIds');
            //$condiciones = $request->request->get('condiciones');
            $oficinaId = $id_oficina;
            $baja->setUsuario($this->getUser());
            $baja->setOficina($oficina);
            $baja->setFinalizada(1);
            $em->persist($baja);
            $em->flush();
            $fecha = $baja->getFecha();
            return $this->redirectToRoute('baja_select_condition', array(
              'id' => $baja->getId(),

              'fecha' => $fecha,
              'articsIds' => $articulosIds,
              //'condiciones' => $condicionesArreglo,
              'idOficina' => $id_oficina
          ));
        }
        return $this->render('baja/new.html.twig', array(
            'continuada' => false,
            'oficina' => $id_oficina,
            'nombreOficina' => $oficina->getNombre(),
            'historiales' => $historiales,
            'baja' => $baja,
            'bajaId' => $baja->getId(),
            'form' => $form->createView(),
        ));
    }


    /**
     * Finds and displays a Baja entity.
     *
     * @Route("/{id}", name="baja_show")
     * @Method("GET")
     */
    public function showAction(Baja $Baja)
    {
        $deleteForm = $this->createDeleteForm($Baja);

        return $this->render('baja/show.html.twig', array(
            'Baja' => $Baja,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Baja entity.
     *
     * @Route("/{id}/edit", name="baja_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Baja $Baja)
    {
        $deleteForm = $this->createDeleteForm($Baja);
        $editForm = $this->createForm('AppBundle\Form\BajaType', $Baja);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('baja_edit', array('id' => $Baja->getId()));
        }

        return $this->render('baja/edit.html.twig', array(
            'Baja' => $Baja,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    // /**
    //  * Deletes a Baja entity.
    //  *
    //  * @Route("/{id}", name="baja_delete")
    //  * @Method("DELETE")
    //  */
    // public function deleteAction(Request $request, Baja $Baja)
    // {
    //     $form = $this->createDeleteForm($Baja);
    //     $form->handleRequest($request);
    //
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $em = $this->getDoctrine()->getManager();
    //         $em->remove($Baja);
    //         $em->flush();
    //     }
    //
    //     return $this->redirectToRoute('baja_index');
    // }

    // /**
    //  * Creates a form to delete a Baja entity.
    //  *
    //  * @param Baja $Baja The Baja entity
    //  *
    //  * @return \Symfony\Component\Form\Form The form
    //  */
    // private function createDeleteForm(Baja $Baja)
    // {
    //     return $this->createFormBuilder()
    //         ->setAction($this->generateUrl('baja_delete', array('id' => $Baja->getId())))
    //         ->setMethod('DELETE')
    //         ->getForm()
    //     ;
    // }

    /**
     * S
     * @Route("/select_condition/{id}", name="baja_select_condition")
     * @Method({"GET", "POST"})
     */

    public function selectCondition(Request $request, Baja $baja){

      $articulosIds = $request->query->get('articsIds');
      //$condicionesArreglo = $request->query->get('condiciones');
      $oficinaId = $request->query->get('idOficina');
      $em = $this->getDoctrine()->getManager();
      $oficina = $em->getRepository('AppBundle:Oficina')->findOneById($oficinaId);
      $baja->setOficina($oficina);
      $baja->setFinalizada(1);
      $fecha = $baja->getFecha();
      $articulosIds = explode(",", $articulosIds);

      //$condicionesArreglo = explode(",", $condicionesArreglo);
      $articuloRepository = $em->getRepository('AppBundle:Articulo');
    //  $condicionRepository = $em->getRepository('AppBundle:Condicion');
      $articulos = array();

      $i = 0;
      $historialesViejos = $em->getRepository('AppBundle:Historial')->findByBaja($baja);
      foreach ($historialesViejos as $h) {
        $em->remove($h);
      }
       $em->flush();
      foreach ($articulosIds as $id){
        $articuloActual =  $articuloRepository->findOneById($id);
        $articulos[$i] = $articuloActual;
        $estado = $em->getRepository('AppBundle:Estado')->findOneByNombre('Baja');
        $articuloActual->setEstado($estado);
        //$condicionSeleccionada = $condicionesArreglo[$j];
        //$condicionReal = $condicionRepository->findOneByNombre($condicionSeleccionada);
        //echo 'atroden';

        $historial = new Historial;
        $historial
            ->setFecha($fecha)
            ->setArticulo($articulos[$i])
            ->setBaja($baja);
        $baja->setFinalizada(1);
        $em->persist($historial);
        $em->flush();
        $i++;
      //  echo $historial->getArticulo()->getDenominacion();
        //die();
      }
      return $this->render('baja/baja_finish.html.twig', array(
        'bajas' => $baja
      ));

    }


    /**
     * Guarda la baja
     * @Route("/new/{id}/test", name="baja_test")
     * @Method({"GET", "POST"})
     */
    public function test(Request $request, $id){
      $fecha = $request->request->get('fecha');
      $fechaDate = (new \DateTime($fecha));

      $observaciones = $request->request->get('observaciones');
      $expediente = $request->request->get('expediente');
      $idsArticulos = $request->request->get('idsArticulos');
      if ($idsArticulos != null) {
        $articulosIds = explode(",", $idsArticulos);
      }else{
        $articulosIds = null;
      }
      //$condicionesArreglo = explode(",", $idsCondiciones);
      $em = $this->getDoctrine()->getManager();
      $oficinaRepository = $em->getRepository('AppBundle:Oficina');
      $bajaRepository = $em->getRepository('AppBundle:Baja');
      $oficinaReal = $oficinaRepository->findOneById($id);
      $baja = $bajaRepository->findOneBy(
              array('finalizada' => 2,
              'oficina' => $oficinaReal)
          );
      if ($baja == null ){
        $baja = new Baja($oficinaReal);
      }else{
        $historialesViejos = $em->getRepository('AppBundle:Historial')->findByBaja($baja);
        foreach ($historialesViejos as $h) {
          $em->remove($h);
        }
         $em->flush();
      }

      $articuloRepository = $em->getRepository('AppBundle:Articulo');
      //$oficinaRepository = $em->getRepository('AppBundle:Oficina');
      //$condicionRepository = $em->getRepository('AppBundle:Condicion');

      $oficina = $oficinaRepository->findOneById($id);

      $baja->setOficina($oficina);
      $baja->setFinalizada(2);
      $baja->setFecha($fechaDate);
      $baja->setExpediente($expediente);
      $baja->setUsuario($this->getUser());
      $baja->setObservaciones($observaciones);
      $em->persist($baja);






      $articulos = array();
      $j = 5;
      $i = 0;
      if ($articulosIds != null) {
        foreach ($articulosIds as $idArtic){
          $articuloActual =  $articuloRepository->findOneById($idArtic);
          $articulos[$i] = $articuloActual;
          $articuloActual->setOficina($oficina);
          //$condicionSeleccionada = $condicionesArreglo[$j];
          //$condicionReal = $condicionRepository->findOneByNombre($condicionSeleccionada);
          $historial = new Historial;
          $historial
            //  ->setCondicion($condicionReal)
              ->setFecha($fechaDate)
              ->setArticulo($articulos[$i])
              ->setBaja($baja);
          $em->persist($historial);

          $i++;
          $j++;
        }
      }
      $em->flush();

      $arreglo = array(
        //'success' => false
        'articulos' => $articulosIds
      );
      return new JsonResponse($arreglo);
    }

    /**
     * Guarda la baja
     * @Route("/baja_continue/{id}/test", name="baja_test2")
     * @Method({"GET", "POST"})
     */

     public function testReabierto(Request $request, $id){
       $fecha = $request->request->get('fecha');
       $fechaDate = (new \DateTime($fecha));

       $observaciones = $request->request->get('observaciones');
       $expediente = $request->request->get('expediente');
       $idsArticulos = $request->request->get('idsArticulos');
       if ($idsArticulos != null) {
         $articulosIds = explode(",", $idsArticulos);
       }else{
         $articulosIds = null;
       }
      // $condicionesArreglo = explode(",", $idsCondiciones);
       $em = $this->getDoctrine()->getManager();
       $oficinaRepository = $em->getRepository('AppBundle:Oficina');
       $bajaRepository = $em->getRepository('AppBundle:Baja');
       $oficinaReal = $oficinaRepository->findOneById($id);
       $baja = $bajaRepository->findOneBy(
               array('finalizada' => 2,
               'oficina' => $oficinaReal)
           );
       if ($baja == null ){
         $baja = new Baja($oficinaReal);
       }else{
         $historialesViejos = $em->getRepository('AppBundle:Historial')->findByBaja($baja);
         foreach ($historialesViejos as $h) {
           $em->remove($h);
         }
          $em->flush();
       }

       $articuloRepository = $em->getRepository('AppBundle:Articulo');
       //$oficinaRepository = $em->getRepository('AppBundle:Oficina');
       //$condicionRepository = $em->getRepository('AppBundle:Condicion');

       $oficina = $oficinaRepository->findOneById($id);

       $baja->setOficina($oficina);
       $baja->setFinalizada(2);
       $baja->setFecha($fechaDate);
       $baja->setExpediente($expediente);
       $baja->setUsuario($this->getUser());
       $baja->setObservaciones($observaciones);
       $em->persist($baja);
       $articulos = array();
       $j = 5;
       $i = 0;
       if ($articulosIds != null) {
         foreach ($articulosIds as $idArtic){
           $articuloActual =  $articuloRepository->findOneById($idArtic);
           $articulos[$i] = $articuloActual;
           $articuloActual->setOficina($oficina);
           //$condicionSeleccionada = $condicionesArreglo[$j];
           //$condicionReal = $condicionRepository->findOneByNombre($condicionSeleccionada);
           $historial = new Historial;
           $historial

               ->setFecha($fechaDate)
               ->setArticulo($articulos[$i])
               ->setBaja($baja);
           $em->persist($historial);

           $i++;
           $j++;
         }
       }
       $em->flush();

       $arreglo = array(
         //'success' => false
         'articulos' => $articulosIds
       );
       return new JsonResponse($arreglo);

     }

    /**
     * Guarda la baja
     * @Route("/new/{id}/baja_cancel", name="baja_cancel")
     * @Method({"GET", "POST"})
     */
    public function bajaCancel(Request $request, $id){
      $fecha = $request->request->get('fecha');
      $fechaDate = (new \DateTime($fecha));

      $observaciones = $request->request->get('observaciones');
      $expediente = $request->request->get('expediente');
      $idsArticulos = $request->request->get('idsArticulos');
      $articulosIds = explode(",", $idsArticulos);
      //$condicionesArreglo = explode(",", $idsCondiciones);
      $em = $this->getDoctrine()->getManager();
      $oficinaRepository = $em->getRepository('AppBundle:Oficina');
      $bajaRepository = $em->getRepository('AppBundle:Baja');
      $oficinaReal = $oficinaRepository->findOneById($id);
      $baja = $bajaRepository->findOneBy(
              array('finalizada' => 2,
              'oficina' => $oficinaReal)
          );
      if ($baja == null ){
        $baja = new Baja($oficinaReal);
      }else{
        $baja->setFinalizada(0);

      }
      $articuloRepository = $em->getRepository('AppBundle:Articulo');
      //$oficinaRepository = $em->getRepository('AppBundle:Oficina');
      //$condicionRepository = $em->getRepository('AppBundle:Condicion');

      $oficina = $oficinaRepository->findOneById($id);

      $baja->setOficina($oficina);
      $baja->setExpediente($expediente);
      $baja->setUsuario($this->getUser());

      $baja->setFecha($fechaDate);
      $baja->setObservaciones($observaciones);
      $em->persist($baja);
      $articulos = array();
      $j = 5;
      $i = 0;
      if ($articulosIds == null) {

        foreach ($articulosIds as $idArtic){
          $articuloActual =  $articuloRepository->findOneById($idArtic);
          $articulos[$i] = $articuloActual;
          $articuloActual->setOficina($oficina);
          //$condicionSeleccionada = $condicionesArreglo[$j];
          //$condicionReal = $condicionRepository->findOneByNombre($condicionSeleccionada);
          $historial = new Historial;
          $historial

              ->setFecha($fechaDate)
              ->setArticulo($articulos[$i])
              ->setBaja($baja);
          $em->persist($historial);

          $i++;
          $j++;
        }
     }
      $em->flush();
      $arreglo = array(
        'success' => true
      );
      return new JsonResponse($arreglo);

    }


    /**
     * Guarda la baja
     * @Route("/baja_continue/{id}/baja_cancel", name="baja_cancel2")
     * @Method({"GET", "POST"})
     */
    public function bajaReabiertaCancel(Request $request, $id){
      $fecha = $request->request->get('fecha');
      $fechaDate = (new \DateTime($fecha));
      $expediente = $request->request->get('expediente');
      $observaciones = $request->request->get('observaciones');
      //$idsCondiciones = $request->request->get('idsCondiciones');
      $idsArticulos = $request->request->get('idsArticulos');
      $articulosIds = explode(",", $idsArticulos);
      //$condicionesArreglo = explode(",", $idsCondiciones);
      $em = $this->getDoctrine()->getManager();
      $oficinaRepository = $em->getRepository('AppBundle:Oficina');
      $bajaRepository = $em->getRepository('AppBundle:Baja');
      $oficinaReal = $oficinaRepository->findOneById($id);
      $baja = $bajaRepository->findOneBy(
              array('finalizada' => 2,
              'oficina' => $oficinaReal)
          );
      $baja->setFinalizada(0);
      $baja->setExpediente($expediente);
      $em->persist($baja);
      $em->flush();
      $arreglo = array(
        'success' => true
      );
      return new JsonResponse($arreglo);
    }

    /**
     * Guarda la baja
     * @Route("/baja_continue/{id_oficina}", name="baja_continue")
     * @Method({"GET", "POST"})
     */
    public function bajaContinue(Request $request, $id_oficina){
      $em = $this->getDoctrine()->getManager();
      $bajaRepository = $em->getRepository('AppBundle:Baja');
      $historialRepository = $em->getRepository('AppBundle:Historial');
      $oficina = $em->getRepository('AppBundle:Oficina')->findOneById($id_oficina);
      $baja = $bajaRepository->findOneBy(
        array('finalizada' => 2,
        'oficina' => $oficina)
      );
      $form = $this->createForm('AppBundle\Form\BajaType', $baja);
      $form->handleRequest($request);

      $baja->setOficina($oficina);
      $historiales = $historialRepository->findByBaja($baja);
      if ($form->isSubmitted() && $form->isValid()) {
          $articulosIds = $request->request->get('articsIds');
          $oficinaId = $id_oficina;

          $em->persist($baja);
          $em->flush();

          $fecha = $baja->getFecha();
          return $this->redirectToRoute('baja_select_condition', array(
            'id' => $baja->getId(),

            'fecha' => $fecha,
            'articsIds' => $articulosIds,
            //'condiciones' => $condicionesArreglo,
            'idOficina' => $id_oficina
        ));
      }
      return $this->render('baja/new.html.twig', array(
          'continuada' => true,
          'oficina' => $id_oficina,
          'historiales' => $historiales,
          'nombreOficina' => $oficina->getNombre(),
          'baja' => $baja,
          'bajaId' => $baja->getId(),
          'form' => $form->createView(),
      ));
    }

    /**
     * Termina la baja
     * @Route("/{id}/oficina_show", name="baja_oficina_show2")
     * @Method({"GET", "POST"})
     */
     public function redirectOfice(Request $request, $id){
       return $this->redirectToRoute('oficina_show', array('id' => $id));
     }


     /**
      * Trae articulos para los select de condicion
      * @Route("/baja_continue/{id}/traerArticulos", name="baja_traerArticulos")
      * @Method({"GET", "POST"})
      */
      public function traerArticulos(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $oficina = $em->getRepository('AppBundle:Oficina')->findOneById($id);
        $articuloRepository = $em->getRepository('AppBundle:Articulo');
        $idsArticulos = $request->request->get('articulos');
        $idsArticulos = explode(",", $idsArticulos);
        $articulos = array();
        foreach($idsArticulos as $idArticulo){
          if (!(in_array($articuloRepository->findOneById($idArticulo), $articulos))) {
            $articulos[] = $articuloRepository->findOneById($idArticulo);
          }
        }
        $data=array();
        foreach($articulos as $articulo) {
          $data[$articulo->getId()] = array(
            'id' => $articulo->getId(),
            'numInventario' => $articulo->getNumInventario(),
            'numExpendiente' => $articulo->getNumExpediente(),
            'denominacion' => $articulo->getDenominacion(),
            'tipo' => ($articulo->getTipo()) ? $articulo->getTipo()->getDescripcion() : null,
            'estado' => $articulo->getEstado()->getNombre()

          );



        };
        return new JsonResponse($data);
      //  return new JsonResponse($arreglo);
      }

      /**
       * Trae articulos para la tabla
       * @Route("/baja_continue/{id}/traerArticulosTabla", name="baja_traerArticulosTabla")
       * @Method({"GET", "POST"})
       */
      public function traerArticulosTabla(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $oficina = $em->getRepository('AppBundle:Oficina')->findOneById($id);
        $articuloRepository = $em->getRepository('AppBundle:Articulo');
        $idsArticulos = $request->request->get('articulos');
        $idsArticulos = explode(",", $idsArticulos);
        $articulos = array();
        foreach($idsArticulos as $idArticulo){
          if (!(in_array($articuloRepository->findOneById($idArticulo), $articulos))) {
            $articulos[] = $articuloRepository->findOneById($idArticulo);
          }
        }
         foreach($articulos as $articulo) {
          $rawResponse['rows'][] = array(
             'id' => $articulo->getId(),
             'numInventario' => $articulo->getNumInventario(),
             'numExpendiente' => $articulo->getNumExpediente(),
             'denominacion' => $articulo->getDenominacion(),
             'tipo' => ($articulo->getTipo()) ? $articulo->getTipo()->getDescripcion() : null,
             'estado' => $articulo->getEstado()->getNombre()
           );
         }
       return new JsonResponse($rawResponse['rows']);

      }

      /**
       * exporta pdf
       * @Route("/ver_informe/{id}", name="baja_ver_informe")
       * @Method({"GET", "POST"})
       */

       public function pdfAction(Request $request, Baja $baja)
           {
              $em = $this->getDoctrine()->getManager();
              $historiales = $em->getRepository('AppBundle:Historial')->findByBaja($baja);
              $articuloRepository = $em->getRepository('AppBundle:Articulo');
              $articulos = array();
              foreach ($historiales as $item) {
                $articulos[] = $item->getArticulo();
              }
              if ($baja->getObservaciones()== null) {
                $tieneObservaciones = false;
              }else{
                $tieneObservaciones = true;
              }
              return $this->render('baja/informe.html.twig', array(
                'articulos' => $articulos,
                'baja' => $baja,
                'tieneObservaciones' => $tieneObservaciones,
                'oficinaOrigen' => $baja->getOficina()

              ));
           }




      /**
       * @Route("/new/oficina_show/{id}", name="baja_redirect")
       * @Method({"GET", "POST"})
       */

       public function redireccionar(Request $request, Oficina $oficina)
           {
               return $this->redirectToRoute('oficina_show', array(
               'id' => $oficina->getId()
             ));
           }



     /**
      * @Route("/baja_continue/oficina_show/{id}", name="baja_redirect2")
      * @Method({"GET", "POST"})
      */

      public function redireccionarContinue(Request $request, Oficina $oficina)
          {
              return $this->redirectToRoute('oficina_show', array(
              'id' => $oficina->getId()
            ));
          }



          /**
           * @Pdf()
           *@Route("/informePDF/{id}", defaults={ "_format"="pdf" }, name="baja_informePDF")
           */
          public function reportePDF(Request $request, Baja $baja)
          {
              $format = $request->get('_format');
              $em = $this->getDoctrine()->getManager();
              $historiales = $em->getRepository('AppBundle:Historial')->findByBaja($baja);
              $articuloRepository = $em->getRepository('AppBundle:Articulo');
              $articulos = array();
              foreach ($historiales as $item) {
                $articulos[] = $item->getArticulo();
              }
              if ($baja->getObservaciones()== null) {
                $tieneObservaciones = false;
              }else{
                $tieneObservaciones = true;
              }
              return $this->render(sprintf('baja/informe.%s.twig', $format), array(
                'articulos' => $articulos,
                'baja' => $baja,
                'tieneObservaciones' => $tieneObservaciones,
                'oficinaOrigen' => $baja->getOficina()

              ));
          }
}

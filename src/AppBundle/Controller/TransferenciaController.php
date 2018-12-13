<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Transferencia;
use AppBundle\Entity\Historial;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Oficina;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Transferencia controller.
 *
 * @Route("transferencia")
 */
class TransferenciaController extends Controller
{
    /**
     * Lists all Transferencia entities.
     *
     * @Route("/", name="transferencia_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $transferencias = $em->getRepository('AppBundle:Transferencia')->findAll();

        return $this->render('transferencia/index.html.twig', array(
            'transferencias' => $transferencias,
        ));
    }

    /**
     * Creates a new Transferencia entity.
     * @Route("/new/{id_oficina}", name="transferencia_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $id_oficina)
    {
        $em = $this->getDoctrine()->getManager();
        $oficina = $em->getRepository('AppBundle:Oficina')->findOneById($id_oficina);
        $transferenciaRepository = $em->getRepository('AppBundle:Transferencia');
        $transferencia = $transferenciaRepository->findOneBy(
              array('finalizada' => 2,
              'oficinaOrigen' => $oficina)
          );
          if ($transferencia == null ){
            $transferencia = new Transferencia($oficina);
          }else{
            $historialesViejos = $em->getRepository('AppBundle:Historial')->findByTransferencia($transferencia);
            foreach ($historialesViejos as $h) {
              $em->remove($h);
            }
             $em->flush();
          }


        $form = $this->createForm('AppBundle\Form\TransferenciaType', $transferencia);
        $form->handleRequest($request);
        $historiales = false;

        $allCondiciones = $em->getRepository('AppBundle:Condicion')->findAll();

        $transferencia->setOficinaOrigen($oficina);
        $transferencia->setUsuario($this->getUser());
        if ($form->isSubmitted() && $form->isValid()) {
            $articulosIds = $request->request->get('articsIds');
            $condicionesArreglo = $request->request->get('condicionesIds');
            $condiciones = $request->request->get('condiciones');
            $oficinaOrigenId = $id_oficina;
            $em->persist($transferencia);
            $em->flush();
            $oficinaDestino = $transferencia->getOficinaDestino();
            $fecha = $transferencia->getFecha();
            return $this->redirectToRoute('select_condition', array(
              'id' => $transferencia->getId(),
              'oficinaDestino' => $oficinaDestino,
              'fecha' => $fecha,
              'articsIds' => $articulosIds,
              'condiciones' => $condicionesArreglo,
              'idOficinaOrigen' => $id_oficina
          ));
        }
        return $this->render('transferencia/new.html.twig', array(
            'continuada' => false,
            'oficina' => $id_oficina,
            'historiales' => $historiales,
            'condiciones' => $allCondiciones,
            'transferencia' => $transferencia,
            'transferenciaId' => $transferencia->getId(),
            'form' => $form->createView(),
        ));
    }


    /**
     * Finds and displays a Transferencia entity.
     *
     * @Route("/{id}", name="transferencia_show")
     * @Method("GET")
     */
    public function showAction(Transferencia $Transferencia)
    {
        $deleteForm = $this->createDeleteForm($Transferencia);

        return $this->render('transferencia/show.html.twig', array(
            'Transferencia' => $Transferencia,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Transferencia entity.
     *
     * @Route("/{id}/edit", name="transferencia_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Transferencia $Transferencia)
    {
        $deleteForm = $this->createDeleteForm($Transferencia);
        $editForm = $this->createForm('AppBundle\Form\TransferenciaType', $Transferencia);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('transferencia_edit', array('id' => $Transferencia->getId()));
        }

        return $this->render('transferencia/edit.html.twig', array(
            'Transferencia' => $Transferencia,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    // /**
    //  * Deletes a Transferencia entity.
    //  *
    //  * @Route("/{id}", name="transferencia_delete")
    //  * @Method("DELETE")
    //  */
    // public function deleteAction(Request $request, Transferencia $Transferencia)
    // {
    //     $form = $this->createDeleteForm($Transferencia);
    //     $form->handleRequest($request);
    //
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $em = $this->getDoctrine()->getManager();
    //         $em->remove($Transferencia);
    //         $em->flush();
    //     }
    //
    //     return $this->redirectToRoute('transferencia_index');
    // }

    // /**
    //  * Creates a form to delete a Transferencia entity.
    //  *
    //  * @param Transferencia $Transferencia The Transferencia entity
    //  *
    //  * @return \Symfony\Component\Form\Form The form
    //  */
    // private function createDeleteForm(Transferencia $Transferencia)
    // {
    //     return $this->createFormBuilder()
    //         ->setAction($this->generateUrl('transferencia_delete', array('id' => $Transferencia->getId())))
    //         ->setMethod('DELETE')
    //         ->getForm()
    //     ;
    // }

    /**
     *
     * @Route("/select_condition/{id}", name="select_condition")
     * @Method({"GET", "POST"})
     */

    public function selectCondition(Request $request, Transferencia $transferencia){
      $articulosIds = $request->query->get('articsIds');
      $condicionesArreglo = $request->query->get('condiciones');
      $oficinaOrigenId = $request->query->get('idOficinaOrigen');
      $em = $this->getDoctrine()->getManager();
      $oficinaOrigen = $em->getRepository('AppBundle:Oficina')->findOneById($oficinaOrigenId);
      $transferencia->setOficinaOrigen($oficinaOrigen);
      $oficinaDestino = $transferencia->getOficinaDestino();
      $fecha = $transferencia->getFecha();
      // echo $articulosIds;
      // die();
      $articulosIds = explode(",", $articulosIds);

      $condicionesArreglo = explode(",", $condicionesArreglo);
      $articuloRepository = $em->getRepository('AppBundle:Articulo');
      $condicionRepository = $em->getRepository('AppBundle:Condicion');
      $articulos = array();
      $j = 5;
      $i = 0;
      $historialesViejos = $em->getRepository('AppBundle:Historial')->findByTransferencia($transferencia);
      foreach ($historialesViejos as $h) {
        $em->remove($h);
      }
       $em->flush();
      foreach ($articulosIds as $id){
        $articuloActual =  $articuloRepository->findOneById($id);
        $articulos[$i] = $articuloActual;

        $articuloActual->setOficina($oficinaDestino); //aca falla
        $estado = $em->getRepository('AppBundle:Estado')->findOneById(2);
        $articuloActual->setEstado($estado);
        $condicionSeleccionada = $condicionesArreglo[$j];
        $condicionReal = $condicionRepository->findOneByNombre($condicionSeleccionada);
        $historial = new Historial;
        $historial
            ->setCondicion($condicionReal)
            ->setFecha($fecha)
            ->setArticulo($articulos[$i])
            ->setTransferencia($transferencia);
        $transferencia->setFinalizada(1);
        $em->persist($historial);
        $em->flush();
        $i++;
        $j++;
      }
      return $this->render('transferencia/transferencia_finish.html.twig', array(
        'transferencias' => $transferencia
      ));

    }


    /**
     * Guarda la transferencia
     * @Route("/new/{id}/test", name="test")
     * @Method({"GET", "POST"})
     */
    public function test(Request $request, $id){
      $fecha = $request->request->get('fecha');
      $fechaDate = (new \DateTime($fecha));
      $oficinaDestino = $request->request->get('oficinaDestino');
      $observaciones = $request->request->get('observaciones');
      $idsCondiciones = $request->request->get('idsCondiciones');
      $idsArticulos = $request->request->get('idsArticulos');
      if ($idsArticulos != null) {
        $articulosIds = explode(",", $idsArticulos);
      }else{
        $articulosIds = null;
      }
      $condicionesArreglo = explode(",", $idsCondiciones);
      $em = $this->getDoctrine()->getManager();
      $oficinaRepository = $em->getRepository('AppBundle:Oficina');
      $transferenciaRepository = $em->getRepository('AppBundle:Transferencia');
      $oficinaReal = $oficinaRepository->findOneById($id);
      $transferencia = $transferenciaRepository->findOneBy(
              array('finalizada' => 2,
              'oficinaOrigen' => $oficinaReal)
          );
      if ($transferencia == null ){
        $transferencia = new Transferencia($oficinaReal);
      }else{
        $historialesViejos = $em->getRepository('AppBundle:Historial')->findByTransferencia($transferencia);
        foreach ($historialesViejos as $h) {
          $em->remove($h);
        }
         $em->flush();
      }

      $articuloRepository = $em->getRepository('AppBundle:Articulo');
      //$oficinaRepository = $em->getRepository('AppBundle:Oficina');
      $condicionRepository = $em->getRepository('AppBundle:Condicion');
      $oficinaDest = $oficinaRepository->findOneById($oficinaDestino);
      $oficinaOrig = $oficinaRepository->findOneById($id);
      $transferencia->setOficinaDestino($oficinaDest);
      $transferencia->setOficinaOrigen($oficinaOrig);
      $transferencia->setFinalizada(2);
      $transferencia->setFecha($fechaDate);
      $transferencia->setUsuario($this->getUser());
      $transferencia->setObservaciones($observaciones);
      $em->persist($transferencia);






      $articulos = array();
      $j = 5;
      $i = 0;
      if ($articulosIds != null) {
        foreach ($articulosIds as $idArtic){
          $articuloActual =  $articuloRepository->findOneById($idArtic);
          $articulos[$i] = $articuloActual;
          $articuloActual->setOficina($oficinaOrig);
          $condicionSeleccionada = $condicionesArreglo[$j];
          $condicionReal = $condicionRepository->findOneByNombre($condicionSeleccionada);
          $historial = new Historial;
          $historial
              ->setCondicion($condicionReal)
              ->setFecha($fechaDate)
              ->setArticulo($articulos[$i])
              ->setTransferencia($transferencia);
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
     * Guarda la transferencia
     * @Route("/transferencia_continue/{id}/test", name="test2")
     * @Method({"GET", "POST"})
     */

     public function testReabierto(Request $request, $id){
       $fecha = $request->request->get('fecha');
       $fechaDate = (new \DateTime($fecha));
       $oficinaDestino = $request->request->get('oficinaDestino');
       $observaciones = $request->request->get('observaciones');
       $idsCondiciones = $request->request->get('idsCondiciones');
       $idsArticulos = $request->request->get('idsArticulos');
       if ($idsArticulos != null) {
         $articulosIds = explode(",", $idsArticulos);
       }else{
         $articulosIds = null;
       }
       $condicionesArreglo = explode(",", $idsCondiciones);
       $em = $this->getDoctrine()->getManager();
       $oficinaRepository = $em->getRepository('AppBundle:Oficina');
       $transferenciaRepository = $em->getRepository('AppBundle:Transferencia');
       $oficinaReal = $oficinaRepository->findOneById($id);
       $transferencia = $transferenciaRepository->findOneBy(
               array('finalizada' => 2,
               'oficinaOrigen' => $oficinaReal)
           );
       if ($transferencia == null ){
         $transferencia = new Transferencia($oficinaReal);
       }else{
         $historialesViejos = $em->getRepository('AppBundle:Historial')->findByTransferencia($transferencia);
         foreach ($historialesViejos as $h) {
           $em->remove($h);
         }
          $em->flush();
       }

       $articuloRepository = $em->getRepository('AppBundle:Articulo');
       //$oficinaRepository = $em->getRepository('AppBundle:Oficina');
       $condicionRepository = $em->getRepository('AppBundle:Condicion');
       $oficinaDest = $oficinaRepository->findOneById($oficinaDestino);
       $oficinaOrig = $oficinaRepository->findOneById($id);
       $transferencia->setOficinaDestino($oficinaDest);
       $transferencia->setOficinaOrigen($oficinaOrig);
       $transferencia->setFinalizada(2);
       $transferencia->setFecha($fechaDate);
       $transferencia->setUsuario($this->getUser());
       $transferencia->setObservaciones($observaciones);
       $em->persist($transferencia);
       $articulos = array();
       $j = 5;
       $i = 0;
       if ($articulosIds != null) {
         foreach ($articulosIds as $idArtic){
           $articuloActual =  $articuloRepository->findOneById($idArtic);
           $articulos[$i] = $articuloActual;
           $articuloActual->setOficina($oficinaOrig);
           $condicionSeleccionada = $condicionesArreglo[$j];
           $condicionReal = $condicionRepository->findOneByNombre($condicionSeleccionada);
           $historial = new Historial;
           $historial
               ->setCondicion($condicionReal)
               ->setFecha($fechaDate)
               ->setArticulo($articulos[$i])
               ->setTransferencia($transferencia);
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
     * Guarda la transferencia
     * @Route("/new/{id}/transferencia_cancel", name="transferencia_cancel")
     * @Method({"GET", "POST"})
     */
    public function transferenciaCancel(Request $request, $id){
      $fecha = $request->request->get('fecha');
      $fechaDate = (new \DateTime($fecha));
      $oficinaDestino = $request->request->get('oficinaDestino');
      $observaciones = $request->request->get('observaciones');
      $idsCondiciones = $request->request->get('idsCondiciones');
      $idsArticulos = $request->request->get('idsArticulos');
      $articulosIds = explode(",", $idsArticulos);
      $condicionesArreglo = explode(",", $idsCondiciones);
      $em = $this->getDoctrine()->getManager();
      $oficinaRepository = $em->getRepository('AppBundle:Oficina');
      $transferenciaRepository = $em->getRepository('AppBundle:Transferencia');
      $oficinaReal = $oficinaRepository->findOneById($id);
      $transferencia = $transferenciaRepository->findOneBy(
              array('finalizada' => 2,
              'oficinaOrigen' => $oficinaReal)
          );
      if ($transferencia == null ){
        $transferencia = new Transferencia($oficinaReal);
      }else{
        $transferencia->setFinalizada(0);

      }
      $articuloRepository = $em->getRepository('AppBundle:Articulo');
      //$oficinaRepository = $em->getRepository('AppBundle:Oficina');
      $condicionRepository = $em->getRepository('AppBundle:Condicion');
      $oficinaDest = $oficinaRepository->findOneById($oficinaDestino);
      $oficinaOrig = $oficinaRepository->findOneById($id);
      $transferencia->setOficinaDestino($oficinaDest);
      $transferencia->setOficinaOrigen($oficinaOrig);
      $transferencia->setUsuario($this->getUser());

      $transferencia->setFecha($fechaDate);
      $transferencia->setObservaciones($observaciones);
      $em->persist($transferencia);
      $articulos = array();
      $j = 5;
      $i = 0;
      if ($articulosIds == null) {

        foreach ($articulosIds as $idArtic){
          $articuloActual =  $articuloRepository->findOneById($idArtic);
          $articulos[$i] = $articuloActual;
          $articuloActual->setOficina($oficinaOrig);
          $condicionSeleccionada = $condicionesArreglo[$j];
          $condicionReal = $condicionRepository->findOneByNombre($condicionSeleccionada);
          $historial = new Historial;
          $historial
              ->setCondicion($condicionReal)
              ->setFecha($fechaDate)
              ->setArticulo($articulos[$i])
              ->setTransferencia($transferencia);
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
     * Guarda la transferencia
     * @Route("/transferencia_continue/{id}/transferencia_cancel", name="transferencia_cancel2")
     * @Method({"GET", "POST"})
     */
    public function transferenciaReabiertaCancel(Request $request, $id){
      $fecha = $request->request->get('fecha');
      $fechaDate = (new \DateTime($fecha));
      $oficinaDestino = $request->request->get('oficinaDestino');
      $observaciones = $request->request->get('observaciones');
      $idsCondiciones = $request->request->get('idsCondiciones');
      $idsArticulos = $request->request->get('idsArticulos');
      $articulosIds = explode(",", $idsArticulos);
      $condicionesArreglo = explode(",", $idsCondiciones);
      $em = $this->getDoctrine()->getManager();
      $oficinaRepository = $em->getRepository('AppBundle:Oficina');
      $transferenciaRepository = $em->getRepository('AppBundle:Transferencia');
      $oficinaReal = $oficinaRepository->findOneById($id);
      $transferencia = $transferenciaRepository->findOneBy(
              array('finalizada' => 2,
              'oficinaOrigen' => $oficinaReal)
          );
      $transferencia->setFinalizada(0);
      $em->persist($transferencia);
      $em->flush();
      $arreglo = array(
        'success' => true
      );
      return new JsonResponse($arreglo);
    }

    /**
     * Guarda la transferencia
     * @Route("/transferencia_continue/{id_oficina}", name="transferencia_continue")
     * @Method({"GET", "POST"})
     */
    public function transferenciaContinue(Request $request, $id_oficina){
      $em = $this->getDoctrine()->getManager();
      $transferenciaRepository = $em->getRepository('AppBundle:Transferencia');
      $historialRepository = $em->getRepository('AppBundle:Historial');
      $oficina = $em->getRepository('AppBundle:Oficina')->findOneById($id_oficina);
      $transferencia = $transferenciaRepository->findOneBy(
        array('finalizada' => 2,
        'oficinaOrigen' => $oficina)
      );
      $form = $this->createForm('AppBundle\Form\TransferenciaType', $transferencia);
      $form->handleRequest($request);
      $allCondiciones = $em->getRepository('AppBundle:Condicion')->findAll();
      $transferencia->setOficinaOrigen($oficina);
      $historiales = $historialRepository->findByTransferencia($transferencia);


      if ($form->isSubmitted() && $form->isValid()) {
          $articulosIds = $request->request->get('articsIds');
          //  echo "<pre>";
          //  var_dump($articulosIds);
          //  echo "</pre>";
          //  echo 'adada';
          // die();
          $condicionesArreglo = $request->request->get('condicionesIds');
          $condiciones = $request->request->get('condiciones');
          $oficinaOrigenId = $id_oficina;

          $em->persist($transferencia);
          $em->flush();
          $oficinaDestino = $transferencia->getOficinaDestino();
          $fecha = $transferencia->getFecha();
          return $this->redirectToRoute('select_condition', array(
            'id' => $transferencia->getId(),
            'oficinaDestino' => $oficinaDestino,
            'fecha' => $fecha,
            'articsIds' => $articulosIds,
            'condiciones' => $condicionesArreglo,
            'idOficinaOrigen' => $id_oficina
        ));
      }
      return $this->render('transferencia/new.html.twig', array(
          'continuada' => true,
          'oficina' => $id_oficina,
          'historiales' => $historiales,
          'condiciones' => $allCondiciones,
          'transferencia' => $transferencia,
          'transferenciaId' => $transferencia->getId(),
          'form' => $form->createView(),
      ));
    }

    /**
     * Termina la transferencia
     * @Route("/{id}/oficina_show", name="oficina_show2")
     * @Method({"GET", "POST"})
     */
     public function redirectOfice(Request $request, $id){
       return $this->redirectToRoute('oficina_show', array('id' => $id));
     }


     /**
      * Trae articulos para los select de condicion
      * @Route("/transferencia_continue/{id}/traerArticulos", name="traerArticulos")
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
       * @Route("/transferencia_continue/{id}/traerArticulosTabla", name="traerArticulosTabla")
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
       return new JsonResponse($rawResponse);

      }

      /**
       * exporta pdf
       * @Route("/ver_informe/{id}", name="ver_informe")
       * @Method({"GET", "POST"})
       */

       public function pdfAction(Request $request, Transferencia $transferencia)
           {
              $em = $this->getDoctrine()->getManager();
              $historiales = $em->getRepository('AppBundle:Historial')->findByTransferencia($transferencia);
              $articuloRepository = $em->getRepository('AppBundle:Articulo');
              $articulos = array();
              foreach ($historiales as $item) {
                $articulos[] = $item->getArticulo();
              }
              if ($transferencia->getObservaciones()== null) {
                $tieneObservaciones = false;
              }else{
                $tieneObservaciones = true;
              }
              return $this->render('transferencia/informe.html.twig', array(
                'articulos' => $articulos,
                'transferencia' => $transferencia,
                'tieneObservaciones' => $tieneObservaciones,
                'oficinaOrigen' => $transferencia->getOficinaOrigen(),
                'oficinaDestino' => $transferencia->getOficinaDestino()
              ));
           }




           /**
            * @Route("/new/oficina_show/{id}", name="transferencia_redirect")
            * @Method({"GET", "POST"})
            */

            public function redireccionar(Request $request, Oficina $oficina)
                {
                    return $this->redirectToRoute('oficina_show', array(
                    'id' => $oficina->getId()
                  ));
                }



          /**
           * @Route("/transferencia_continue/oficina_show/{id}", name="transferencia_redirect2")
           * @Method({"GET", "POST"})
           */

           public function redireccionarContinue(Request $request, Oficina $oficina)
               {
                   return $this->redirectToRoute('oficina_show', array(
                   'id' => $oficina->getId()
                 ));
               }


}

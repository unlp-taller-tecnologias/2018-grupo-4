<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Transferencia;
use AppBundle\Entity\Historial;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Oficina;

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
     * @Route("/new/{id}", name="transferencia_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $id)
    {

        $transferencia = new Transferencia();
        $form = $this->createForm('AppBundle\Form\TransferenciaType', $transferencia);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $oficina = $em->getRepository('AppBundle:Oficina')->findOneById($id);
        $transferencia->setOficinaOrigen($oficina);
        if ($form->isSubmitted() && $form->isValid()) {
            $articulosIds = $request->request->get('articulosIds');
            $oficinaOrigenId = $id;
            $em->persist($transferencia);
            $em->flush();
            return $this->redirectToRoute('select_condition', array(
              'id' => $transferencia->getId(),
              'articulosIds' => $articulosIds,
              'idOficinaOrigen' => $id
          ));
        }
        return $this->render('transferencia/new.html.twig', array(
            'oficina' => $id,
            'Transferencia' => $transferencia,
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

    /**
     * Deletes a Transferencia entity.
     *
     * @Route("/{id}", name="transferencia_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Transferencia $Transferencia)
    {
        $form = $this->createDeleteForm($Transferencia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($Transferencia);
            $em->flush();
        }

        return $this->redirectToRoute('transferencia_index');
    }

    /**
     * Creates a form to delete a Transferencia entity.
     *
     * @param Transferencia $Transferencia The Transferencia entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Transferencia $Transferencia)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('transferencia_delete', array('id' => $Transferencia->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Solicita condicion de los articulos a transferir
     * @Route("/select_condition/{id}", name="select_condition")
     * @Method({"GET", "POST"})
     */

    public function selectCondition(Request $request, Transferencia $transferencia){
      $articulosIds = $request->query->get('articulosIds');

      $oficinaOrigenId = $request->query->get('idOficinaOrigen');
      $articulosIds = explode(",", $articulosIds);
      $em = $this->getDoctrine()->getManager();
      $articuloRepository = $em->getRepository('AppBundle:Articulo');
      $articulos = array();
      $transferencia->setOficinaOrigen($oficinaOrigenId);
      foreach ($articulosIds as $id){
        $articulos[] =  $articuloRepository->findByNumInventario($id);
      }
      $condiciones = $em->getRepository('AppBundle:Condicion')->findAll();
      return $this->render('transferencia/select_condition.html.twig', array(
          'oficinaOrigenId' => $oficinaOrigenId,
          'transferencia' => $transferencia,
          'articulos' => $articulos,
          'condiciones' => $condiciones
      ));
    }

    /**
     * Termina la transferencia
     * @Route("select_condition/finish/{id}", name="transferencia_finished")
     * @Method({"GET", "POST"})
     */

    public function finishTransferencia(Request $request, Transferencia $transferencia){
      $em = $this->getDoctrine()->getManager();
      $condicionRepository = $em->getRepository('AppBundle:Condicion');
      $transferenciaId = $request->request->get('transferenciaId');
      $transfer = $em->getRepository('AppBundle:Transferencia')->findOneById($transferenciaId);
      $oficina_destino = $transfer->getOficinaDestino();
      $fecha = $transfer->getFecha();
      $articulos = array();
      $articulosIds = $request->request->get('articulosIds');
      $articulosIds = explode(",", $articulosIds);
      $articuloRepository = $em->getRepository('AppBundle:Articulo');
      $i=0;
      foreach ($articulosIds as $id){
        $articulos[$i] =  $articuloRepository->findOneById($id);
        $condicionSeleccionada = $request->request->get($id);
        $condicionReal = $condicionRepository->findOneByNombre($condicionSeleccionada);
        $articuloActual = $articulos[$i];
        $articuloActual->setOficina($oficina_destino);
        $historial = new Historial;
        $historial
            ->setCondicion($condicionReal)
            ->setFecha($fecha)
            ->setArticulo($articulos[$i])
            ->setTransferencia($transfer);
        $em->persist($historial);
        $em->flush();
        $i++;
      }
      return $this->render('transferencia/transferencia_finish.html.twig', array(
        'transferencias' => $transferencia
      ));
    }

    /**
     * Cancela la transferencia
     * @Route("/transferencia_cancel/{id}", name="transferencia_cancel")
     * @Method({"GET", "POST"})
     */

    public function transferenciaCancel(Request $request, Transferencia $transferencia){



    }

}

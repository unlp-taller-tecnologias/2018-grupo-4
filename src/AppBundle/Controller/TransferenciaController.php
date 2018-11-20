<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Transferencia;
use AppBundle\Entity\Historial;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
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
        $oficinas = $em->getRepository('AppBundle:Articulo')->findByOficina($id);
        if ($form->isSubmitted() && $form->isValid()) {
            $articulosId = $request->request->get('transferenciasIds');
            //$transferenciasId = explode(',', $transferenciasId);
            $transferencia->setFinalizada('1');
            //die($id);
            //$transferencia->setOficinaOrigen($oficinas);
            $em->persist($transferencia);
            $em->flush();
            return $this->redirectToRoute('select_condition', array(
            'id' => $transferencia->getId(),
            'articulosId' => $articulosId
          ));
        }
        return $this->render('transferencia/new.html.twig', array(
            'oficinas' => $oficinas,
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
      $articulosId = $request->query->get('articulosId');
      $articulosId = explode(",", $articulosId);
      $em = $this->getDoctrine()->getManager();
      $articuloRepository = $em->getRepository('AppBundle:Articulo');
      $articulos = array();
      foreach ($articulosId as $id){
        $articulos[] =  $articuloRepository->findByNumInventario($id);
      }
      $condiciones = $em->getRepository('AppBundle:Condicion')->findAll();
      return $this->render('transferencia/select_condition.html.twig', array(
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
      //crear 1 historial por articulos
      // crear 1 condicion x articulo
      // la transferencia conoce los ids de articulos??

      //$articulo = $transferencia->getHistoriales();
      //foreach ($articulo as $a ) {
      //  $historial = new Historial();
      //  $historial->set
    //  }


      return $this->render('transferencia/transferencia_finish.html.twig', array(
        'transferencias' => $transferencia
      ));
    }




}

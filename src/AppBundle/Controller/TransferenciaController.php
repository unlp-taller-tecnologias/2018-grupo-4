<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Transferencia;
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
     *
     * @Route("/new/{id}", name="transferencia_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $id)
    {
        $Transferencia = new Transferencia();
        $form = $this->createForm('AppBundle\Form\TransferenciaType', $Transferencia);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $oficinas = $em->getRepository('AppBundle:Articulo')->findByOficina($id);

        if ($form->isSubmitted() && $form->isValid()) {
            $em2 = $this->getDoctrine()->getManager();
            $em2->persist($Transferencia);
            $em2->flush();

            return $this->redirectToRoute('transferencia_show', array('id' => $Transferencia->getId()));
        }

        return $this->render('transferencia/new.html.twig', array(
            'oficinas' => $oficinas,
            'Transferencia' => $Transferencia,
            'form' => $form->createView(),
        ));
    }

    /**
      * Elegir articulos para la transferencia
      *
     * @Route("/new/{id}", name="agregar_articulos")
     * @Method({"GET", "POST"})
     */
  /*  public function agregarArticulos(Request $request, Oficina $oficina)
    {
        $Transferencia = new Transferencia();
        $form = $this->createForm('AppBundle\Form\TransferenciaType', $Transferencia);
        $form->handleRequest($request);
        $articulos = $em->getRepository('AppBundle:Articulo')->findAll();
        /*if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($Transferencia);
            $em->flush();

            return $this->redirectToRoute('transferencia_show', array('id' => $Transferencia->getId()));
        }

        return $this->render('transferencia/agregar_articulos.html.twig', array(
            'Transferencia' => $Transferencia,
            'articulos' => $articulos,
            'form' => $form->createView(),
        ));
    }*/







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
     * Transfiere articulos.
     *
     * @Route("/transferir", name="transferir")
     * @Method("POST")
     */

    private function Transferir($articulos){
      
    }




        /**
         * Lists all articulo entities.
         *
         * @Route("/{oficina}/articulos/listado", name="oficina_show_listado")
         * @Method("GET")
         */
  /*      public function showListadoAction(Request $request, Oficina $oficina) {
          $offset = $request->query->get('offset', 0);
          $limit = $request->query->get('limit', 10);
          $search = $request->query->get('search', null);
          $sort = $request->query->get('sort', null);
          $order = $request->query->get('order', null);

          $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Articulo');
          $articulos = $repository->getBy($offset, $limit, $sort, $order, $search, $oficina);
          $total = $repository->countBy($search, $oficina);

          $rawResponse = array(
            'total' => $total,
            'rows' => array()
          );

          foreach($articulos as $articulo) {
            $rawResponse['rows'][] = array(
              'id' => $articulo->getId(),
              'numInventario' => $articulo->getNumInventario(),
              'numExpendiente' => $articulo->getNumExpediente(),
              'denominacion' => $articulo->getDenominacion(),
              'tipo' => ($articulo->getTipo()) ? $articulo->getTipo()->getDescripcion() : null,
              'estado' => $articulo->getEstado()->getNombre()
            );
          };

          return new JsonResponse($rawResponse);
        }*/



}

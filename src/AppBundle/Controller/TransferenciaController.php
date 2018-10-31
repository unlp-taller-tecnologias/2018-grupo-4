<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Transferencia;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/new", name="transferencia_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $Transferencia = new Transferencia();
        $form = $this->createForm('AppBundle\Form\TransferenciaType', $Transferencia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($Transferencia);
            $em->flush();

            return $this->redirectToRoute('transferencia_show', array('id' => $Transferencia->getId()));
        }

        return $this->render('transferencia/new.html.twig', array(
            'Transferencia' => $Transferencia,
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
}

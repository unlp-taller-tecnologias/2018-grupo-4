<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Estado;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Estado controller.
 *
 * @Route("estado")
 */
class EstadoController extends Controller
{
    /**
     * Lists all estado entities.
     *
     * @Route("/", name="estado_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $estados = $em->getRepository('AppBundle:Estado')->findAll();

        return $this->render('estado/index.html.twig', array(
            'estados' => $estados,
        ));
    }

    /**
     * Creates a new estado entity.
     *
     * @Route("/new", name="estado_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $estado = new Estado();
        $form = $this->createForm('AppBundle\Form\EstadoType', $estado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($estado);
            $em->flush();

            return $this->redirectToRoute('estado_show', array('id' => $estado->getId()));
        }

        return $this->render('estado/new.html.twig', array(
            'estado' => $estado,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a estado entity.
     *
     * @Route("/{id}", name="estado_show")
     * @Method("GET")
     */
    public function showAction(Estado $estado)
    {
        $deleteForm = $this->createDeleteForm($estado);

        return $this->render('estado/show.html.twig', array(
            'estado' => $estado,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing estado entity.
     *
     * @Route("/{id}/edit", name="estado_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Estado $estado)
    {
        $deleteForm = $this->createDeleteForm($estado);
        $editForm = $this->createForm('AppBundle\Form\EstadoType', $estado);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('estado_edit', array('id' => $estado->getId()));
        }

        return $this->render('estado/edit.html.twig', array(
            'estado' => $estado,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a estado entity.
     *
     * @Route("/{id}", name="estado_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Estado $estado)
    {
        $form = $this->createDeleteForm($estado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($estado);
            $em->flush();
        }

        return $this->redirectToRoute('estado_index');
    }

    /**
     * Creates a form to delete a estado entity.
     *
     * @param Estado $estado The estado entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Estado $estado)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('estado_delete', array('id' => $estado->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

<?php

namespace AppBundle\Controller;

use AppBundle\Entity\EstadoArticulo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Estadoarticulo controller.
 *
 * @Route("estadoarticulo")
 */
class EstadoArticuloController extends Controller
{
    /**
     * Lists all estadoArticulo entities.
     *
     * @Route("/", name="estadoarticulo_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $estadoArticulos = $em->getRepository('AppBundle:EstadoArticulo')->findAll();

        return $this->render('estadoarticulo/index.html.twig', array(
            'estadoArticulos' => $estadoArticulos,
        ));
    }

    /**
     * Creates a new estadoArticulo entity.
     *
     * @Route("/new", name="estadoarticulo_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $estadoArticulo = new Estadoarticulo();
        $form = $this->createForm('AppBundle\Form\EstadoArticuloType', $estadoArticulo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($estadoArticulo);
            $em->flush();

            return $this->redirectToRoute('estadoarticulo_show', array('id' => $estadoArticulo->getId()));
        }

        return $this->render('estadoarticulo/new.html.twig', array(
            'estadoArticulo' => $estadoArticulo,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a estadoArticulo entity.
     *
     * @Route("/{id}", name="estadoarticulo_show")
     * @Method("GET")
     */
    public function showAction(EstadoArticulo $estadoArticulo)
    {
        $deleteForm = $this->createDeleteForm($estadoArticulo);

        return $this->render('estadoarticulo/show.html.twig', array(
            'estadoArticulo' => $estadoArticulo,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing estadoArticulo entity.
     *
     * @Route("/{id}/edit", name="estadoarticulo_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, EstadoArticulo $estadoArticulo)
    {
        $deleteForm = $this->createDeleteForm($estadoArticulo);
        $editForm = $this->createForm('AppBundle\Form\EstadoArticuloType', $estadoArticulo);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('estadoarticulo_edit', array('id' => $estadoArticulo->getId()));
        }

        return $this->render('estadoarticulo/edit.html.twig', array(
            'estadoArticulo' => $estadoArticulo,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a estadoArticulo entity.
     *
     * @Route("/{id}", name="estadoarticulo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, EstadoArticulo $estadoArticulo)
    {
        $form = $this->createDeleteForm($estadoArticulo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($estadoArticulo);
            $em->flush();
        }

        return $this->redirectToRoute('estadoarticulo_index');
    }

    /**
     * Creates a form to delete a estadoArticulo entity.
     *
     * @param EstadoArticulo $estadoArticulo The estadoArticulo entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(EstadoArticulo $estadoArticulo)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('estadoarticulo_delete', array('id' => $estadoArticulo->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

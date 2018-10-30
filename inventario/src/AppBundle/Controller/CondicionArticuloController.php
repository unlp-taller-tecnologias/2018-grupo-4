<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CondicionArticulo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Condicionarticulo controller.
 *
 * @Route("condicionarticulo")
 */
class CondicionArticuloController extends Controller
{
    /**
     * Lists all condicionArticulo entities.
     *
     * @Route("/", name="condicionarticulo_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $condicionArticulos = $em->getRepository('AppBundle:CondicionArticulo')->findAll();

        return $this->render('condicionarticulo/index.html.twig', array(
            'condicionArticulos' => $condicionArticulos,
        ));
    }

    /**
     * Creates a new condicionArticulo entity.
     *
     * @Route("/new", name="condicionarticulo_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $condicionArticulo = new Condicionarticulo();
        $form = $this->createForm('AppBundle\Form\CondicionArticuloType', $condicionArticulo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($condicionArticulo);
            $em->flush();

            return $this->redirectToRoute('condicionarticulo_show', array('id' => $condicionArticulo->getId()));
        }

        return $this->render('condicionarticulo/new.html.twig', array(
            'condicionArticulo' => $condicionArticulo,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a condicionArticulo entity.
     *
     * @Route("/{id}", name="condicionarticulo_show")
     * @Method("GET")
     */
    public function showAction(CondicionArticulo $condicionArticulo)
    {
        $deleteForm = $this->createDeleteForm($condicionArticulo);

        return $this->render('condicionarticulo/show.html.twig', array(
            'condicionArticulo' => $condicionArticulo,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing condicionArticulo entity.
     *
     * @Route("/{id}/edit", name="condicionarticulo_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CondicionArticulo $condicionArticulo)
    {
        $deleteForm = $this->createDeleteForm($condicionArticulo);
        $editForm = $this->createForm('AppBundle\Form\CondicionArticuloType', $condicionArticulo);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('condicionarticulo_edit', array('id' => $condicionArticulo->getId()));
        }

        return $this->render('condicionarticulo/edit.html.twig', array(
            'condicionArticulo' => $condicionArticulo,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a condicionArticulo entity.
     *
     * @Route("/{id}", name="condicionarticulo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CondicionArticulo $condicionArticulo)
    {
        $form = $this->createDeleteForm($condicionArticulo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($condicionArticulo);
            $em->flush();
        }

        return $this->redirectToRoute('condicionarticulo_index');
    }

    /**
     * Creates a form to delete a condicionArticulo entity.
     *
     * @param CondicionArticulo $condicionArticulo The condicionArticulo entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CondicionArticulo $condicionArticulo)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('condicionarticulo_delete', array('id' => $condicionArticulo->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

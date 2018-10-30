<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TipoArticulo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Tipoarticulo controller.
 *
 * @Route("tipoarticulo")
 */
class TipoArticuloController extends Controller
{
    /**
     * Lists all tipoArticulo entities.
     *
     * @Route("/", name="tipoarticulo_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tipoArticulos = $em->getRepository('AppBundle:TipoArticulo')->findAll();

        return $this->render('tipoarticulo/index.html.twig', array(
            'tipoArticulos' => $tipoArticulos,
        ));
    }

    /**
     * Creates a new tipoArticulo entity.
     *
     * @Route("/new", name="tipoarticulo_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $tipoArticulo = new Tipoarticulo();
        $form = $this->createForm('AppBundle\Form\TipoArticuloType', $tipoArticulo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tipoArticulo);
            $em->flush();

            return $this->redirectToRoute('tipoarticulo_show', array('id' => $tipoArticulo->getId()));
        }

        return $this->render('tipoarticulo/new.html.twig', array(
            'tipoArticulo' => $tipoArticulo,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a tipoArticulo entity.
     *
     * @Route("/{id}", name="tipoarticulo_show")
     * @Method("GET")
     */
    public function showAction(TipoArticulo $tipoArticulo)
    {
        $deleteForm = $this->createDeleteForm($tipoArticulo);

        return $this->render('tipoarticulo/show.html.twig', array(
            'tipoArticulo' => $tipoArticulo,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing tipoArticulo entity.
     *
     * @Route("/{id}/edit", name="tipoarticulo_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, TipoArticulo $tipoArticulo)
    {
        $deleteForm = $this->createDeleteForm($tipoArticulo);
        $editForm = $this->createForm('AppBundle\Form\TipoArticuloType', $tipoArticulo);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tipoarticulo_edit', array('id' => $tipoArticulo->getId()));
        }

        return $this->render('tipoarticulo/edit.html.twig', array(
            'tipoArticulo' => $tipoArticulo,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a tipoArticulo entity.
     *
     * @Route("/{id}", name="tipoarticulo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, TipoArticulo $tipoArticulo)
    {
        $form = $this->createDeleteForm($tipoArticulo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tipoArticulo);
            $em->flush();
        }

        return $this->redirectToRoute('tipoarticulo_index');
    }

    /**
     * Creates a form to delete a tipoArticulo entity.
     *
     * @param TipoArticulo $tipoArticulo The tipoArticulo entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TipoArticulo $tipoArticulo)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tipoarticulo_delete', array('id' => $tipoArticulo->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Articulo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Articulo controller.
 *
 * @Route("articulo")
 */
class ArticuloController extends Controller
{
    /**
     * Lists all articulo entities.
     *
     * @Route("/", name="articulo_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $articulos = $em->getRepository('AppBundle:Articulo')->findAll();

        return $this->render('articulo/index.html.twig', array(
            'articulos' => $articulos,
        ));
    }

    /**
     * Creates a new articulo entity.
     *
     * @Route("/new", name="articulo_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $articulo = new Articulo();
        $form = $this->createForm('AppBundle\Form\ArticuloType', $articulo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($articulo);
            $em->flush();

            return $this->redirectToRoute('articulo_show', array('id' => $articulo->getId()));
        }

        return $this->render('articulo/new.html.twig', array(
            'articulo' => $articulo,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a articulo entity.
     *
     * @Route("/{id}", name="articulo_show")
     * @Method("GET")
     */
    public function showAction(Articulo $articulo)
    {
        $deleteForm = $this->createDeleteForm($articulo);

        return $this->render('articulo/show.html.twig', array(
            'articulo' => $articulo,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing articulo entity.
     *
     * @Route("/{id}/edit", name="articulo_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Articulo $articulo)
    {
        $deleteForm = $this->createDeleteForm($articulo);
        $editForm = $this->createForm('AppBundle\Form\ArticuloType', $articulo);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('articulo_edit', array('id' => $articulo->getId()));
        }

        return $this->render('articulo/edit.html.twig', array(
            'articulo' => $articulo,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a articulo entity.
     *
     * @Route("/{id}", name="articulo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Articulo $articulo)
    {
        $form = $this->createDeleteForm($articulo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($articulo);
            $em->flush();
        }

        return $this->redirectToRoute('articulo_index');
    }

    /**
     * Creates a form to delete a articulo entity.
     *
     * @param Articulo $articulo The articulo entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Articulo $articulo)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('articulo_delete', array('id' => $articulo->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

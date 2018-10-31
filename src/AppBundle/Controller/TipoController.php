<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Tipo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Tipo controller.
 *
 * @Route("tipo")
 */
class TipoController extends Controller
{
    /**
     * Lists all tipo entities.
     *
     * @Route("/", name="tipo_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tipos = $em->getRepository('AppBundle:Tipo')->findAll();

        return $this->render('tipo/index.html.twig', array(
            'tipos' => $tipos,
        ));
    }

    /**
     * Creates a new tipo entity.
     *
     * @Route("/new", name="tipo_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $tipo = new Tipo();
        $form = $this->createForm('AppBundle\Form\TipoType', $tipo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tipo);
            $em->flush();

            return $this->redirectToRoute('tipo_show', array('id' => $tipo->getId()));
        }

        return $this->render('tipo/new.html.twig', array(
            'tipo' => $tipo,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a tipo entity.
     *
     * @Route("/{id}", name="tipo_show")
     * @Method("GET")
     */
    public function showAction(Tipo $tipo)
    {
        $deleteForm = $this->createDeleteForm($tipo);

        return $this->render('tipo/show.html.twig', array(
            'tipo' => $tipo,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing tipo entity.
     *
     * @Route("/{id}/edit", name="tipo_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Tipo $tipo)
    {
        $deleteForm = $this->createDeleteForm($tipo);
        $editForm = $this->createForm('AppBundle\Form\TipoType', $tipo);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tipo_edit', array('id' => $tipo->getId()));
        }

        return $this->render('tipo/edit.html.twig', array(
            'tipo' => $tipo,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a tipo entity.
     *
     * @Route("/{id}", name="tipo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Tipo $tipo)
    {
        $form = $this->createDeleteForm($tipo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tipo);
            $em->flush();
        }

        return $this->redirectToRoute('tipo_index');
    }

    /**
     * Creates a form to delete a tipo entity.
     *
     * @param Tipo $tipo The tipo entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Tipo $tipo)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tipo_delete', array('id' => $tipo->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Importe;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Importe controller.
 *
 * @Route("importe")
 */
class ImporteController extends Controller
{
    /**
     * Lists all importe entities.
     *
     * @Route("/", name="importe_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $importes = $em->getRepository('AppBundle:Importe')->findAll();

        return $this->render('importe/index.html.twig', array(
            'importes' => $importes,
        ));
    }

    /**
     * Creates a new importe entity.
     *
     * @Route("/new", name="importe_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $importe = new Importe();
        $form = $this->createForm('AppBundle\Form\ImporteType', $importe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($importe);
            $em->flush();

            return $this->redirectToRoute('importe_show', array('id' => $importe->getId()));
        }

        return $this->render('importe/new.html.twig', array(
            'importe' => $importe,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a importe entity.
     *
     * @Route("/{id}", name="importe_show")
     * @Method("GET")
     */
    public function showAction(Importe $importe)
    {
        $deleteForm = $this->createDeleteForm($importe);

        return $this->render('importe/show.html.twig', array(
            'importe' => $importe,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing importe entity.
     *
     * @Route("/{id}/edit", name="importe_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Importe $importe)
    {
        $deleteForm = $this->createDeleteForm($importe);
        $editForm = $this->createForm('AppBundle\Form\ImporteType', $importe);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('importe_edit', array('id' => $importe->getId()));
        }

        return $this->render('importe/edit.html.twig', array(
            'importe' => $importe,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a importe entity.
     *
     * @Route("/{id}", name="importe_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Importe $importe)
    {
        $form = $this->createDeleteForm($importe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($importe);
            $em->flush();
        }

        return $this->redirectToRoute('importe_index');
    }

    /**
     * Creates a form to delete a importe entity.
     *
     * @param Importe $importe The importe entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Importe $importe)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('importe_delete', array('id' => $importe->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

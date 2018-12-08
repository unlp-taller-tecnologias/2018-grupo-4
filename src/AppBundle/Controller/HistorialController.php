<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Historial;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Historial controller.
 *
 * @Route("historial")
 */
class HistorialController extends Controller
{
    /**
     * Lists all historial entities.
     *
     * @Route("/", name="historial_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $historials = $em->getRepository('AppBundle:Historial')->findAll();

        return $this->render('historial/index.html.twig', array(
            'historials' => $historials,
        ));
    }

    /**
     * Creates a new historial entity.
     *
     * @Route("/new", name="historial_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $historial = new Historial();
        $form = $this->createForm('AppBundle\Form\HistorialType', $historial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($historial);
            $em->flush();

            return $this->redirectToRoute('historial_show', array('id' => $historial->getId()));
        }

        return $this->render('historial/new.html.twig', array(
            'historial' => $historial,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a historial entity.
     *
     * @Route("/{id}", name="historial_show")
     * @Method("GET")
     */
    public function showAction(Historial $historial)
    {
        // $deleteForm = $this->createDeleteForm($historial);

        return $this->render('historial/show.html.twig', array(
            'historial' => $historial,
            // 'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing historial entity.
     *
     * @Route("/{id}/edit", name="historial_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Historial $historial)
    {
        $deleteForm = $this->createDeleteForm($historial);
        $editForm = $this->createForm('AppBundle\Form\HistorialType', $historial);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('historial_edit', array('id' => $historial->getId()));
        }

        return $this->render('historial/edit.html.twig', array(
            'historial' => $historial,
            'edit_form' => $editForm->createView(),
            // 'delete_form' => $deleteForm->createView(),
        ));
    }

    // /**
    //  * Deletes a historial entity.
    //  *
    //  * @Route("/{id}", name="historial_delete")
    //  * @Method("DELETE")
    //  */
    // public function deleteAction(Request $request, Historial $historial)
    // {
    //     $form = $this->createDeleteForm($historial);
    //     $form->handleRequest($request);
    //
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $em = $this->getDoctrine()->getManager();
    //         $em->remove($historial);
    //         $em->flush();
    //     }
    //
    //     return $this->redirectToRoute('historial_index');
    // }

    // /**
    //  * Creates a form to delete a historial entity.
    //  *
    //  * @param Historial $historial The historial entity
    //  *
    //  * @return \Symfony\Component\Form\Form The form
    //  */
    // private function createDeleteForm(Historial $historial)
    // {
    //     return $this->createFormBuilder()
    //         ->setAction($this->generateUrl('historial_delete', array('id' => $historial->getId())))
    //         ->setMethod('DELETE')
    //         ->getForm()
    //     ;
    // }
}

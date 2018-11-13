<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Condicion;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Condicion controller.
 *
 * @Route("condicion")
 */
class CondicionController extends Controller
{
    /**
     * Lists all condicion entities.
     *
     * @Route("/", name="condicion_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $condicions = $em->getRepository('AppBundle:Condicion')->findAll();

        return $this->render('condicion/index.html.twig', array(
            'condicions' => $condicions,
        ));
    }


  



    /**
     * Creates a new condicion entity.
     *
     * @Route("/new", name="condicion_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $condicion = new Condicion();
        $form = $this->createForm('AppBundle\Form\CondicionType', $condicion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($condicion);
            $em->flush();

            return $this->redirectToRoute('condicion_show', array('id' => $condicion->getId()));
        }

        return $this->render('condicion/new.html.twig', array(
            'condicion' => $condicion,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a condicion entity.
     *
     * @Route("/{id}", name="condicion_show")
     * @Method("GET")
     */
    public function showAction(Condicion $condicion)
    {
        $deleteForm = $this->createDeleteForm($condicion);

        return $this->render('condicion/show.html.twig', array(
            'condicion' => $condicion,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing condicion entity.
     *
     * @Route("/{id}/edit", name="condicion_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Condicion $condicion)
    {
        $deleteForm = $this->createDeleteForm($condicion);
        $editForm = $this->createForm('AppBundle\Form\CondicionType', $condicion);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('condicion_edit', array('id' => $condicion->getId()));
        }

        return $this->render('condicion/edit.html.twig', array(
            'condicion' => $condicion,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a condicion entity.
     *
     * @Route("/{id}", name="condicion_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Condicion $condicion)
    {
        $form = $this->createDeleteForm($condicion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($condicion);
            $em->flush();
        }

        return $this->redirectToRoute('condicion_index');
    }

    /**
     * Creates a form to delete a condicion entity.
     *
     * @param Condicion $condicion The condicion entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Condicion $condicion)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('condicion_delete', array('id' => $condicion->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

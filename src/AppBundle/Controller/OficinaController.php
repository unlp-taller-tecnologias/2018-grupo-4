<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Oficina;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Oficina controller.
 *
 * @Route("oficina")
 */
class OficinaController extends Controller
{
    /**
     * Lists all oficina entities.
     *
     * @Route("/", name="oficina_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $oficinas = $em->getRepository('AppBundle:Oficina')->findAll();

        return $this->render('oficina/index.html.twig', array(
            'oficinas' => $oficinas,
        ));
    }

    /**
     * Creates a new oficina entity.
     *
     * @Route("/new", name="oficina_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $oficina = new Oficina();
        $form = $this->createForm('AppBundle\Form\OficinaType', $oficina);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($oficina);
            $em->flush();

            return $this->redirectToRoute('oficina_show', array('id' => $oficina->getId()));
        }

        return $this->render('oficina/new.html.twig', array(
            'oficina' => $oficina,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a oficina entity.
     *
     * @Route("/{id}", name="oficina_show")
     * @Method("GET")
     */
    public function showAction(Oficina $oficina)
    {
        $deleteForm = $this->createDeleteForm($oficina);

        return $this->render('oficina/show.html.twig', array(
            'oficina' => $oficina,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing oficina entity.
     *
     * @Route("/{id}/edit", name="oficina_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Oficina $oficina)
    {
        $deleteForm = $this->createDeleteForm($oficina);
        $editForm = $this->createForm('AppBundle\Form\OficinaType', $oficina);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('oficina_edit', array('id' => $oficina->getId()));
        }

        return $this->render('oficina/edit.html.twig', array(
            'oficina' => $oficina,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a oficina entity.
     *
     * @Route("/{id}", name="oficina_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Oficina $oficina)
    {
        $form = $this->createDeleteForm($oficina);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($oficina);
            $em->flush();
        }

        return $this->redirectToRoute('oficina_index');
    }

    /**
     * Creates a form to delete a oficina entity.
     *
     * @param Oficina $oficina The oficina entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Oficina $oficina)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('oficina_delete', array('id' => $oficina->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

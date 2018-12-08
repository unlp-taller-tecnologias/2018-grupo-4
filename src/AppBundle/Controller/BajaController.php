<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Baja;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Baja controller.
 *
 * @Route("baja")
 */
class BajaController extends Controller
{
    /**
     * Lists all baja entities.
     *
     * @Route("/", name="baja_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $bajas = $em->getRepository('AppBundle:Baja')->findAll();

        return $this->render('baja/index.html.twig', array(
            'bajas' => $bajas,
        ));
    }

    /**
     * Creates a new baja entity.
     *
     * @Route("/new/{id}", name="baja_new")
     * @Method({"GET", "POST"})
     */

    public function newAction(Request $request, $id)
    {
        $baja = new Baja();
        $form = $this->createForm('AppBundle\Form\BajaType', $baja);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($baja);
            $em->flush();

            return $this->redirectToRoute('baja_show', array('id' => $baja->getId()));
        }

        return $this->render('baja/new.html.twig', array(
            'baja' => $baja,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a baja entity.
     *
     * @Route("/{id}", name="baja_show")
     * @Method("GET")
     */
    public function showAction(Baja $baja)
    {
        $deleteForm = $this->createDeleteForm($baja);

        return $this->render('baja/show.html.twig', array(
            'baja' => $baja,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing baja entity.
     *
     * @Route("/{id}/edit", name="baja_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Baja $baja)
    {
        $deleteForm = $this->createDeleteForm($baja);
        $editForm = $this->createForm('AppBundle\Form\BajaType', $baja);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('baja_edit', array('id' => $baja->getId()));
        }

        return $this->render('baja/edit.html.twig', array(
            'baja' => $baja,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a baja entity.
     *
     * @Route("/{id}", name="baja_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Baja $baja)
    {
        $form = $this->createDeleteForm($baja);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($baja);
            $em->flush();
        }

        return $this->redirectToRoute('baja_index');
    }

    /**
     * Creates a form to delete a baja entity.
     *
     * @param Baja $baja The baja entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Baja $baja)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('baja_delete', array('id' => $baja->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

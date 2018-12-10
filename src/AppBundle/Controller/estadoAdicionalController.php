<?php

namespace AppBundle\Controller;

use AppBundle\Entity\estadoAdicional;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * estadoAdicional controller.
 *
 * @Route("estadoAdicional")
 */
class estadoAdicionalController extends Controller
{
    /**
     * Lists all estadoAdicional entities.
     *
     * @Route("/", name="estadoAdicional_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $estados = $em->getRepository('AppBundle:estadoAdicional')->findAll();

        return $this->render('estadoAdicional/index.html.twig', array(
            'estados' => $estados,
        ));
    }
    /**
     * Lists all articulo entities.
     *
     * @Route("/listado", name="estadoAdicional_list")
     * @Method("GET")
     */
    public function listadoAction(Request $request){
      $offset = $request->query->get('offset', 0);
      $limit = $request->query->get('limit', 10);
      $search = $request->query->get('search', null);
      $sort = $request->query->get('sort', null);
      $order = $request->query->get('order', null);

      $em = $this->getDoctrine()->getManager();
      $repository = $em->getRepository('AppBundle:estadoAdicional');
      $estadosQuery = $repository
        ->createQueryBuilder('estadoAdicional');

      if (!is_null($search) && strlen($search) > 0) {
        $estadosQuery
          ->where('estadoAdicional.nombre like :nombre')
          ->setParameter('nombre', '%'.$search.'%');
      }

      if (!is_null($sort) && !is_null($order)) {
        $estadosQuery
          ->orderBy('estadoAdicional.'.$sort, $order);
      }

      $estados = $estadosQuery
        ->setMaxResults($limit)
        ->setFirstResult($offset)
        ->getQuery()
        ->getResult();

      $totalQuery = $repository->createQueryBuilder('estadoAdicional')
        ->select('count(estadoAdicional.id)');
      if (!is_null($search) && strlen($search) > 0) {
        $totalQuery
          ->where('estadoAdicional.nombre like :nombre')
          ->setParameter('nombre', '%'.$search.'%');
      }
      $total = $totalQuery
        ->getQuery()
        ->getSingleScalarResult();

      $rawResponse = array(
        'total' => $total,
        'rows' => array()
      );

      foreach($estados as $estado) {
        $rawResponse['rows'][] = array(
          'id' => $estado->getId(),
          'nombre' => $estado->getNombre(),
          'color' => $estado->getColor(),
          'habilitado' => ($estado->getHabilitado() == 1)?'Si':'No'
        );
      };

      return new JsonResponse($rawResponse);
    }

    /**
     * Creates a new estadoAdicional entity.
     *
     * @Route("/new", name="estadoAdicional_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $estado = new estadoAdicional();
        $form = $this->createForm('AppBundle\Form\estadoAdicionalType', $estado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //$habilitado = $em->getRepository('AppBundle:estadoAdicional')->findOneByHabilitado('1');
            //$estado->setHabilitado($habilitado);
            $em->persist($estado);
            $em->flush();
            return $this->redirectToRoute('estadoAdicional_show', array('id' => $estado->getId()));
        }

        return $this->render('estadoadicional/new.html.twig', array(
            'estado' => $estado,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a estadoAdicional entity.
     *
     * @Route("/{id}", name="estadoAdicional_show")
     * @Method("GET")
     */
    public function showAction(estadoAdicional $estado)
    {
        $deleteForm = $this->createDeleteForm($estado);

        return $this->render('estadoadicional/show.html.twig', array(
            'estado' => $estado,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing estadoAdicional entity.
     *
     * @Route("/{id}/edit", name="estadoAdicional_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, estadoAdicional $estado)
    {
        $deleteForm = $this->createDeleteForm($estado);
        $editForm = $this->createForm('AppBundle\Form\estadoAdicionalType', $estado, array("edit" => true));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('estadoAdicional_edit', array('id' => $estado->getId()));
        }

        return $this->render('estadoadicional/edit.html.twig', array(
            'estado' => $estado,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a estadoAdicional entity.
     *
     * @Route("/{id}", name="estadoAdicional_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, estadoAdicional $estado)
    {
        $form = $this->createDeleteForm($estado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($estado);
            $em->flush();
        }

        return $this->redirectToRoute('estadoAdicional_index');
    }

    /**
     * Creates a form to delete a estadoAdicional entity.
     *
     * @param estadoAdicional $estadoAdicional The estadoAdicional entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(estadoAdicional $estado)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('estadoAdicional_delete', array('id' => $estado->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

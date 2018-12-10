<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Estado;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Estado controller.
 *
 * @Route("estado")
 */
class EstadoController extends Controller
{
    /**
     * Lists all estado entities.
     *
     * @Route("/", name="estado_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $estados = $em->getRepository('AppBundle:Estado')->findAll();
        $editado = $request->query->get('editado');
        return $this->render('estado/index.html.twig', array(
            'estados' => $estados,
            'editado' => $editado,
        ));
    }
    /**
     * Lists all articulo entities.
     *
     * @Route("/listado", name="estado_list")
     * @Method("GET")
     */
    public function listadoAction(Request $request){
      $offset = $request->query->get('offset', 0);
      $limit = $request->query->get('limit', 10);
      $search = $request->query->get('search', null);
      $sort = $request->query->get('sort', null);
      $order = $request->query->get('order', null);

      $em = $this->getDoctrine()->getManager();
      $repository = $em->getRepository('AppBundle:Estado');
      $estadosQuery = $repository
        ->createQueryBuilder('estado');

      if (!is_null($search) && strlen($search) > 0) {
        $estadosQuery
          ->where('estado.nombre like :nombre')
          ->setParameter('nombre', '%'.$search.'%');
      }

      if (!is_null($sort) && !is_null($order)) {
        $estadosQuery
          ->orderBy('estado.'.$sort, $order);
      }

      $estados = $estadosQuery
        ->setMaxResults($limit)
        ->setFirstResult($offset)
        ->getQuery()
        ->getResult();

      $totalQuery = $repository->createQueryBuilder('estado')
        ->select('count(estado.id)');
      if (!is_null($search) && strlen($search) > 0) {
        $totalQuery
          ->where('estado.nombre like :nombre')
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
     * Creates a new estado entity.
     *
     * @Route("/new", name="estado_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $estado = new Estado();
        $form = $this->createForm('AppBundle\Form\EstadoType', $estado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $habilitado = $em->getRepository('AppBundle:Estado')->findOneByHabilitado('1');
            $estado->setHabilitado($habilitado);
            $em->persist($estado);
            $em->flush();

            return $this->redirectToRoute('estado_index', array('editado' => 'editado'));
        }

        return $this->render('estado/new.html.twig', array(
            'estado' => $estado,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a estado entity.
     *
     * @Route("/{id}", name="estado_show")
     * @Method("GET")
     */
    public function showAction(Estado $estado)
    {
        // $deleteForm = $this->createDeleteForm($estado);

        return $this->render('estado/show.html.twig', array(
            'estado' => $estado,
            // 'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing estado entity.
     *
     * @Route("/{id}/edit", name="estado_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Estado $estado)
    {
        // $deleteForm = $this->createDeleteForm($estado);
        $editForm = $this->createForm('AppBundle\Form\EstadoType', $estado, array("edit" => false));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('estado_index', array('editado' => 'editado'));
        }

        return $this->render('estado/edit.html.twig', array(
            'estado' => $estado,
            'edit_form' => $editForm->createView(),
            // 'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing estado entity.
     *
     * @Route("/{id}/visibility", name="estado_visibility")
     * @Method({"GET", "POST"})
     */
    public function visibilityAction(Request $request, Estado $estado)
    {
        $editForm = $this->createForm('AppBundle\Form\EstadoType', $estado, array("visibility" => false));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('estado_index', array('editado' => 'editado'));
        }

        return $this->render('estado/edit.html.twig', array(
            'estado' => $estado,
            'edit_form' => $editForm->createView(),
        ));
    }


    // /**
    //  * Deletes a estado entity.
    //  *
    //  * @Route("/{id}", name="estado_delete")
    //  * @Method("DELETE")
    //  */
    // public function deleteAction(Request $request, Estado $estado)
    // {
    //     $form = $this->createDeleteForm($estado);
    //     $form->handleRequest($request);
    //
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $em = $this->getDoctrine()->getManager();
    //         $em->remove($estado);
    //         $em->flush();
    //     }
    //
    //     return $this->redirectToRoute('estado_index');
    // }
    //
    // /**
    //  * Creates a form to delete a estado entity.
    //  *
    //  * @param Estado $estado The estado entity
    //  *
    //  * @return \Symfony\Component\Form\Form The form
    //  */
    // private function createDeleteForm(Estado $estado)
    // {
    //     return $this->createFormBuilder()
    //         ->setAction($this->generateUrl('estado_delete', array('id' => $estado->getId())))
    //         ->setMethod('DELETE')
    //         ->getForm()
    //     ;
    // }
}

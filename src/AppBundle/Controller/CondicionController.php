<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Condicion;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

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
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $condicions = $em->getRepository('AppBundle:Condicion')->findAll();
        $editado = $request->query->get('editado');
        return $this->render('condicion/index.html.twig', array(
            'condicions' => $condicions,
            'editado' => $editado
        ));
    }

    /**
     * Lists all articulo entities.
     *
     * @Route("/listado", name="condicion_list")
     * @Method("GET")
     */
    public function listadoAction(Request $request){
      $offset = $request->query->get('offset', 0);
      $limit = $request->query->get('limit', 10);
      $search = $request->query->get('search', null);
      $sort = $request->query->get('sort', null);
      $order = $request->query->get('order', null);

      $em = $this->getDoctrine()->getManager();
      $repository = $em->getRepository('AppBundle:Condicion');
      $condicionesQuery = $repository
        ->createQueryBuilder('condicion');

      if (!is_null($search) && strlen($search) > 0) {
        $condicionesQuery
          ->where('condicion.nombre like :nombre')
          ->setParameter('nombre', '%'.$search.'%');
      }

      if (!is_null($sort) && !is_null($order)) {
        $condicionesQuery
          ->orderBy('condicion.'.$sort, $order);
      }

      $condiciones = $condicionesQuery
        ->setMaxResults($limit)
        ->setFirstResult($offset)
        ->getQuery()
        ->getResult();

      $totalQuery = $repository->createQueryBuilder('condicion')
        ->select('count(condicion.id)');
      if (!is_null($search) && strlen($search) > 0) {
        $totalQuery
          ->where('condicion.nombre like :nombre')
          ->setParameter('nombre', '%'.$search.'%');
      }
      $total = $totalQuery
        ->getQuery()
        ->getSingleScalarResult();

      $rawResponse = array(
        'total' => $total,
        'rows' => array()
      );

      foreach($condiciones as $condicion) {
        $rawResponse['rows'][] = array(
          'id' => $condicion->getId(),
          'nombre' => $condicion->getNombre(),
          'habilitado' => ($condicion->getHabilitado() == 1)?'Si':'No',
          'descripcion' => $condicion->getDescripcion(),
        );
      };

      return new JsonResponse($rawResponse);
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
            $habilitado = $em->getRepository('AppBundle:Condicion')->findOneByHabilitado('1');
            $condicion->setHabilitado($habilitado);
            $em->persist($condicion);
            $em->flush();

            return $this->redirectToRoute('condicion_index', array('editado' => 'editado' ));
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
        // $deleteForm = $this->createDeleteForm($condicion);

        return $this->render('condicion/show.html.twig', array(
            'condicion' => $condicion,
            // 'delete_form' => $deleteForm->createView(),
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
        // $deleteForm = $this->createDeleteForm($condicion);
        $editForm = $this->createForm('AppBundle\Form\CondicionType', $condicion, array("edit" => false));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            // return $this->redirectToRoute('condicion_edit', array('id' => $condicion->getId()));
            return $this->redirectToRoute('condicion_index',array('editado' => 'editado' ));
        }

        return $this->render('condicion/edit.html.twig', array(
            'condicion' => $condicion,
            'edit_form' => $editForm->createView(),
            // 'delete_form' => $deleteForm->createView(),
        ));
    }

    // /**
    //  * Deletes a condicion entity.
    //  *
    //  * @Route("/{id}", name="condicion_delete")
    //  * @Method("DELETE")
    //  */
    // public function deleteAction(Request $request, Condicion $condicion)
    // {
    //     $form = $this->createDeleteForm($condicion);
    //     $form->handleRequest($request);
    //
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $em = $this->getDoctrine()->getManager();
    //         $em->remove($condicion);
    //         $em->flush();
    //     }
    //
    //     return $this->redirectToRoute('condicion_index');
    // }

    // /**
    //  * Creates a form to delete a condicion entity.
    //  *
    //  * @param Condicion $condicion The condicion entity
    //  *
    //  * @return \Symfony\Component\Form\Form The form
    //  */
    // private function createDeleteForm(Condicion $condicion)
    // {
    //     return $this->createFormBuilder()
    //         ->setAction($this->generateUrl('condicion_delete', array('id' => $condicion->getId())))
    //         ->setMethod('DELETE')
    //         ->getForm()
    //     ;
    // }
}

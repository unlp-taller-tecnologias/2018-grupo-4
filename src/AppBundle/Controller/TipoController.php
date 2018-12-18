<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Tipo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

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
    public function indexAction (Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $tipos = $em->getRepository('AppBundle:Tipo')->findAll();
        $editado = $request->query->get('editado');
        return $this->render('tipo/index.html.twig', array(
            'tipos' => $tipos,
            'editado' => $editado,
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
            $habilitado = $em->getRepository('AppBundle:Tipo')->findOneByHabilitado('1');
            $tipo->setHabilitado($habilitado);
            $em->persist($tipo);
            $em->flush();

            return $this->redirectToRoute('tipo_index', array('editado' => 'editado'));
        }

        return $this->render('tipo/new.html.twig', array(
            'tipo' => $tipo,
            'form' => $form->createView(),
        ));
    }

    /**
     * Lists all tipo entities.
     *
     * @Route("/listado", name="tipo_list")
     * @Method("GET")
     */
    public function listadoAction(Request $request){
      $offset = $request->query->get('offset', 0);
      $limit = $request->query->get('limit', 10);
      $search = $request->query->get('search', null);
      $sort = $request->query->get('sort', null);
      $order = $request->query->get('order', null);

      $em = $this->getDoctrine()->getManager();
      $repository = $em->getRepository('AppBundle:Tipo');
      $tiposQuery = $repository
        ->createQueryBuilder('tipo');

      if (!is_null($search) && strlen($search) > 0) {
        $tiposQuery
          ->where('tipo.concepto like :concepto')
          ->setParameter('concepto', '%'.$search.'%');
      }

      if (!is_null($sort) && !is_null($order)) {
        $tiposQuery
          ->orderBy('tipo.'.$sort, $order);
      }

      $tipos = $tiposQuery
        ->setMaxResults($limit)
        ->setFirstResult($offset)
        ->getQuery()
        ->getResult();

      $totalQuery = $repository->createQueryBuilder('tipo')
        ->select('count(tipo.id)');
      if (!is_null($search) && strlen($search) > 0) {
        $totalQuery
          ->where('tipo.concepto like :concepto')
          ->setParameter('concepto', '%'.$search.'%');
      }
      $total = $totalQuery
        ->getQuery()
        ->getSingleScalarResult();

      $rawResponse = array(
        'total' => $total,
        'rows' => array()
      );

      foreach($tipos as $tipo) {
        $rawResponse['rows'][] = array(
          'id' => $tipo->getId(),
          'codigo' => $tipo->getCodigo(),
          'concepto' => $tipo->getConcepto(),
          'habilitado' => ($tipo->getHabilitado() == 1)?'Si':'No'
        );
      };

      return new JsonResponse($rawResponse);
    }

    /**
     * Finds and displays a tipo entity.
     *
     * @Route("/{id}", name="tipo_show")
     * @Method("GET")
     */
    public function showAction(Tipo $tipo)
    {
        // $deleteForm = $this->createDeleteForm($tipo);

        return $this->render('tipo/show.html.twig', array(
            'tipo' => $tipo,
            // 'delete_form' => $deleteForm->createView(),
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
        // $deleteForm = $this->createDeleteForm($tipo);
        $editForm = $this->createForm('AppBundle\Form\TipoType', $tipo, array("edit" => false));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tipo_index', array('editado' => 'editado'));
        }

        return $this->render('tipo/edit.html.twig', array(
            'tipo' => $tipo,
            'edit_form' => $editForm->createView(),
            'editado' => '',
        ));
    }

    /**
     * Displays a form to edit an existing tipo entity.
     *
     * @Route("/{id}/visibility", name="tipo_visibility")
     * @Method({"GET", "POST"})
     */
    public function visibilityAction(Request $request, Tipo $tipo)
    {
        $editForm = $this->createForm('AppBundle\Form\TipoType', $tipo, array("visibility" => false));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tipo_index', array('editado' => 'editado'));
        }

        return $this->render('tipo/edit.html.twig', array(
            'tipo' => $tipo,
            'edit_form' => $editForm->createView(),
        ));
    }

    // /**
    //  * Deletes a tipo entity.
    //  *
    //  * @Route("/{id}", name="tipo_delete")
    //  * @Method("DELETE")
    //  */
    // public function deleteAction(Request $request, Tipo $tipo)
    // {
    //     $form = $this->createDeleteForm($tipo);
    //     $form->handleRequest($request);
    //
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $em = $this->getDoctrine()->getManager();
    //         $em->remove($tipo);
    //         $em->flush();
    //     }
    //
    //     return $this->redirectToRoute('tipo_index');
    // }
    //
    // /**
    //  * Creates a form to delete a tipo entity.
    //  *
    //  * @param Tipo $tipo The tipo entity
    //  *
    //  * @return \Symfony\Component\Form\Form The form
    //  */
    // private function createDeleteForm(Tipo $tipo)
    // {
    //     return $this->createFormBuilder()
    //         ->setAction($this->generateUrl('tipo_delete', array('id' => $tipo->getId())))
    //         ->setMethod('DELETE')
    //         ->getForm()
    //     ;
    // }
}

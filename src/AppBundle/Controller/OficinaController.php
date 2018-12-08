<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Oficina;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

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
     * Lists all oficinas entities.
     *
     * @Route("/listado", name="oficina_list")
     * @Method("GET")
     */
    public function listadoAction(Request $request){
      $offset = $request->query->get('offset', 0);
      $limit = $request->query->get('limit', 10);
      $search = $request->query->get('search', null);
      $sort = $request->query->get('sort', null);
      $order = $request->query->get('order', null);

      $em = $this->getDoctrine()->getManager();
      $repository = $em->getRepository('AppBundle:Oficina');
      $oficinasQuery = $repository
        ->createQueryBuilder('oficina');

      if (!is_null($search) && strlen($search) > 0) {
        $oficinasQuery
          ->where('oficina.nombre like :nombre')
          ->setParameter('nombre', '%'.$search.'%');
      }

      if (!is_null($sort) && !is_null($order)) {
        $oficinasQuery
          ->orderBy('oficina.'.$sort, $order);
      }

      $oficinas = $oficinasQuery
        ->setMaxResults($limit)
        ->setFirstResult($offset)
        ->getQuery()
        ->getResult();

      $totalQuery = $repository->createQueryBuilder('oficina')
        ->select('count(oficina.id)');
      if (!is_null($search) && strlen($search) > 0) {
        $totalQuery
          ->where('oficina.nombre like :nombre')
          ->setParameter('nombre', '%'.$search.'%');
      }
      $total = $totalQuery
        ->getQuery()
        ->getSingleScalarResult();

      $rawResponse = array(
        'total' => $total,
        'rows' => array()
      );

      foreach($oficinas as $oficina) {
        $rawResponse['rows'][] = array(
          'id' => $oficina->getId(),
          'nombre' => $oficina->getNombre(),
          'numeroCarpeta' => $oficina->getNumeroCarpeta(),
          'responsableOficina' => $oficina->getResponsableOficina()
        );
      };

      return new JsonResponse($rawResponse);
    }

    /**
     * Lists all oficinas entities.
     *
     * @Route("/oficina_listFilter", name="oficina_listFilter")
     * @Method({"GET", "POST"})
     */
    public function listadoActionFilter(Request $request){
      $offset = $request->query->get('offset', 0);
      $limit = $request->query->get('limit', 10);
      $search = $request->query->get('search', null);
      $sort = $request->query->get('sort', null);
      $order = $request->query->get('order', null);

      $nombre = $request->request->get('nombre');
      $nroCarpeta = $request->request->get('nroCarpeta');
      $responsable = $request->request->get('responsable');

      $nombre = ($nombre == "")? NULL:"%".$nombre."%";
      $nroCarpeta = ($nroCarpeta == "")? NULL:$nroCarpeta;
      $responsable = ($responsable == "")? NULL:"%".$responsable."%";


      $em = $this->getDoctrine()->getEntityManager();
      $dql = "select a from AppBundle:Oficina a where (((a.nombre like :nombre and :nombre is not null) or (:nombre is null))
              and ((a.numeroCarpeta = :numeroCarpeta and :numeroCarpeta is not null) or (:numeroCarpeta is null))
              and ((a.responsableOficina like :responsableOficina and :responsableOficina is not null) or (:responsableOficina is null)))
              or (:nombre is null and :numeroCarpeta is null and :responsableOficina is null) order by a.nombre asc";
      $query = $em->createQuery($dql);

      $query->setParameter('nombre', $nombre);
      $query->setParameter('numeroCarpeta', $nroCarpeta);
      $query->setParameter('responsableOficina', $responsable);

      $oficinas = $query->getResult();

      $total = count($oficinas);


      $rawResponse = array(
        'total' => $total,
        'rows' => array()
      );

      foreach($oficinas as $oficina) {
        $rawResponse['rows'][] = array(
          'id' => $oficina->getId(),
          'nombre' => $oficina->getNombre(),
          'numeroCarpeta' => $oficina->getNumeroCarpeta(),
          'responsableOficina' => $oficina->getResponsableOficina()
        );
      };

      return new JsonResponse($rawResponse);
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

            // return $this->redirectToRoute('oficina_show', array('id' => $oficina->getId()));
            return $this->redirectToRoute('oficina_index');
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
     * Lists all articulo entities.
     *
     * @Route("/{oficina}/articulos/listado", name="oficina_show_listado")
     * @Method("GET")
     */
    public function showListadoAction(Request $request, Oficina $oficina) {
      $offset = $request->query->get('offset', 0);
      $limit = $request->query->get('limit', 10);
      $search = $request->query->get('search', null);
      $sort = $request->query->get('sort', null);
      $order = $request->query->get('order', null);

      $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Articulo');
      $articulos = $repository->getBy($offset, $limit, $sort, $order, $search, $oficina);
      $total = $repository->countBy($search, $oficina);

      $rawResponse = array(
        'total' => $total,
        'rows' => array()
      );

      foreach($articulos as $articulo) {
        $rawResponse['rows'][] = array(
          'id' => $articulo->getId(),
          'numInventario' => $articulo->getNumInventario(),
          'numExpendiente' => $articulo->getNumExpediente(),
          'denominacion' => $articulo->getDenominacion(),
          'tipo' => ($articulo->getTipo()) ? $articulo->getTipo()->getDescripcion() : null,
          'estado' => $articulo->getEstado()->getNombre()
        );
      };

      return new JsonResponse($rawResponse);
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

            // return $this->redirectToRoute('oficina_edit', array('id' => $oficina->getId()));
              return $this->redirectToRoute('oficina_index');
        }

        return $this->render('oficina/edit.html.twig', array(
            'oficina' => $oficina,
            'edit_form' => $editForm->createView(),
            // 'delete_form' => $deleteForm->createView(),
        ));
    }

    // /**
    //  * Deletes a oficina entity.
    //  *
    //  * @Route("/{id}/delete", name="oficina_delete")
    //  * @Method("DELETE")
    //  */
    // public function deleteAction(Request $request, Oficina $oficina)
    // {
    //     $form = $this->createDeleteForm($oficina);
    //     $form->handleRequest($request);
    //
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $em = $this->getDoctrine()->getManager();
    //         $em->remove($oficina);
    //         $em->flush();
    //     }
    //
    //     return $this->redirectToRoute('oficina_index');
    // }

    // /**
    //  * Creates a form to delete a oficina entity.
    //  *
    //  * @param Oficina $oficina The oficina entity
    //  *
    //  * @return \Symfony\Component\Form\Form The form
    //  */
    // private function createDeleteForm(Oficina $oficina)
    // {
    //     return $this->createFormBuilder()
    //         ->setAction($this->generateUrl('oficina_delete', array('id' => $oficina->getId())))
    //         ->setMethod('DELETE')
    //         ->getForm()
    //     ;
    // }
}

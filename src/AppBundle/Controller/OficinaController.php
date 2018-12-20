<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Oficina;
use AppBundle\Entity\Articulo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $oficinas = $em->getRepository('AppBundle:Oficina')->findAll();
        $editado = $request->query->get('editado');
        return $this->render('oficina/index.html.twig', array(
            'oficinas' => $oficinas,
            'editado' => $editado,
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
      $sort = $request->query->get('sort', 'nombre');
      $order = $request->query->get('order', 'asc');

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
      $sort = $request->query->get('sort', 'nombre');
      $order = $request->query->get('order', 'asc');

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
     * @Security("is_granted('ROLE_ADMIN')")
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

            return $this->redirectToRoute('oficina_index', array('editado' => 'editado'));
        }

        return $this->render('oficina/new.html.twig', array(
            'oficina' => $oficina,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a oficina entity.
     * @Route("/{id}", name="oficina_show")
     * @Method("GET")
     */
    public function showAction(Request $request, Oficina $oficina)
    {
        $deleteForm = $this->createDeleteForm($oficina);

        $tipo="";
        $em = $this->getDoctrine()->getManager();
        $transferenciaRepository = $em->getRepository('AppBundle:Transferencia');
        $existenTransferenciasPendientes = $transferenciaRepository->findBy(
          array('finalizada' => 2,
          'oficinaOrigen' => $oficina)
        );
        if ($existenTransferenciasPendientes == null) {
          $bajaRepository = $em->getRepository('AppBundle:Baja');
          $existenBajasPendientes = $bajaRepository->findBy(
            array('finalizada' => 2,
            'oficina' => $oficina)
          );
          if ($existenBajasPendientes == null) {
            $operacionesPendientes = false;
          }else{
            $operacionesPendientes = true;
            $tipo = 'baja';
          }
        }else{
          $operacionesPendientes = true;
          $tipo = 'transferencia';
        }



        $editado = $request->query->get('editado');

        return $this->render('oficina/show.html.twig', array(
            'operaciones' => $operacionesPendientes,
            'oficina' => $oficina,
            'tipo' =>$tipo,
            'delete_form' => $deleteForm->createView(),
            'editado' => $editado,
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
      $sort = $request->query->get('sort', 'denominacion');
      $order = $request->query->get('order', 'asc');

      $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Articulo');
      $condicionInicial = null;
      $articulos = $repository->getBy($offset, $limit, $sort, $order, $search, $oficina);
      $total = $repository->countBy($search, $oficina);


      $rawResponse = array(
        'total' => $total,
        'rows' => array()
      );



      foreach($articulos as $articulo) {
        $condiciones = array();
        $historialesCollection = $articulo->getHistoriales();
        if (!($historialesCollection->isEmpty())) {
            foreach ($historialesCollection as $h) {
              if ($h->getTransferencia() != null) {
                $condiciones[] = $h->getCondicion()->getNombre();
              }
            }

        }else{
          if ($articulo->getCondicion() != null) {
            $condicionInicial = $articulo->getCondicion()->getNombre();
          }else{
            $condicionInicial = null;
          }

        }
        $condicion = end($condiciones);
        $rawResponse['rows'][] = array(
          'id' => $articulo->getId(),
          'numInventario' => $articulo->getNumInventario(),
          'numExpendiente' => $articulo->getNumExpediente(),
          'denominacion' => $articulo->getDenominacion(),
          'oficina' => $articulo->getOficina()->getNombre(),
          'tipo' => ($articulo->getTipo()) ? $articulo->getTipo()->getDescripcion() : null,
          'estado' => $articulo->getEstado()->getNombre(),
          'estadoAdicional' =>  ($articulo->getEstadoAdicional()) ? $articulo->getEstadoAdicional()->getNombre() : null,
          'condicion' => ($condicion) ? $condicion : $condicionInicial,
          'material' =>  ($articulo->getMaterial()) ? $articulo->getMaterial() : null,
          'marca' =>  ($articulo->getMarca()) ? $articulo->getMarca() : null,
          'numFabrica' =>  ($articulo->getNumFabrica()) ? $articulo->getNumFabrica() : null,
          'largo' =>  ($articulo->getLargo()) ? $articulo->getLargo() : null,
          'ancho' =>  ($articulo->getAncho()) ? $articulo->getAncho() : null,
          'alto' =>  ($articulo->getAlto()) ? $articulo->getAlto() : null,
          'estantes' =>  ($articulo->getNumsEstantes()) ? $articulo->getNumsEstantes() : null,
          'cajones' =>  ($articulo->getNumsCajones()) ? $articulo->getNumsCajones() : null,
          'detalleOrigen' =>  ($articulo->getDetalleOrigen()) ? $articulo->getDetalleOrigen() : null,
          'importe' =>  ($articulo->getImporte()) ? $articulo->getImporte() : null,
          'fechaEntrada' => $articulo->getFechaEntrada()->format('d-m-Y'),
          'codigoCuentaSubcuenta' =>  ($articulo->getCodigoCuentaSubcuenta()) ? $articulo->getCodigoCuentaSubcuenta() : null,
        );
      };

      return new JsonResponse($rawResponse);
    }




    /**
     * Lists all articulo entities.
     *
     * @Route("/{oficina}/articulos/listadoActivos", name="oficina_show_listadoActivos")
     * @Method("GET")
     */
    public function showListadoActivosAction(Request $request, Oficina $oficina) {
      $offset = $request->query->get('offset', 0);
      $limit = $request->query->get('limit', 10);
      $search = $request->query->get('search', null);
      $sort = $request->query->get('sort', 'denominacion');
      $order = $request->query->get('order', 'asc');

      $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Articulo');
      $articulos = $repository->getBy($offset, $limit, $sort, $order, $search, $oficina);
      $total = $repository->countBy($search, $oficina);

     $transferenciaRepository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Transferencia');
     $historialRepository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Historial');
      $transferencia = $transferenciaRepository->findOneBy(
            array('finalizada' => 2,
            'oficinaOrigen' => $oficina)
        );


      $activos = array();
      foreach ($articulos as $a) {
        if ($a->getEstado()->getNombre() == 'Activo') {
          $activos[] = $a;
        }
      }

      $rawResponse = array(
        'total' => $total,
        'rows' => array()
      );

      foreach($activos as $articulo) {
        $rawResponse['rows'][] = array(
          'id' => $articulo->getId(),
          'numInventario' => $articulo->getNumInventario(),
          'numExpendiente' => $articulo->getNumExpediente(),
          'denominacion' => $articulo->getDenominacion(),
          'tipo' => ($articulo->getTipo()) ? $articulo->getTipo()->getDescripcion() : null,
          'estado' => $articulo->getEstado()->getNombre(),
          'estadoAdicional' =>  ($articulo->getEstadoAdicional()) ? $articulo->getEstadoAdicional()->getNombre() : null
        );
      };

      return new JsonResponse($rawResponse);
    }
    /**
     * Displays a form to edit an existing oficina entity.
     *
     * @Route("/{id}/edit", name="oficina_edit")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Oficina $oficina)
    {
        $deleteForm = $this->createDeleteForm($oficina);
        $editForm = $this->createForm('AppBundle\Form\OficinaType', $oficina, array("edit" => false));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('oficina_index', array('editado' => 'editado'));
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
     * @Route("/{id}/delete", name="oficina_delete")
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

        return $this->redirectToRoute('oficina_index', array('editado' => 'editado'));
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


    /**
     * Lists all articulo entities.
     *
     * @Route("/{oficina}/articulo_listFilter_oficinas", name="articulo_listFilter_oficinas", defaults={"oficina"=null})
     * @Method({"GET", "POST"})
     */
    public function listadoFilterOficinaAction(Request $request,Oficina $oficina){
      $offset = $request->query->get('offset', 0);
      $limit = $request->query->get('limit', 10);
      $search = $request->query->get('search', null);
      $sort = $request->query->get('sort', 'denominacion');
      $order = $request->query->get('order', 'asc');

      $nroInventario = $request->request->get('nroInventario');
      $numExpediente = $request->request->get('expediente');
      $denominacion = $request->request->get('denominacion');
      $estado = $request->request->get('estado');
      $tipo = $request->request->get('tipo');
      $oficinaId = $request->query->get('idOficina');
      $estadoAdicional = $request->request->get('estadoAdicional');

      $nroInventario = ($nroInventario == "")? NULL:$nroInventario;
      $numExpediente = ($numExpediente == "")? NULL:$numExpediente;
      $denominacion = ($denominacion == "")? NULL:$denominacion."%";
      $estado = ($estado == "")? NULL:$estado;
      $tipo = ($tipo == "")? NULL:$tipo;
      $estadoAdicional = ($estadoAdicional == "")? NULL:$estadoAdicional;

      $em = $this->getDoctrine()->getEntityManager();
      $dql = "select a from AppBundle:Articulo a where (((a.numInventario = :nroInventario and :nroInventario is not null) or (:nroInventario is null))
              and ((a.numExpediente = :numExpediente and :numExpediente is not null) or (:numExpediente is null))
              and ((a.denominacion like :denominacion and :denominacion is not null) or (:denominacion is null))
              and ((a.estado = :estado and :estado is not null) or (:estado is null))
              and ((a.estadoAdicional = :estadoAdicional and :estadoAdicional is not null) or (:estadoAdicional is null))
              and ((a.tipo = :tipo and :tipo is not null) or (:tipo is null)))
              or (:nroInventario is null and :numExpediente is null and :denominacion is null and :estado is null and :estadoAdicional is null and :tipo is null)";
      $query = $em->createQuery($dql);
      $query->setParameter('nroInventario', $nroInventario);
      $query->setParameter('numExpediente', $numExpediente);
      $query->setParameter('denominacion', $denominacion);
      $query->setParameter('estado', $estado);
      $query->setParameter('tipo', $tipo);
      $query->setParameter('estadoAdicional', $estadoAdicional);
      $articulos = $query->getResult();

      $rawResponse = array(
        'total' => 0,
        'rows' => array()
      );

      foreach($articulos as $articulo) {
        $of = $articulo->getOficina();
        if ($of->getId() ==  $oficina->getId()) {
          $rawResponse['rows'][] = array(
            'id' => $articulo->getId(),
            'numInventario' =>$articulo->getNumInventario(),
            'numExpendiente' => $articulo->getNumExpediente(),
            'denominacion' => $articulo->getDenominacion(),
            'tipo' => ($articulo->getTipo()) ? $articulo->getTipo()->getDescripcion() : null,
            'estado' => $articulo->getEstado()->getNombre(),
            'estadoAdicional' => ($articulo->getEstadoAdicional()) ? $articulo->getEstadoAdicional()->getNombre() : null
          );
        }
      };
      $rawResponse['total'] = count($rawResponse['rows']);
      return new JsonResponse($rawResponse);
    }
}

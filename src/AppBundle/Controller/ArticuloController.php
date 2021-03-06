<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Articulo;
use AppBundle\Entity\Oficina;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use Ps\PdfBundle\Annotation\Pdf;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;



/**
 * Articulo controller.
 *
 * @Route("articulo")
 */
class ArticuloController extends Controller
{
    /**
     * Lists all articulo entities.
     *
     * @Route("/", name="articulo_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
      // var_dump($this->getUser()->getUsername());
      //
      // if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
      //   var_dump("OK HACE ESTO COMO ADMIN");
      // } else {
      //   var_dump("HACE ESTO COMO OTRO USUARIO");
      // }

      //$user = $this->getUser();

      #$this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();

        //$user->addRole("ROLE_ADMIN");
        $em->flush();

        $articulos = $em->getRepository('AppBundle:Articulo')->findAll();
        $editado = $request->query->get('editado');
        $mensaje = $request->query->get('mensaje');
        return $this->render('articulo/index.html.twig', array(
            'articulos' => $articulos,
            'editado' => $editado,
            'mensaje' => $mensaje
        ));
    }

    /**
     * Finds and displays a articulo entity.
     *
     * @Route("/ver/{id}", name="articulo_show")
     * @Method("GET")
     */
    public function showAction(Request $request, Articulo $articulo)
    {
        // $deleteForm = $this->createDeleteForm($articulo);
        $editado = $request->query->get('editado');
        $mensaje = $request->query->get('mensaje');
        return $this->render('articulo/show.html.twig', array(
            'articulo' => $articulo,
            // 'delete_form' => $deleteForm->createView(),
            'editado' => $editado,
            'mensaje' => $mensaje
        ));
    }

    /**
     * Lists all articulo entities.
     *
       * @Route("/listado", name="articulo_list")
     * @Method({"GET"})
     */
    public function listadoAction(Request $request){
      $offset = $request->query->get('offset', 0);
      $limit = $request->query->get('limit', 10);
      $search = $request->query->get('search', null);
      $sort = $request->query->get('sort', null);
      $order = $request->query->get('order', null);

      $nroInventario = $request->query->get('nroInventario');
      $numExpediente = $request->query->get('expediente');
      $denominacion = $request->query->get('denominacion');
      $estado = $request->query->get('estado');
      $tipo = $request->query->get('tipo');
      $estadoAdicional = $request->query->get('estadoAdicional');

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
      $query->setMaxResults($limit)
      ->setFirstResult($offset);
      $articulos = $query->getResult();

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
      $total = count($query->getResult());

      $rawResponse = array(
        'total' => $total,
        'rows' => array()
      );

      $condicionInicial = null;
      foreach($articulos as $articulo) {
        $condiciones = array();
        $historialesCollection = $articulo->getHistoriales();
        if (!($historialesCollection->isEmpty())) {
            foreach ($historialesCollection as $h) {
              if ($h->getTransferencia() != null) {
                $condiciones[] = ($h->getCondicion() != null)? $h->getCondicion()->getNombre():null;
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
          'numExpediente' => $articulo->getNumExpediente(),
          'denominacion' => $articulo->getDenominacion(),
          'oficina' => $articulo->getOficina()->getNombre(),
          'tipo' => (!is_null($articulo->getTipo())) ? $articulo->getTipo()->getConcepto() : null,
          'estado' => $articulo->getEstado()->getNombre(),
          'estadoAdicional' =>  (!is_null($articulo->getEstadoAdicional())) ? $articulo->getEstadoAdicional()->getNombre() : null,
          'condicion' => ($condicion) ? $condicion : $condicionInicial,
          'material' =>  (!is_null($articulo->getMaterial())) ? $articulo->getMaterial() : null,
          'marca' =>  (!is_null($articulo->getMarca())) ? $articulo->getMarca() : null,
          'numFabrica' =>  (!is_null($articulo->getNumFabrica())) ? $articulo->getNumFabrica() : null,
          'largo' =>  (!is_null($articulo->getLargo())) ? $articulo->getLargo() : null,
          'ancho' =>  (!is_null($articulo->getAncho())) ? $articulo->getAncho() : null,
          'alto' =>  (!is_null($articulo->getAlto())) ? $articulo->getAlto() : null,
          'estantes' =>  (!is_null($articulo->getNumsEstantes())) ? $articulo->getNumsEstantes() : null,
          'cajones' =>  (!is_null($articulo->getNumsCajones())) ? $articulo->getNumsCajones() : null,
          'detalleOrigen' =>  (!is_null($articulo->getDetalleOrigen())) ? $articulo->getDetalleOrigen() : null,
          'importe' =>  (!is_null($articulo->getImporte())) ? $articulo->getImporte() : null,
          'fechaEntrada' => $articulo->getFechaEntrada()->format('d-m-Y'),
          'codigoCuentaSubcuenta' =>  (!is_null($articulo->getCodigoCuentaSubcuenta())) ? $articulo->getCodigoCuentaSubcuenta() : null,
        );
      };
      return new JsonResponse($rawResponse);
    }

    /**
     * Lists all articulo entities.
     *
     * @Route("/articulo_listFilter", name="articulo_listFilter", defaults={"oficina"=null})
     * @Method({"GET", "POST"})
     */
    public function listadoActionFilter(Request $request){
      $offset = $request->query->get('offset', 0);
      $limit = $request->query->get('limit', 20);
      $search = $request->query->get('search', null);
      $sort = $request->query->get('sort', null);
      $order = $request->query->get('order', null);

      $nroInventario = $request->request->get('nroInventario');
      $numExpediente = $request->request->get('expediente');
      $denominacion = $request->request->get('denominacion');
      $estado = $request->request->get('estado');
      $tipo = $request->request->get('tipo');
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

      $total = count($articulos);

      $rawResponse = array(
        'total' => $total,
        'rows' => array()
      );

      $condicionInicial = null;
      foreach($articulos as $articulo) {
        $condiciones = array();
        $historialesCollection = $articulo->getHistoriales();
        if (!($historialesCollection->isEmpty())) {
            foreach ($historialesCollection as $h) {
              if ($h->getTransferencia() != null) {
                $condiciones[] = ($h->getCondicion() != null)? $h->getCondicion()->getNombre():null;
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
          'numExpediente' => (!is_null($articulo->getNumExpediente())) ? $articulo->getNumExpediente() : "-",
          'denominacion' => $articulo->getDenominacion(),
          'oficina' => $articulo->getOficina()->getNombre(),
          'tipo' => (!is_null($articulo->getTipo())) ? $articulo->getTipo()->getConcepto() : null,
          'estado' => $articulo->getEstado()->getNombre(),
          'estadoAdicional' =>  ($articulo->getEstadoAdicional()) ? $articulo->getEstadoAdicional()->getNombre() : null,
          'condicion' => ($condicion) ? $condicion : $condicionInicial,
          'material' =>  (!is_null($articulo->getMaterial())) ? $articulo->getMaterial() : null,
          'marca' =>  (!is_null($articulo->getMarca())) ? $articulo->getMarca() : null,
          'numFabrica' =>  (!is_null($articulo->getNumFabrica())) ? $articulo->getNumFabrica() : null,
          'largo' =>  (!is_null($articulo->getLargo())) ? $articulo->getLargo() : null,
          'ancho' =>  (!is_null($articulo->getAncho())) ? $articulo->getAncho() : null,
          'alto' =>  (!is_null($articulo->getAlto())) ? $articulo->getAlto() : null,
          'estantes' =>  (!is_null($articulo->getNumsEstantes())) ? $articulo->getNumsEstantes() : null,
          'cajones' =>  (!is_null($articulo->getNumsCajones())) ? $articulo->getNumsCajones() : null,
          'detalleOrigen' =>  (!is_null($articulo->getDetalleOrigen())) ? $articulo->getDetalleOrigen() : null,
          'importe' =>  ($articulo->getImporte()) ? $articulo->getImporte() : null,
          'fechaEntrada' => $articulo->getFechaEntrada()->format('d-m-Y'),
          'codigoCuentaSubcuenta' =>  ($articulo->getCodigoCuentaSubcuenta()) ? $articulo->getCodigoCuentaSubcuenta() : null,
        );
      };

      return new JsonResponse($rawResponse['rows']);
    }

    /**
     * Creates a new articulo entity.
     *
     * @Route("{id}/new", name="articulo_new")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Oficina $oficina)
    {
      $em = $this->getDoctrine()->getManager();
      $artNumInv = $em->getRepository('AppBundle:Articulo')->findOneBy([], ['id' => 'desc']);
      $numInv =  $this->container->getParameter('numeroFacultad').'-'.$oficina->getNumeroCarpeta().'-'.((is_null($artNumInv))? 1 : $artNumInv->getNumInventarioINT()+1);
      $condiciones = $em->getRepository('AppBundle:Condicion')->findByHabilitado(1);
      $articulo = new Articulo($numInv, $oficina);
      $form = $this->createForm('AppBundle\Form\ArticuloType', $articulo);
      $form->handleRequest($request);
      $errors = array();
      $backPath = 'articulos_index';
      $backTitle = 'articulos';
      $em = $this->getDoctrine()->getManager();
      $estadosRepository = $em->getRepository('AppBundle:estadoAdicional');
      $estados = $estadosRepository->findBy(
            array('habilitado' => 1
            )
        );


      $oficinaId = $oficina->getId();

      if (!is_null($oficinaId))
      {
        $backPath = 'oficina_index';
        $backTitle = 'oficinas';
        $oficinaId = trim($oficinaId);
        $oficina = $em->getRepository('AppBundle:Oficina')->find($oficinaId);
        if (!$oficina)
        {
          $errors[] = 'La oficina ingresada no existe.';
        }
      }

      if ($form->isSubmitted() && $form->isValid() && count($errors) == 0) {

        $cantidad = $request->request->get('cantidad');
        $ultimoNum = $request->request->get('numInvent');
        $porciones = explode("-", $articulo->getNumInventario());
        $ultimoNum = $porciones[2];
        if ($cantidad == '1')
        {
          $estado = $em->getRepository('AppBundle:Estado')->findOneByNombre('Activo');
          $estados = $em->getRepository('AppBundle:estadoAdicional')->findAll();
          $estadoAdicional = $request->request->get("estadoAdicional");
          $estadoAdicionalReal = $em->getRepository('AppBundle:estadoAdicional')->findOneByNombre($estadoAdicional);
          $articulo->setEstadoAdicional($estadoAdicionalReal);
          $articulo->setEstado($estado);
          $articulo->setUser($this->getUser());
          if ($oficina) {
            $articulo->setOficina($oficina);
          }
          $em->persist($articulo);
          $em->flush();
          return $this->redirectToRoute('oficina_show', array('id' => $oficinaId, 'editado' => 'editado',
            'mensaje' => 'El articulo se ha agregado con éxito'
          ));
        } else {
          for ($i=1; $i <= $cantidad; $i++) {
            $estado = $em->getRepository('AppBundle:Estado')->findOneByNombre('Activo');
            $articulo->setEstado($estado);
            $articulo->setUser($this->getUser());
            $em->persist($articulo);
            $em->flush();
            $ultimoNum = $ultimoNum + 1;
            $denomin = $articulo->getDenominacion();
            $material = $articulo->getMaterial();
            $marca = $articulo->getMarca();
            $numFabrica = $articulo->getNumFabrica();
            $largo = $articulo->getLargo();
            $ancho = $articulo->getAncho();
            $alto = $articulo->getAlto();
            $numEst = $articulo->getNumsEstantes();
            $numCaj = $articulo->getNumsCajones();
            $detalle = $articulo->getDetalleOrigen();
            $tipoM = $articulo->getMoneda();
            $importe = $articulo->getImporte();
            $fecha = $articulo->getFechaEntrada();
            $cod = $articulo->getCodigoCuentaSubcuenta();
            $exped = $articulo->getNumExpediente();
            $obs = $articulo->getObservaciones();
            $cond = $articulo->getCondicion();
            $tipo = $articulo->getTipo();
            $articulo = new Articulo($porciones[0].'-'.$porciones[1].'-'.$ultimoNum, $oficina);
            $articulo->setDenominacion($denomin);
            $articulo->setMaterial($material);
            $articulo->setMarca($marca);
            $articulo->setNumFabrica($numFabrica);
            $articulo->setLargo($largo);
            $articulo->setAncho($ancho);
            $articulo->setAlto($alto);
            $articulo->setNumsEstantes($numEst);
            $articulo->setNumsCajones($numCaj);
            $articulo->setDetalleOrigen($detalle);
            $articulo->setMoneda($tipoM);
            $articulo->setImporte($importe);
            $articulo->setFechaEntrada($fecha);
            $articulo->setCodigoCuentaSubcuenta($cod);
            $articulo->setNumExpediente($exped);
            $articulo->setObservaciones($obs);
            $articulo->setCondicion($cond);
            $articulo->setTipo($tipo);
          }
          return $this->redirectToRoute('oficina_show', array('id' => $oficinaId, 'editado' => 'editado',
            'mensaje' => 'El articulo se ha agregado con éxito'
          ));
        }
      }
    $arrayValuesCond = $em->getRepository('AppBundle:Condicion')->findBy(array('habilitado' => '0'));
    $arrayDesCond = [];
    $count = count($arrayValuesCond);
    for ($i=0; $i < $count; $i++) {
      array_push($arrayDesCond,$arrayValuesCond[$i]->getId());
    }
    $arrayValuesTipo = $em->getRepository('AppBundle:Tipo')->findBy(array('habilitado' => '0'));
    $arrayDesTipo = [];
    $count = count($arrayValuesTipo);
    for ($i=0; $i < $count; $i++) {
      array_push($arrayDesTipo,$arrayValuesTipo[$i]->getId());
    }



    return $this->render('articulo/new.html.twig', array(
        'articulo' => $articulo,
        'estados' => $estados,
        'form' => $form->createView(),
        'errors' => $errors,
        'backPath' => $backPath,
        'backTitle' => $backTitle,
        'CondDeshabilitadas' => $arrayDesCond,
        'TiposDeshabilitadas' => $arrayDesTipo
    ));
  }

  /**
   * Cambio de estado adicional de articulos vista
   * @Route("{id}/articulo_change", name="articulo_change")
   * @Method({"GET", "POST"})
   */
    public function changeAction(Request $request, Oficina $oficina)
    {
      $id = $oficina->getId();
      $em = $this->getDoctrine()->getManager();
      $articulosRepository = $em->getRepository('AppBundle:Articulo');
      $em = $this->getDoctrine()->getManager();
      $estadosRepository = $em->getRepository('AppBundle:Estado');
      $estadoActivo = $estadosRepository->findOneByNombre('Activo');

      $articulos = $articulosRepository->findBy(array('oficina' => $id, 'estado' => $estadoActivo->getId()));
      //foreach ($articulos as $ar){
      //  var_dump($ar->getDenominacion());
      //}
      //die();

      return $this->render('oficina/change_state.html.twig', array(
          'articulos' => $articulos,
          'oficina' => $oficina,
          //'delete_form' => $deleteForm->createView(),
          //'editado' => '',
      ));



    }

    /**
     * Cambio de estado adicional de articulos
     * @Route("{id}/changeArticulos", name="changeArticulos")
     * @Method({"GET", "POST"})
     */
      public function changeArticulosAction(Request $request){
        $estado = $request->request->get('estado');
        $articulosSeleccionados = $request->request->get('articulosSeleccionados');
        //$oficinaId = $request->query->get('id');


        $em = $this->getDoctrine()->getManager();
        $estadosRepository = $em->getRepository('AppBundle:estadoAdicional');
        $estadoAd = $estadosRepository->findOneBy(array('id' => $estado ));

        $count = count($articulosSeleccionados);
        for ($i=0; $i < $count; $i++) {
          $em = $this->getDoctrine()->getManager();
          $articulosRepository = $em->getRepository('AppBundle:Articulo');
          $idArt = $articulosSeleccionados[$i];
          $articulo = $articulosRepository->findOneBy(array('id' => $idArt));
          $articulo->setEstadoAdicional($estadoAd);
          $this->getDoctrine()->getManager()->flush();
        }

        $rawResponse = array(
          'res' => true
        );

        return new JsonResponse($rawResponse);
      }


    /**
     * Displays a form to edit an existing articulo entity.
     *
     * @Route("/{id}/edit", name="articulo_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Articulo $articulo)
    {
        // $deleteForm = $this->createDeleteForm($articulo);
        $editForm = $this->createForm('AppBundle\Form\ArticuloType', $articulo);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

          $this->getDoctrine()->getManager()->flush();
          $oficinaIdToRedirect = ($articulo->getOficina())->getId();
          return $this->redirectToRoute('oficina_show', array('id' => $oficinaIdToRedirect, 'editado'=>'editado',
          'mensaje' => 'El articulo se ha editado con éxito'
          ));
        }
        $em = $this->getDoctrine()->getManager();
        $arrayValuesCond = $em->getRepository('AppBundle:Condicion')->findBy(array('habilitado' => '0'));
        $arrayDesCond = [];
        $count = count($arrayValuesCond);
        for ($i=0; $i < $count; $i++) {
          array_push($arrayDesCond,$arrayValuesCond[$i]->getId());
        }
        $arrayValuesTipo = $em->getRepository('AppBundle:Tipo')->findBy(array('habilitado' => '0'));
        $arrayDesTipo = [];
        $count = count($arrayValuesTipo);
        for ($i=0; $i < $count; $i++) {
          array_push($arrayDesTipo,$arrayValuesTipo[$i]->getId());
        }
        return $this->render('articulo/edit.html.twig', array(
            'articulo' => $articulo,
            'edit_form' => $editForm->createView(),
            'CondDeshabilitadas' => $arrayDesCond,
            'TiposDeshabilitadas' => $arrayDesTipo
            // 'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Displays a form to edit an existing articulo entity.
     *
     * @Route("/{id}/articulo_historial", name="articulo_historial")
     * @Method({"GET", "POST"})
     */
     public function showHistorial(Request $request, Articulo $articulo)
    {
      $em = $this->getDoctrine()->getManager();
      $qb = $em->createQueryBuilder();

      $articuloRepository = $em->getRepository('AppBundle:Articulo');
      $historialRepository = $em->getRepository('AppBundle:Historial');
      $oficinaRepository = $em->getRepository('AppBundle:Oficina');
      $historiales = $articulo->getHistoriales();
      $historial = array();

      $articulo->getOficina();

      $historial[0]['fechaDesde'] = $articulo->getFechaEntrada()->format('d-m-Y');
      $historial[0]['estado'] = false;
      $historial[0]['oficina'] = $articulo->getOficina();
      if ($historiales->isEmpty()) {

      }else{
          $a = $historiales[0];
          $b = $historiales[1];

          //el primer historial es una transferencia?
          if ($a->getTransferencia() != null) {
            $oficinaNombre = $a->getTransferencia()->getOficinaOrigen()->getNombre();
            $historial[0]['oficina'] = $oficinaNombre;
          }else{
            $oficinaNombre = $a->getBaja()->getOficina()->getNombre();
            $historial[1]['estado'] = true;
          }

          $i=1;
          $j=0;
          foreach ($historiales as $h){
          	$historial[$i]['fechaDesde'] = $h->getFecha()->format('d-m-Y');
            if ($h->getTransferencia() !=null) {
              $oficinaNombre = ($h->getTransferencia()->getOficinaDestino() != null)?$h->getTransferencia()->getOficinaDestino()->getNombre():"-";
              $historial[$i]['estado'] = false;
            }else{
              $oficinaNombre = $h->getBaja()->getOficina()->getNombre();
              $historial[$i]['estado'] = true;
            }
          	$historial[$i]['oficina'] = $oficinaNombre;
          	$i++;
          }
      }

      return $this->render('articulo/historial.html.twig', array(
          'historial' => $historial,

          'articulo' => $articulo,
          // 'delete_form' => $deleteForm->createView(),
      ));
    }



    /**
     *
     * @Route("/listadoArticulosPDF",   name="listado_articulos_pdf")
     * @Method({"POST"})
     */
     public function exportTabla(Request $request){

       $em = $this->getDoctrine()->getManager();
       // $articulos = $em->getRepository('AppBundle:Articulo')->findAll();
       $nroInventario = $request->request->get('nroInventario');
       $numExpediente = $request->request->get('nroExpediente');
       $denominacion = $request->request->get('denominacion');
       $estado = $request->request->get('estado');
       $tipo = $request->request->get('tipo');
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

       $total = count($articulos);

       $rawResponse = array(
         'total' => $total,
         'rows' => array()
       );

       $condicionInicial = null;
       foreach($articulos as $articulo) {
         $condiciones = array();
         $historialesCollection = $articulo->getHistoriales();
         if (!($historialesCollection->isEmpty())) {
             foreach ($historialesCollection as $h) {
               if ($h->getTransferencia() != null) {
                 $condiciones[] = ($h->getCondicion() != null)? $h->getCondicion()->getNombre():null;
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
           'numExpediente' => $articulo->getNumExpediente(),
           'denominacion' => $articulo->getDenominacion(),
           'oficina' => $articulo->getOficina()->getNombre(),
           'tipo' => (!is_null($articulo->getTipo())) ? $articulo->getTipo()->getConcepto() : null,
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

       return $this->render('articulo/reporte.html.twig', array(
          'articulo' => $rawResponse['rows']

       ));
     }


         /**
          *
          * @Route("/listadoArticulosOficinaPDF/{id}",   name="listado_articulos_oficina_pdf")
          */
          public function exportTablaOficina(Request $request, $id){
            // $format = $request->get('_format');
            // $em = $this->getDoctrine()->getManager();
            // $oficina = $em->getRepository('AppBundle:Oficina')->findOneByNombre($id);
            // $articulos = $em->getRepository('AppBundle:Articulo')->findByOficina($oficina);
            // return $this->render('articulo/reportePorOficina.html.twig', array(
            //    'articulo' => $articulos,
            //    'oficina' => $id
            // ));

            $em = $this->getDoctrine()->getManager();
            $nroInventario = $request->request->get('nroInventario');
            $numExpediente = $request->request->get('expediente');
            $denominacion = $request->request->get('denominacion');
            $estado = $request->request->get('estado');
            $tipo = $request->request->get('tipo');
            $oficinaId = $request->query->get('idOficina');
            $estadoAdicional = $request->request->get('estadoAdicional');
            $objOficina = $em->getRepository("AppBundle:Oficina")->findOneBy(array('id' => $id ));

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
                    and ((a.oficina = :oficina and :oficina is not null) or (:oficina is null))
                    and ((a.tipo = :tipo and :tipo is not null) or (:tipo is null)))
                    or (:nroInventario is null and :numExpediente is null and :denominacion is null and :estado is null and :estadoAdicional is null and :tipo is null and a.oficina = :oficina)";
            $query = $em->createQuery($dql);
            $query->setParameter('nroInventario', $nroInventario);
            $query->setParameter('numExpediente', $numExpediente);
            $query->setParameter('denominacion', $denominacion);
            $query->setParameter('estado', $estado);
            $query->setParameter('tipo', $tipo);
            $query->setParameter('estadoAdicional', $estadoAdicional);
            $query->setParameter('oficina', $id);
            $articulos = $query->getResult();

            $total = count($query->getResult());

            $rawResponse = array(
              'total' => $total,
              'rows' => array()
            );
            $condicionInicial = null;
            foreach($articulos as $articulo) {
                $condiciones = array();
                $historialesCollection = $articulo->getHistoriales();
                if (!($historialesCollection->isEmpty())) {
                    foreach ($historialesCollection as $h) {
                      if ($h->getTransferencia() != null) {
                        $condiciones[] = ($h->getCondicion() != null)? $h->getCondicion()->getNombre():null;
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
                  'tipo' => (!is_null($articulo->getTipo())) ? $articulo->getTipo()->getConcepto() : null,
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

            return $this->render('articulo/reportePorOficina.html.twig', array(
               'articulo' => $rawResponse['rows'],
               'oficina' => $objOficina
            ));

          }





    // /**
    //  * Deletes a articulo entity.
    //  *
    //  * @Route("/{id}/delete", name="articulo_delete")
    //  * @Method("DELETE")
    //  */
    // public function deleteAction(Request $request, Articulo $articulo)
    // {
    //     $form = $this->createDeleteForm($articulo);
    //     $form->handleRequest($request);
    //
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $em = $this->getDoctrine()->getManager();
    //         $em->remove($articulo);
    //         $em->flush();
    //     }
    //
    //     return $this->redirectToRoute('articulo_index');
    // }
    //
    // /**
    //  * Creates a form to delete a articulo entity.
    //  *
    //  * @param Articulo $articulo The articulo entity
    //  *
    //  * @return \Symfony\Component\Form\Form The form
    //  */
    // private function createDeleteForm(Articulo $articulo)
    // {
    //     return $this->createFormBuilder()
    //         ->setAction($this->generateUrl('articulo_delete', array('id' => $articulo->getId())))
    //         ->setMethod('DELETE')
    //         ->getForm()
    //     ;
    // }

}

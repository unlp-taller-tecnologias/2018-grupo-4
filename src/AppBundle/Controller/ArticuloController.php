<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Articulo;
use AppBundle\Entity\Oficina;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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
        return $this->render('articulo/index.html.twig', array(
            'articulos' => $articulos,
            'editado' => $editado,
        ));
    }

    /**
     * Finds and displays a articulo entity.
     *
     * @Route("/{id}", name="articulo_show")
     * @Method("GET")
     */
    public function showAction(Request $request, Articulo $articulo)
    {
        // $deleteForm = $this->createDeleteForm($articulo);
        $editado = $request->query->get('editado');
        return $this->render('articulo/show.html.twig', array(
            'articulo' => $articulo,
            // 'delete_form' => $deleteForm->createView(),
            'editado' => $editado,
        ));
    }

    /**
     * Lists all articulo entities.
     *
     * @Route("/listado", name="articulo_list", defaults={"oficina"=null})
     * @Method({"GET", "POST"})
     */
    public function listadoAction(Request $request){
      $offset = $request->query->get('offset', 0);
      $limit = $request->query->get('limit', 10);
      $search = $request->query->get('search', null);
      $sort = $request->query->get('sort', null);
      $order = $request->query->get('order', null);

      $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Articulo');
      $articulos = $repository->getBy($offset, $limit, $sort, $order, $search);
      $total = $repository->countBy($search);

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
     * Lists all articulo entities.
     *
     * @Route("/articulo_listFilter", name="articulo_listFilter", defaults={"oficina"=null})
     * @Method({"GET", "POST"})
     */
    public function listadoActionFilter(Request $request){
      $offset = $request->query->get('offset', 0);
      $limit = $request->query->get('limit', 10);
      $search = $request->query->get('search', null);
      $sort = $request->query->get('sort', null);
      $order = $request->query->get('order', null);

      $nroInventario = $request->request->get('nroInventario');
      $numExpediente = $request->request->get('expediente');
      $denominacion = $request->request->get('denominacion');
      $estado = $request->request->get('estado');
      $tipo = $request->request->get('tipo');


      $nroInventario = ($nroInventario == "")? NULL:$nroInventario;
      $numExpediente = ($numExpediente == "")? NULL:$numExpediente;
      $denominacion = ($denominacion == "")? NULL:$denominacion."%";
      $estado = ($estado == "")? NULL:$estado;
      $tipo = ($tipo == "")? NULL:$tipo;

      $em = $this->getDoctrine()->getEntityManager();
      $dql = "select a from AppBundle:Articulo a where (((a.numInventario = :nroInventario and :nroInventario is not null) or (:nroInventario is null))
              and ((a.numExpediente = :numExpediente and :numExpediente is not null) or (:numExpediente is null))
              and ((a.denominacion like :denominacion and :denominacion is not null) or (:denominacion is null))
              and ((a.estado = :estado and :estado is not null) or (:estado is null))
              and ((a.tipo = :tipo and :tipo is not null) or (:tipo is null)))
              or (:nroInventario is null and :numExpediente is null and :denominacion is null and :estado is null and :tipo is null)";
      $query = $em->createQuery($dql);
      $query->setParameter('nroInventario', $nroInventario);
      $query->setParameter('numExpediente', $numExpediente);
      $query->setParameter('denominacion', $denominacion);
      $query->setParameter('estado', $estado);
      $query->setParameter('tipo', $tipo);
      $articulos = $query->getResult();

      $total = count($articulos);

      $rawResponse = array(
        'total' => $total,
        'rows' => array()
      );

      foreach($articulos as $articulo) {
        $rawResponse['rows'][] = array(
          'id' => $articulo->getId(),
          'numInventario' =>$articulo->getNumInventario(),
          'numExpendiente' => $articulo->getNumExpediente(),
          'denominacion' => $articulo->getDenominacion(),
          'tipo' => ($articulo->getTipo()) ? $articulo->getTipo()->getDescripcion() : null,
          'estado' => $articulo->getEstado()->getNombre()
        );
      };

      return new JsonResponse($rawResponse);
    }

    /**
     * Creates a new articulo entity.
     *
     * @Route("/new/{id}", name="articulo_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
      $em = $this->getDoctrine()->getManager();
      $artNumInv = $em->getRepository('AppBundle:Articulo')->findOneBy([], ['id' => 'desc']);
      $numInv = ($artNumInv->getNumInventario())+1;
      $condiciones = $em->getRepository('AppBundle:Condicion')->findByHabilitado(1);
      $articulo = new Articulo($numInv);
      $form = $this->createForm('AppBundle\Form\ArticuloType', $articulo);
      $form->handleRequest($request);
      $errors = array();
      $backPath = 'articulos_index';
      $backTitle = 'articulos';

      $oficinaId = $request->query->get('id', null);
      var_dump($oficinaId);
      die();

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
        //$matrial = $request->request->get('material');

        if ($cantidad == '1')
        {
          $estado = $em->getRepository('AppBundle:Estado')->findOneByNombre('Activo');
          $articulo->setEstado($estado);
          $articulo->setUser($this->getUser());
          if ($oficina) {
            $articulo->setOficina($oficina);
          }
          $em->persist($articulo);
          $em->flush();
          return $this->redirectToRoute('articulo_show', array('id' => $articulo->getId()));
        } else {
          for ($i=1; $i <= $cantidad; $i++) {
            $estado = $em->getRepository('AppBundle:Estado')->findOneByNombre('Activo');
            $articulo->setEstado($estado);
            $articulo->setUser($this->getUser());
            if ($oficina) {
              $articulo->setOficina($oficina);
            }
            $em->persist($articulo);
            $em->flush();
            //$articulo->setNumInventario($ultimoNum);
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

            $articulo = new Articulo($ultimoNum);
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
        'form' => $form->createView(),
        'errors' => $errors,
        'backPath' => $backPath,
        'backTitle' => $backTitle,
        'CondDeshabilitadas' => $arrayDesCond,
        'TiposDeshabilitadas' => $arrayDesTipo
    ));
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
          return $this->redirectToRoute('oficina_show', array('id' => $oficinaIdToRedirect, 'editado'=>'editado'));
        }

        return $this->render('articulo/edit.html.twig', array(
            'articulo' => $articulo,
            'edit_form' => $editForm->createView(),
            // 'delete_form' => $deleteForm->createView(),
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

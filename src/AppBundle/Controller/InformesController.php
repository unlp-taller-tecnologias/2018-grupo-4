<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Baja;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Informes controller.
 *
 * @Route("informes")
 */
class InformesController extends Controller
{
    /**
     * Lists all informes.
     *
     * @Route("/", name="informes_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        //$bajas = $em->getRepository('AppBundle:Baja')->findAll();

        return $this->render('informes/informes.html.twig');
    }

    /**
     * Lists all informes
     *
     * @Route("/informes_list", name="informes_list")
     * @Method({"GET"})
     */
    public function listAction(Request $request){
      // $offset = $request->query->get('offset', 0);
      // $limit = $request->query->get('limit', 10);
      // $search = $request->query->get('search', null);
      // $sort = $request->query->get('sort', null);
      // $order = $request->query->get('order', null);
      $em = $this->getDoctrine()->getManager();
      $transferenciaRepository = $em->getRepository('AppBundle:Transferencia');
      $bajaRepository = $em->getRepository('AppBundle:Baja');
      // $articulos = $repository->getBy($offset, $limit, $sort, $order, $search);
      $transferencias =  $transferenciaRepository->findBy(array('finalizada' => '1'));
      $bajas =  $bajaRepository->findBy(array('finalizada' => '1'));
      foreach($transferencias as $item) {
      
        $rawResponse['rows'][] = array(
          'id' => $item->getId(),
          'oficinaOrigen' => $item->getOficinaOrigen()->getNombre(),
          'oficinaDestino' =>  ($item->getOficinaDestino()) ? $item->getOficinaDestino()->getNombre() : null,

          'fecha' => $item->getFecha(),
          'tipo' => 'Transferencia',
        );
      };

      foreach($bajas as $item) {
        $rawResponse['rows'][] = array(
          'id' => $item->getId(),
          'oficinaOrigen' => $item->getOficina()->getNombre(),

          'fecha' => $item->getFecha(),
          'tipo' => 'Baja',
        );
      };

      return new JsonResponse($rawResponse);
    }



}

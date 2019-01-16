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
      $em = $this->getDoctrine()->getManager();
      $transferenciaRepository = $em->getRepository('AppBundle:Transferencia');
      $bajaRepository = $em->getRepository('AppBundle:Baja');
      $transferencias =  $transferenciaRepository->findBy(array('finalizada' => '1'));
      $bajas =  $bajaRepository->findBy(array('finalizada' => '1'));
      foreach($transferencias as $item) {

        $rawResponse['rows'][] = array(
          'id' => $item->getId(),
          'oficinaOrigen' => $item->getOficinaOrigen()->getNombre(),
          'oficinaDestino' =>  ($item->getOficinaDestino()) ? $item->getOficinaDestino()->getNombre() : null,

          'fecha' => $item->getFecha()->format('d-m-Y'),
          'tipo' => 'Transferencia',
        );
      };

      foreach($bajas as $item) {
        $rawResponse['rows'][] = array(
          'id' => $item->getId(),
          'oficinaOrigen' => $item->getOficina()->getNombre(),
          'fecha' => $item->getFecha()->format('d-m-Y'),
          'tipo' => 'Baja',
        );
      };

      return new JsonResponse($rawResponse['rows']);
    }

    /**
     * Lists all informes by oficina
     *
     * @Route("/listado_informes_oficina/{id_oficina}", name="listado_informes_oficina")
     * @Method({"GET"})
     */
    public function listOficinaAction(Request $request, $id_oficina){
      $em = $this->getDoctrine()->getManager();
      $oficina = $em->getRepository('AppBundle:Oficina')->findOneById($id_oficina);
      //var_dump($id_oficina);
      //die();
      $em = $this->getDoctrine()->getManager();
      $transferenciaRepository = $em->getRepository('AppBundle:Transferencia');
      $bajaRepository = $em->getRepository('AppBundle:Baja');
      $transferencias =  $transferenciaRepository->findBy(array('finalizada' => '1', 'oficinaOrigen' => $oficina));

      $bajas =  $bajaRepository->findBy(array('finalizada' => '1', 'oficina' => $oficina));
      foreach($transferencias as $item) {

        $rawResponse['rows'][] = array(
          'id' => $item->getId(),
          'oficinaOrigen' => $item->getOficinaOrigen()->getNombre(),
          'oficinaDestino' =>  ($item->getOficinaDestino()) ? $item->getOficinaDestino()->getNombre() : null,

          'fecha' => $item->getFecha()->format('d-m-Y'),
          'tipo' => 'Transferencia',
        );
      };

      foreach($bajas as $item) {
        $rawResponse['rows'][] = array(
          'id' => $item->getId(),
          'oficinaOrigen' => $item->getOficina()->getNombre(),
          'fecha' => $item->getFecha()->format('d-m-Y'),
          'tipo' => 'Baja',
        );
      };

      return new JsonResponse($rawResponse['rows']);
    }

    /**
     * Lists all informes.
     *
     * @Route("/informes_oficina/{id_oficina}", name="informes_oficina")
     * @Method("GET")
     */
    public function informesOficinaAction($id_oficina)
    {
        $em = $this->getDoctrine()->getManager();
        $oficina = $em->getRepository('AppBundle:Oficina')->findOneById($id_oficina);

        return $this->render('informes/informes_oficina.html.twig', array('oficina' => $oficina));
    }

}

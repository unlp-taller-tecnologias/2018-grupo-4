<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * User controller.
 *
 * @Route("editUser")
 */
class EditUserController extends Controller
{
	/**
	 * Lists all user entities.
	 *
	 * @Route("/", name="user_index")
	 * @Method("GET")
	 */
	public function indexAction(Request $request)
	{

		$em = $this->getDoctrine()->getManager();

		$users = $em->getRepository('AppBundle:User')->findAll();
		$editado = $request->query->get('editado');
		return $this->render('user/index.html.twig', array(
	    	'users' => $users,
	    	'editado' => $editado,
		));
	}

	// /**
	//  * Creates a new user entity.
	//  *
	//  * @Route("/new", name="user_new")
	//  * @Method({"GET", "POST"})
	//  */
	// public function newAction(Request $request)
	// {
	// 	$user = new User();
	// 	$editRole = $this->isGranted('ROLE_SUPER_ADMIN');
	// 	$form = $this->createForm('AppBundle\Form\UserType', $user, array(
	//   	'edit_role' => $editRole
	// 	));
	// 	$form->handleRequest($request);
	//
	// 	if ($form->isSubmitted() && $form->isValid()) {
	//     	$em = $this->getDoctrine()->getManager();
	//     	$em->persist($user);
	//     	$em->flush();
	//
	//     	return $this->redirectToRoute('user_index', array('editado' => 'editado'));
	// 	}
	//
	// 	return $this->render('user/new.html.twig', array(
	//     	'user' => $user,
	//     	'form' => $form->createView(),
	// 	));
	// }

	// /**
	//  * Finds and displays a user entity.
	//  *
	//  * @Route("/{id}", name="editUser_show")
	//  * @Method("GET")
	//  */
	// public function showAction(User $user)
	// {
	// 	return $this->render('user/show.html.twig', array(
	//     	'user' => $user,
	//     	'delete_form' => $deleteForm->createView(),
	// 	));
	// }

	/**
 	* Displays a form to edit an existing user entity.
 	*
 	* @Route("/{id}/editUser", name="editUser_edit")
 	* @Method({"GET", "POST"})
 	*/
	public function editAction(Request $request, User $user)
	{

				if ($this->getUser()->getId() == $user->getId()) {
					$deleteForm = $this->createDeleteForm($user);
		    	$editRole = $this->isGranted('ROLE_SUPER_ADMIN');
		    	$editForm = $this->createForm('AppBundle\Form\UserType', $user, array(
		      	'edit_role' => $editRole, "edit" => false
		    	));
		    	$editForm->handleRequest($request);

		    	if ($editForm->isSubmitted() && $editForm->isValid()) {

		        	$userManager = $this->container->get('fos_user.user_manager');
		        	$userManager->updatePassword($user);
		        	$this->getDoctrine()->getManager()->flush();

							return $this->render('oficina/index.html.twig', array(
			            'editado' => 'editado',
			        ));
		    	}
							// return $this->redirectToRoute('editUser_edit', array('editado' => 'editado',
							// 'id' => $user->getId()));
		    	return $this->render('user/editUser.html.twig', array(
							'eliminado' => "",
		        	'user' => $user,
		        	'edit_form' => $editForm->createView(),
		        	'delete_form' => $deleteForm->createView(),
		    	 ));
				}else{
					return $this->render('oficina/index.html.twig', array(
						'editado' => "",
					));
				}


	}

	// /**
	//  * Displays a form to edit an existing user entity.
	//  *
	//  * @Route("/{id}/visibility", name="user_visibility")
	//  * @Method({"GET", "POST"})
	//  */
	// public function visibilityAction(Request $request, User $user)
	// {
	//
	// 	$editRole = $this->isGranted('ROLE_SUPER_ADMIN');
	// 	$editForm = $this->createForm('AppBundle\Form\UserType', $user, array(
	//   	'edit_role' => $editRole, 'visibility' => false
	// 	));
	// 	$editForm->handleRequest($request);
	//
	// 	if ($editForm->isSubmitted() && $editForm->isValid()) {
	//     	$userManager = $this->container->get('fos_user.user_manager');
	//     	$userManager->updatePassword($user);
	//     	$this->getDoctrine()->getManager()->flush();
	//
	//     	return $this->redirectToRoute('user_index', array('editado' => 'editado'));
	// 	}
	//
	// 	return $this->render('user/edit.html.twig', array(
	//     	'user' => $user,
	//     	'edit_form' => $editForm->createView(),
	// 	));
	// }


	/**
	 * Deletes a user entity.
	 *
	 * @Route("/{id}/delete", name="user_delete")
	 * @Method("DELETE")
	 */
	public function deleteAction(Request $request, User $user)
	{
		$form = $this->createDeleteForm($user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
	    	$em = $this->getDoctrine()->getManager();
	    	$em->remove($user);
	    	$em->flush();
		}

		return $this->redirectToRoute('user_index');
	}

	/**
	 * Creates a form to delete a user entity.
	 *
	 * @param User $user The user entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createDeleteForm(User $user)
	{
		return $this->createFormBuilder()
	    	->setAction($this->generateUrl('user_delete', array('id' => $user->getId())))
	    	->setMethod('DELETE')
	    	->getForm()
		;
	}



	// /**
	//  * Lists all articulo entities.
	//  *
	//  * @Route("/listadoUsers", name="user_list")
	//  * @Method({"GET", "POST"})
	//  */
	// public function listadoAction(Request $request){
	//   $offset = $request->query->get('offset', 0);
	//   $limit = $request->query->get('limit', 10);
	//   $search = $request->query->get('search', null);
	//   $sort = $request->query->get('sort', null);
	//   $order = $request->query->get('order', null);
	//
	//   $em = $this->getDoctrine()->getManager();
	//   $repository = $em->getRepository('AppBundle:User');
	//   $userQuery = $repository
	// 	->createQueryBuilder('user');
	//
	//   $users = $userQuery
	// 	->setMaxResults($limit)
	// 	->setFirstResult($offset)
	// 	->getQuery()
	// 	->getResult();
	//
	//   $totalQuery = $repository->createQueryBuilder('user')
	// 	->select('count(user.id)');
	//   if (!is_null($search) && strlen($search) > 0) {
	// 	$totalQuery
	//   	->where('user.name like :name')
	//   	->setParameter('user', '%'.$search.'%');
	//   }
	//
	//   $total = $totalQuery
	// 	->getQuery()
	// 	->getSingleScalarResult();
	//
	//   $rawResponse = array(
	// 	'total' => $total,
	// 	'rows' => array()
	//   );
	//
	//   foreach($users as $user) {
	// 	$rawResponse['rows'][] = array(
	//   	'id' => $user->getId(),
	//   	'username' => $user->getUsername(),
	//   	'name' => $user->getName(),
	//   	'lastname' => $user->getLastname(),
	//   	'enabled' => ($user->getEnabled() == 1)?'Si':'No',
	// 	);
	//   };
	//
	//   return new JsonResponse($rawResponse);
	// }


	/**
 	* Finds and displays a user entity.
 	*
 	* @Route("/{id}", name="editUser_ver")
 	* @Method("GET")
 	*/
	public function showAction(User $user)
	{

    	return $this->render('user/ver.html.twig', array(
        	'user' => $user,
    	));
	}
}

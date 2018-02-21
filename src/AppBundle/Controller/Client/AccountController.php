<?php

namespace AppBundle\Controller\Client;

use AppBundle\Entity\Category;
use AppBundle\Entity\User;
use AppBundle\Service\UserServices;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Account controller.
 *
 * @Route("mon-compte")
 */
class AccountController extends Controller
{

    private $userServices;

    public function __construct(UserServices $userServices)
    {
        $this->userServices = $userServices;
    }

    /**
     * Edit current user.
     *
     * @Route("/", name="account_user_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editUserAction(Request $request)
    {
        $savedUser = clone $this->getUser();
        $user = $this->userServices->preLoadAccount($this->getUser());

        $deleteForm = $this->createDeleteUserForm($user);

        $editForm = $this->createForm('AppBundle\Form\UserType', $user, [
            'type' => 'userEdit'
        ]);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $user = $this->userServices->prePersistAccount($savedUser, $user);

            $this->getDoctrine()->getManager()->flush();

            if ($user->getPassword() != $savedUser->getPassword()){
                $this->redirectToRoute('logout');
            }
        }

        return $this->render('@Client/account/index.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Lists all created projects by user.
     *
     * @Route("/projets-crees", name="account_show_created_projects")
     * @Method("GET")
     */
    public function showCreatedProjectsAction()
    {
        return $this->render('@Client/account/project/list.html.twig', [
            'projects' => $this->getUser()->getCreatedProjects(),
            'categories' => $this->getDoctrine()->getRepository(Category::class)->findAll()
        ]);
    }

    /**
     * Lists all followed projects by user.
     *
     * @Route("/projets-suivis", name="account_show_followed_projects")
     * @Method("GET")
     */
    public function showFollowedProjectsAction()
    {
        return $this->render('@Client/account/project/list.html.twig', [
            'projects' => $this->getUser()->getFollowedProjects(),
            'categories' => $this->getDoctrine()->getRepository(Category::class)->findAll()
        ]);
    }

    /**
     * Lists all participating projects by user.
     *
     * @Route("/projets-participes", name="account_show_participating_projects")
     * @Method("GET")
     */
    public function showParticipatingProjectsAction()
    {
        return $this->render('@Client/account/project/list.html.twig', [
            'projects' => $this->getUser()->getParticipatingProject(),
            'categories' => $this->getDoctrine()->getRepository(Category::class)->findAll()
        ]);
    }

    /**
     * Deletes a user entity.
     *
     * @Route("/supprimer", name="account_user_delete")
     * @Method("DELETE")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteUserAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createDeleteUserForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->userServices->preDeleteUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();

            $this->get('security.token_storage')->setToken(null);
        }

        return $this->redirectToRoute('login');
    }

    /**
     * Creates a form to delete a user entity.
     *
     * @param User $user The user entity
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createDeleteUserForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('account_user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}

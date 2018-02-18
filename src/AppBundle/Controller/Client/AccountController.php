<?php

namespace AppBundle\Controller\Client;

use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use AppBundle\Service\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;

/**
 * Account controller.
 *
 * @Route("mon-compte")
 */
class AccountController extends Controller
{

    private $uploader;

    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * Edit current user.
     *
     * @Route("/", name="account_user_edit")
     * @Method("GET")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editUserAction(Request $request)
    {
        $user = $this->getUser();
        $deleteForm = $this->createDeleteUserForm($user);

        $roles = '';
        foreach ($user->getRoles() as $role){
            $roles= $roles . $role . ';';
        }
        $roles = substr($roles, 0, -1);
        $user->setRoles($roles);

        if ($user->getPicture() != null && gettype($user->getPicture()) == 'string'){
            $picture = new File($this->uploader->getUserProfilePictureDir().'/'.$user->getPicture());
            $user->setPicture($picture);
        }

        $editForm = $this->createForm('AppBundle\Form\UserType', $user, [
            'type' => 'edit'
        ]);
        $editForm->handleRequest($request);

        if ($user->getPicture() == null && isset($picture)){
            $user->setPicture($picture);
        }

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $this->getDoctrine()->getManager()->flush();
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
     * @Route("/projets-crées", name="account_show_created_projects")
     * @Method("GET")
     */
    public function showCreatedProjectsAction()
    {
        return $this->render('@Client/account/project/list.html.twig', [
            'projects' => $this->getUser()->getCreatedProjects()
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
            'projects' => $this->getUser()->getFollowedProjects()
        ]);
    }

    /**
     * Lists all participating projects by user.
     *
     * @Route("/projets-participés", name="account_show_participating_projects")
     * @Method("GET")
     */
    public function showParticipatingProjectsAction()
    {
        return $this->render('@Client/account/project/list.html.twig', [
            'projects' => $this->getUser()->getParticipatingProject()
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
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
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

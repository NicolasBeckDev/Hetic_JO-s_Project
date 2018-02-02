<?php

namespace AppBundle\Controller\Client;

use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Account controller.
 *
 * @Route("account")
 */
class AccountController extends Controller
{

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

        $editForm = $this->createForm('AppBundle\Form\UserType', $user, [
            'type' => 'edit'
        ]);
        $editForm->handleRequest($request);

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
     * @Route("/", name="account_show_created_projects")
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
     * @Route("/", name="account_show_followed_projects")
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
     * @Route("/", name="account_show_participating_projects")
     * @Method("GET")
     */
    public function showParticipatingProjectsAction()
    {
        return $this->render('@Client/account/project/list.html.twig', [
            'projects' => $this->getUser()->getParticipatingProject()
        ]);
    }

    /**
     * Displays a form to edit an existing project entity.
     *
     * @Route("/{id}/edit", name="admin_project_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Project $project
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editProjectAction(Request $request, Project $project)
    {
        $deleteForm = $this->createDeleteProjectForm($project);
        $editForm = $this->createForm('AppBundle\Form\ProjectType', $project);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_project_edit', array('id' => $project->getId()));
        }

        return $this->render('@Admin/project/edit.html.twig', array(
            'project' => $project,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Deletes a user entity.
     *
     * @Route("/{id}", name="account_user_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteUserAction(Request $request, User $user)
    {
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


    /**
     * Deletes a project entity.
     *
     * @Route("/{id}", name="account_project_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param Project $project
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteProjectAction(Request $request, Project $project)
    {
        $form = $this->createDeleteProjectForm($project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($project);
            $em->flush();
        }

        return $this->redirectToRoute('account_user_edit');
    }

    /**
     * Creates a form to delete a user entity.
     *
     * @param Project $project The project entity
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createDeleteProjectForm(Project $project)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('account_project_delete', array('id' => $project->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}

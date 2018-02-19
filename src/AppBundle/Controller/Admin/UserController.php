<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\User;
use AppBundle\Service\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * User controller.
 *
 * @Route("administrations/utilisateurs")
 */
class UserController extends Controller
{

    private $uploader;

    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }


    /**
     * Lists all user entities.
     *
     * @Route("/liste", name="admin_user_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        return $this->render('@Admin/user/list.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/nouveau", name="admin_user_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm('AppBundle\Form\UserType', $user, [
            'type' => 'new'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('admin_user_show', [
                'id' => $user->getId()
            ]);
        }

        return $this->render('@Admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("/voir/{id}", name="admin_user_show")
     * @Method("GET")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(User $user)
    {
        $deleteForm = $this->createDeleteForm($user);

        return $this->render('@Admin/user/show.html.twig', array(
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/modifier/{id}", name="admin_user_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, User $user)
    {
        $deleteForm = $this->createDeleteForm($user);

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

            return $this->redirectToRoute('admin_user_edit', array('id' => $user->getId()));
        }

        return $this->render('@Admin/user/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a user entity.
     *
     * @Route("/supprimer/{id}", name="admin_user_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
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

        return $this->redirectToRoute('admin_user_index');
    }

    /**
     * Creates a form to delete a user entity.
     *
     * @param User $user The user entity
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}

<?php

namespace AppBundle\Controller\Admin;

use AppBundle\AppBundle;
use AppBundle\Entity\Project;
use AppBundle\Service\ProjectServices;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;

/**
 * Project controller.
 *
 * @Route("administration/projets")
 */
class ProjectController extends Controller
{
    private $projectServices;

    public function __construct(ProjectServices $projectServices)
    {
        $this->projectServices = $projectServices;
    }

    /**
     * Lists all project entities.
     *
     * @Route("/liste", name="admin_project_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('@Admin/project/index.html.twig', [
            'projects' => $this->getDoctrine()->getRepository(Project::class)->findAll()
        ]);
    }

    /**
     * Displays a form to edit an existing project entity.
     *
     * @Route("/modifier/{id}", name="admin_project_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Project $project
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Project $project)
    {
        $savedProject = clone  $project;
        $project = $this->projectServices->preLoadEdit($project);

        $deleteForm = $this->createDeleteForm($project);

        $validationForm = $this->createValidationForm($project);

        $editForm = $this->createForm('AppBundle\Form\ProjectType', $project);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $project = $this->projectServices->prePersistEdit($savedProject, $project);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_project_edit', array('id' => $project->getId()));
        }

        return $this->render('@Admin/project/edit.html.twig', array(
            'project' => $project,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'validation_form' => $validationForm->createView(),
        ));
    }

    /**
     * validate a project entity.
     *
     * @Route("/valider/{id}", name="admin_project_validate")
     * @Method("POST")
     * @param Request $request
     * @param Project $project
     * @param \Swift_Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function validateAction(Request $request, Project $project, \Swift_Mailer $mailer)
    {
        $form = $this->createValidationForm($project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $project = $this->projectServices->prePersistValidated($project);

            $this->getDoctrine()->getManager()->flush();

            $mail = (new \Swift_Message("Tous Paris. : Création d'un projet (validé)"))
                ->setFrom('tousparis2024@gmail.com')
                ->setTo($project->getCreator()->getEmail())
                ->setBody(
                    '<html>' .
                    '<head></head>' .
                    '<body>' .
                    'Bonjour'.$project->getCreator()->getFirstname().'<br><br>'.
                    'Un membre de notre équipe vient de valider votre projet '. $project->getName() . '.<br>'.
                    'De ce fait, il est maintenant visible par tous.<br><br>'.
                    'Cordialement,<br>'.
                    "L'équipe de Tous Paris.".
                    ' </body>' .
                    '</html>',
                    'text/html'
                );

            $mailer->send($mail);
        }

        return $this->redirectToRoute('admin_project_edit', ['id' => $project->getId()]);
    }

    /**
     * Creates a form to validate a project entity.
     * @param Project $project
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createValidationForm(Project $project)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_project_validate', array('id' => $project->getId())))
            ->setMethod('POST')
            ->getForm()
            ;
    }

    /**
     * Deletes a project entity.
     *
     * @Route("/supprimer/{id}", name="admin_project_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param Project $project
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Project $project)
    {
        $form = $this->createDeleteForm($project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project = $this->projectServices->preDeleteProject($project);
            $em = $this->getDoctrine()->getManager();
            $em->remove($project);
            $em->flush();
        }

        return $this->redirectToRoute('admin_project_index');
    }

    /**
     * Creates a form to delete a project entity.
     * @param Project $project
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createDeleteForm(Project $project)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_project_delete', array('id' => $project->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

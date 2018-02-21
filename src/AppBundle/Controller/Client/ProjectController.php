<?php

namespace AppBundle\Controller\Client;

use AppBundle\Entity\Category;
use AppBundle\Entity\District;
use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use AppBundle\Form\LocationType;
use AppBundle\Service\ProjectServices;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Project controller.
 *
 * @Route("projets")
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
     * @Route("/liste", name="project_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('@Client/project/list.html.twig', [
            'projects' => $this->getDoctrine()->getRepository(Project::class)->findBy(['isValidated' => true]),
            'districts' => $this->getDoctrine()->getRepository(District::class)->findAll(),
            'categories' => $this->getDoctrine()->getRepository(Category::class)->findAll(),
        ]);
    }

    /**
     * Creates a new project entity.
     *
     * @Route("/nouveau", name="project_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, \Swift_Mailer $mailer)
    {
        $project = new Project();
        $form = $this->createForm('AppBundle\Form\ProjectType', $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $project = $this->projectServices->prePersistNew($project);

            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            $mail = (new \Swift_Message("Tous Paris. : Création d'un projet ( en attente de validation )"))
                ->setFrom('tousparis2024@gmail.com')
                ->setTo($this->getUser()->getEmail())
                ->setBody(
                    $this->renderView(
                        '@Email/projectCreation.html.twig',
                        [
                            'project' => $project
                        ]
                    ),
                    'text/html'
                )
            ;

            $mailer->send($mail);

            return $this->redirectToRoute('project_show', [
                'id' => $project->getId()
            ]);
        }

        return $this->render('@Client/project/new.html.twig', array(
            'project' => $project,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a project entity.
     *
     * @Route("/voir/{id}", name="project_show")
     * @Method({"GET", "POST"})
     * @param Project $project
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Project $project, Request $request)
    {
        $followForm = $this->createFormBuilder()->getForm();
        $participateForm = $this->createFormBuilder()->getForm();

        $followForm->handleRequest($request);
        $participateForm->handleRequest($request);

        if ($followForm->isSubmitted() && $followForm->isValid()) {

            $this->projectServices->updateFollowedProjects($project);
            $this->getDoctrine()->getManager()->flush();

        }elseif ($participateForm->isSubmitted() && $participateForm->isValid()){

            $this->projectServices->updateParticipatingProjects($project);
            $this->getDoctrine()->getManager()->flush();

        }

        return $this->render('@Client/project/show.html.twig', array(
            'project' => $project,
            'followForm' => $followForm->createView(),
            'participateForm' => $participateForm->createView()
        ));
    }

    /**
     * Displays a form to edit an existing project entity.
     *
     * @Route("/modifier/{id}", name="project_edit")
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
        $editForm = $this->createForm('AppBundle\Form\ProjectType', $project);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $project = $this->projectServices->prePersistEdit($savedProject, $project);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('project_edit', array('id' => $project->getId()));
        }

        return $this->render('@Client/project/edit.html.twig', array(
            'project' => $project,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a project entity.
     *
     * @Route("/supprimer/{id}", name="project_delete")
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

        return $this->redirectToRoute('project_index');
    }

    /**
     * Creates a form to delete a project entity.
     * @param Project $project
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createDeleteForm(Project $project)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('project_delete', array('id' => $project->getId())))
            ->setMethod('POST')
            ->getForm()
            ;
    }

    /**
     * Location.
     *
     * @Route("/geolocalisation", name="project_location")
     * @Method("GET")
     */
    public function locationAction()
    {
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

        $normalizer->setCircularReferenceHandler(function ($object) { return $object; });

        $serializer = new Serializer(array($normalizer), array($encoder));

        return $this->render('@Client/project/location.html.twig', [
            'form' => $this->createForm(LocationType::class)->createView(),
            'projects' => $serializer->serialize($this->getDoctrine()->getRepository(Project::class)->findBy(['isValidated' => true]), 'json')
            ]);
    }
}

<?php

namespace AppBundle\Controller\Client;

use AppBundle\Entity\Category;
use AppBundle\Entity\District;
use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use AppBundle\Form\LocationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Project controller.
 *
 * @Route("projects")
 */
class ProjectController extends Controller
{
    /**
     * Lists all project entities.
     *
     * @Route("/", name="project_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $projects = $this->getDoctrine()->getRepository(Project::class)->findAll();
        $districts = $this->getDoctrine()->getRepository(District::class)->findAll();
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render('@Client/project/list.html.twig', [
            'projects' => $projects,
            'districts' => $districts,
            'categories' => $categories,
        ]);
    }

    /**
     * Creates a new project entity.
     *
     * @Route("/new", name="project_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $project = new Project();
        $project->setInProgress(false);
        $form = $this->createForm('AppBundle\Form\ProjectType', $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

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
     * @Route("show/{id}", name="project_show")
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

            /** @var User $user */
            $user = $this->getUser();

            if (in_array($project, $user->getFollowedProjects()->toArray())){
                $user->removeFollowedProject($project);
            }else{
                $user->addFollowedProject($project);
            }

            $this->getDoctrine()->getManager()->flush();

        }elseif ($participateForm->isSubmitted() && $participateForm->isValid()){

            /** @var User $user */
            $user = $this->getUser();

            if (in_array($project, $user->getParticipatingProject()->toArray())){
                $user->removeParticipatingProject($project);
            }else{
                $user->addParticipatingProject($project);
            }

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
     * @Route("/edit/{id}", name="project_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Project $project
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Project $project)
    {
        $deleteForm = $this->createDeleteForm($project);
        $editForm = $this->createForm('AppBundle\Form\ProjectType', $project);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
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
     * @Route("/{id}", name="project_delete")
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
     * @Route("/location", name="project_location")
     * @Method("GET")
     */
    public function locationAction()
    {
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object;
        });
        $serializer = new Serializer(array($normalizer), array($encoder));

        $projects = $this->getDoctrine()->getRepository(Project::class)->findAll();

        return $this->render('@Client/project/location.html.twig', [
            'form' => $this->createForm(LocationType::class)->createView(),
            'projects' => $serializer->serialize($projects, 'json')
            ]);
    }
}

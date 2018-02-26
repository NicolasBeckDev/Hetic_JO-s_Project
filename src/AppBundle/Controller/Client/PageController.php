<?php

namespace AppBundle\Controller\Client;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        return $this->render('@Client/homepage/homepage.html.twig',[
            'message' => $request->query->get('message') ?? false,
            'type' => $request->query->get('type') ?? false,
            'title' => $request->query->get('title') ?? false,
        ]);
    }

    /**
     * @Route("/a-propos", name="about")
     */
    public function aboutAction()
    {
        return $this->render('@Client/about/about.html.twig');
    }
}

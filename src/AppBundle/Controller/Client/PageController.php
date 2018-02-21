<?php

namespace AppBundle\Controller\Client;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('@Client/homepage/homepage.html.twig');
    }

    /**
     * @Route("/a-propos", name="about")
     */
    public function aboutAction()
    {
        return $this->render('@Client/about/about.html.twig');
    }
}

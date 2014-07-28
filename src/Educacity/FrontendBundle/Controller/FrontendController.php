<?php

namespace Educacity\FrontendBundle\Controller;

class FrontendController extends CustomController
{
    public function indexAction()
    {
        return $this->render('FrontendBundle:Pages:home.html.twig');
    }

    public function aboutAction()
    {
        return $this->render('FrontendBundle:Pages:use-terms.html.twig', array('notHome' => true));
    }
}

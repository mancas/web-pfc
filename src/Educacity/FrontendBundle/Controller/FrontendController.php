<?php

namespace Educacity\FrontendBundle\Controller;

use Educacity\FrontendBundle\Form\Type\SubscriptionType;
use Symfony\Component\HttpFoundation\Request;

class FrontendController extends CustomController
{
    public function indexAction()
    {
        $form = $this->createForm(new SubscriptionType());
        return $this->render('FrontendBundle:Pages:home.html.twig', array('form' => $form->createView()));
    }

    public function aboutAction()
    {
        return $this->render('FrontendBundle:Pages:use-terms.html.twig', array('notHome' => true));
    }

    public function subscribeAction(Request $request)
    {
        $em = $this->getEntityManager();
        $form = $this->createForm(new SubscriptionType());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $subscription = $form->getData();
            $em->persist($subscription);
            $em->flush();

            return $this->render('FrontendBundle:Pages:thanks-for-register.html.twig', array('notHome' => true, 'form' => $form->createView()));
        }

        return $this->redirect($this->generateUrl('frontend_homepage'));
    }
}

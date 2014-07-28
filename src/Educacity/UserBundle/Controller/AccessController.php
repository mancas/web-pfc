<?php

namespace Educacity\UserBundle\Controller;

use Educacity\FrontendBundle\Controller\CustomController;
use Educacity\UserBundle\Entity\FirstUser;
use Educacity\UserBundle\Event\UserEvent;
use Educacity\UserBundle\Event\UserEvents;
use Educacity\UserBundle\Form\Model\Registration;
use Educacity\UserBundle\Form\Type\RegistrationType;
use Symfony\Component\HttpFoundation\Request;

class AccessController extends CustomController
{
    public function registerAction(Request $request)
    {
        $registration = new Registration();
        $form = $this->createForm(new RegistrationType(), $registration);
        $formHandler = $this->get('user.create_user_form_handler');

        if ($formHandler->handle($form, $request)) {
            $user = $registration->getUser();
            $userEvent = new UserEvent($user);
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch(UserEvents::NEW_USER_CREATED, $userEvent);

            return $this->redirect($this->generateUrl('success_register'));
        }

        return $this->render('UserBundle:Commons:registration.html.twig', array('form' => $form->createView()));
    }

    public function loginAction()
    {
        return $this->render('FrontendBundle:Commons:login.html.twig');
    }

    public function successRegisterAction()
    {
        $form = $this->createForm(new ValidatedCodeType());

        return $this->render('UserBundle:Access:success-register.html.twig', array('user' => $this->get('security.context')->getToken()->getUser(), 'form' => $form->createView()));
    }

    public function validateUserAction(Request $request)
    {
        $form = $this->createForm(new ValidatedCodeType());
        $form->handleRequest($request);
        $user = $this->getCurrentUser();
        if ($form->isValid()) {
            $code = $form->get('code')->getData();

            if ($user instanceof User && $user->getValidatedCode() == $code) {
                $em = $this->getEntityManager();
                $user->setValidated(true);
                $em->persist($user);
                $em->flush();
                $this->setTranslatedFlashMessage('Tu cuenta ha sido validada, ya puedes acceder a tu perfil y configurar tus datos.');

                $this->resetToken($user);

                return $this->redirect($this->generateUrl('frontend_homepage'));
            } else {
                $this->setTranslatedFlashMessage('El código introducido no coincide con el que te hemos mandado.', 'error');
            }
        }

        return $this->render('UserBundle:Access:validate-code.html.twig', array('form' => $form->createView(), 'user' => $user));
    }

    public function resendActivationEmailAction()
    {
        $user = $this->getCurrentUser();
        $userEvent = new UserEvent($user);
        $dispatcher = $this->get('event_dispatcher');
        $dispatcher->dispatch(UserEvents::RESEND_ACTIVATION_EMAIL, $userEvent);
        $this->setTranslatedFlashMessage('El correo de activación ha sido reenviado a tu cuenta.');

        return $this->redirect($this->generateUrl('validate_user'));
    }

    public function checkIfEmailIsAvailableAction(Request $request)
    {
        $jsonResponse = json_encode(array('available' => 'false'));
        if ($request->isXmlHttpRequest()) {
            $form = $request->query->get('registration');

            $email = current($form['user']['email']);

            if ($this->checkEmailAvailable($email)) {
                $jsonResponse = json_encode(array('available' => 'true'));
            }
        }

        return $this->getHttpJsonResponse($jsonResponse);
    }

    private function checkEmailAvailable($email)
    {
        $em = $this->getEntityManager();
        $user = $em->getRepository('UserBundle:User')->findOneByEmail($email);

        return !($user instanceof User);
    }

    public function forgotPasswordAction(Request $request)
    {
        $form = $this->createFormBuilder()->add('email', 'email', array('required' => true, 'attr' => array('placeholder' => 'Introduzca su e-mail')))->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEntityManager();
            $data = $form->getData();
            $user = $em->getRepository('UserBundle:User')->findOneBy(array('email' => $data['email']));
            $recoverPassword = new RecoverPassword();
            $recoverPassword->setEmail($user->getEmail());
            $recoverPassword->setSalt($user->getSalt());

            $em->persist($recoverPassword);
            $em->flush();

            $userEvent = new UserEvent($user);
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch(UserEvents::RECOVER_PASSWORD, $userEvent);
            $this->setTranslatedFlashMessage('El correo con las instrucciones para reestablecer tu contraseña ha sido enviado correctamente. Recuerda que dispones de 24 horas a partir de ahora para reestablercerla.');
            return $this->redirect($this->generateUrl('login'));
        }

        return $this->render('UserBundle:Access:forgot-password.html.twig', array('form' => $form->createView()));
    }

    /**
     * @ParamConverter("user", class="UserBundle:User")
     */
    public function changePasswordAction(User $user, Request $request)
    {
        $em = $this->getEntityManager();
        $recoverPassword = $em->getRepository('UserBundle:RecoverPassword')->findOneBy(array('email' => $user->getEmail()));
        if (!$recoverPassword) {
            return $this->redirect($this->generateUrl('frontend_homepage'));
        }

        $now = new \DateTime('now');
        $dateRequest = $recoverPassword->getDateRequest();
        $dateRequest->modify('+1 days');
        if ($dateRequest >  $now) {
            $form = $this->createForm(new RecoverPasswordType());
            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();
                $user->setPassword($data['password']);
                $encoder = $this->get('security.encoder_factory')->getEncoder($user);
                $encodePassword = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                $user->setPassword($encodePassword);

                $em->persist($user);
                $em->remove($recoverPassword);
                $em->flush();

                $this->setTranslatedFlashMessage('Tu contraseña ha sido reestablecida. Ya puedes acceder con normalidad a tu cuenta');
                return $this->redirect($this->generateUrl('login'));
            }

            return $this->render('UserBundle:Access:new-password.html.twig', array('form' => $form->createView(), 'user' => $user));
        } else {
            $this->setTranslatedFlashMessage('Han pasado más de 24 horas desde que solicitaste el cambio de contraseña. Por favor, solicitalo de nuevo.');
            return $this->redirect($this->generateUrl('login'));
        }

    }

    public function subscribeAction(Request $request)
    {
        $email = $request->request->get('email');
        $em = $this->getEntityManager();
        $firstUser = new FirstUser();
        $firstUser->setEmail($email);
        $em->persist($firstUser);
        $em->flush();

        return $this->render('FrontendBundle:Pages:thanks-for-register.html.twig');
    }
}

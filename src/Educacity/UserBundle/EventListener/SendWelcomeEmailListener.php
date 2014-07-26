<?php

namespace Educacity\UserBundle\EventListener;

use Educacity\UserBundle\Entity\User;
use Educacity\UserBundle\Event\UserEvent;
use Educacity\UserBundle\Event\UserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Routing\Router;

class SendWelcomeEmailListener implements EventSubscriberInterface
{
    private $mailer;
    private $templating;
    private $router;
    private $noreply;

    public static function getSubscribedEvents()
    {
        return array(
            UserEvents::NEW_USER_CREATED => 'onNewUser',
            UserEvents::RECOVER_PASSWORD => 'onRecoverPassword'
        );
    }

    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating, Router $router, $noreply)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->router = $router;
        $this->noreply = $noreply;
    }

    public function onNewUser(UserEvent $event)
    {
        $this->sendActivationEmail($event->getUser());
    }

    public function onRecoverPassword(UserEvent $event)
    {
        $this->sendRecoveryPassEmail($event->getUser());
    }

    private function sendActivationEmail(User $user)
    {
        $url = $this->router->generate('validate_user',
            array('salt' => $user->getSalt()), true);

        $messageBody = $this->templating->render('UserBundle:Email:validation.html.twig',
            array('url' => $url));
        $from = $this->noreply;

        $message = \Swift_Message::newInstance()
            ->setSubject('Bienvenido')
            ->setFrom($from)
            ->setTo($user->getEmail())
            ->setBody($messageBody, 'text/html');

        if (!$this->mailer->send($message)) {
            throw new \Exception("Error en el envío del correo de activación");
        }
    }

    private function sendRecoveryPassEmail(User $user)
    {
        $url = $this->router->generate('new_password',
            array('salt' => $user->getSalt()), true);
        $from = $this->noreply;
        $to = $user->getEmail();
        $messageBody = $this->templating->render(
            'UserBundle:Email:recovery-password.html.twig',
            array('url' => $url));
        $message = \Swift_Message::newInstance()
            ->setSubject('Restablecer tu contraseña en Educacity')
            ->setFrom($from)
            ->setTo($to)
            ->setBody($messageBody, 'text/html');
        if (!$this->mailer->send($message)) {
            throw new \Exception("Error en el envío del correo para recuperar la contraseña");
        }
    }

}
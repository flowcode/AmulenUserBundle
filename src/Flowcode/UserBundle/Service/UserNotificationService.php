<?php

namespace Flowcode\UserBundle\Service;

use Flowcode\NotificationBundle\Senders\EmailSenderInterface;
use Flowcode\UserBundle\Entity\UserInterface as User;
/**
 * Description of UserNotificationService
 *
 * @author Juan Manuel Agüero <jaguero@flowcode.com.ar>
 */
class UserNotificationService
{
    /**
     * EmailSender
     * @var EmailSenderInterface
     */
    protected $mailSender;

    protected $container;

    /**
     * [$templating description]
     * @var [type]
     */
    protected $templating;

    public function __construct($container, $mailingService, $templating)
    {
        $this->container = $container;
        $this->mailSender = $mailingService;
        $this->templating = $templating;
    }

    /**
     * Notify Password reset.
     *
     * @param User   $user          [description]
     * @param [type] $plainPassword [description]
     */
    public function notifyPasswordReset(User $user, $plainPassword)
    {
        $fromEmail = $this->container->getParameter("default_mail_from");
        $fromName = $this->container->getParameter("default_mail_from_name");
        $appName = $this->container->getParameter("default_app_name");
        $subject = "[$appName] Cambio de datos";
        $toEmail = $user->getEmail();
        $toName = $user->getUsername();
        $body = $this->container->get('templating')->render('FlowcodeUserBundle:Email:notifyPasswordReset.html.twig', array('user' => $user, 'plainPassword' => $plainPassword));

        $this->mailSender->send($toEmail, $toName, $fromEmail, $fromName, $subject, $body, true);
    }

    /**
     * Notify Login.
     *
     * @param  User   $user [description]
     * @return [type]       [description]
     */
    public function notifyLogin(User $user)
    {
        $fromEmail = $this->container->getParameter("default_mail_from");
        $fromName = $this->container->getParameter("default_mail_from_name");
        $appName = $this->container->getParameter("default_app_name");
        $subject = "[$appName] Bienvenido";
        $toEmail = $user->getEmail();
        $toName = $user->getUsername();
        $body = $this->container->get('templating')->render('FlowcodeUserBundle:Email:notifyRegister.html.twig', array('user' => $user));

        $this->mailSender->send($toEmail, $toName, $fromEmail, $fromName, $subject, $body, true);

    }

    /**
     * Notify register.
     *
     * @param  User   $user [description]
     * @return [type]       [description]
     */
    public function notifyRegister(User $user, $activateAccountLink)
    {
        $fromEmail = $this->container->getParameter("default_mail_from");
        $fromName = $this->container->getParameter("default_mail_from_name");
        $subject = "[" . $fromName . "] Bienvenido";
        $toEmail = $user->getEmail();
        $toName = $user->getUsername();
        $body = $this->container->get('templating')->render('FlowcodeUserBundle:Email:notifyRegister.html.twig', array('user' => $user, 'activateAccountLink' => $activateAccountLink));

        $this->mailSender->send($toEmail, $toName, $fromEmail, $fromName, $subject, $body, true);

    }

}

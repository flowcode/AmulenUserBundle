<?php

namespace Flowcode\UserBundle\Tests\Mocks;

use Flowcode\NotificationBundle\Senders\EmailSenderInterface;
use Flowcode\UserBundle\Entity\UserInterface as User;

class UserNotificationMockService
{
    protected $mailSender;
    protected $container;
    protected $templating;

    public function __construct($container, $mailingService, $templating)
    {
    }
    public function notifyPasswordReset(User $user, $plainPassword)
    {
    }

    public function notifyLogin(User $user)
    {
    }
    public function notifyRegister(User $user, $activateAccountLink)
    {
    }

    public function notifyForgot(User $user, $forgotLink)
    {
    }

    public function notifyRecover(User $user)
    {
    }
}

<?php

namespace Flowcode\UserBundle\Tests\Mocks;

use Flowcode\NotificationBundle\Senders\EmailSenderInterface;
use Flowcode\UserBundle\Entity\UserInterface as User;

class UserNotificationMockService
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
    }

    /**
     * Notify Password reset.
     *
     * @param User   $user          [description]
     * @param [type] $plainPassword [description]
     */
    public function notifyPasswordReset(User $user, $plainPassword)
    {
    }

    /**
     * Notify Login.
     *
     * @param  User   $user [description]
     * @return [type]       [description]
     */
    public function notifyLogin(User $user)
    {

    }

    /**
     * Notify register.
     *
     * @param  User   $user [description]
     * @return [type]       [description]
     */
    public function notifyRegister(User $user, $activateAccountLink)
    {
    }

}

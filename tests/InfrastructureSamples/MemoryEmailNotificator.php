<?php
namespace Extasy\UserProfile\tests\InfrastructureSamples;

use Extasy\UserProfile\Infrastructure\ConfirmOldEmailNotificationInterface;
use Extasy\UserProfile\Infrastructure\ConfirmNewEmailNotificationInterface;
use Extasy\Users\User;

class MemoryEmailNotificator implements ConfirmOldEmailNotificationInterface, ConfirmNewEmailNotificationInterface
{
    protected $user = null;

    public function getUser()
    {
        return $this->user;
    }

    public function notificate(User $user)
    {
        $this->user = $user;
    }
}
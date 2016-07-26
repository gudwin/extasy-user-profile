<?php


namespace Extasy\UserProfile\Infrastructure;


use Extasy\Users\User;

interface ConfirmNewEmailNotificationInterface
{
    public function notificate( User $user );
}
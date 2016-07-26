<?php
namespace Extasy\UserProfile\Infrastructure;

use Extasy\Users\User;

interface ConfirmOldEmailNotificationInterface
{
    public function notificate(User $user);
}
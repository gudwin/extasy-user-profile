<?php


namespace Extasy\UserProfile\Usecases;

use Extasy\UserProfile\Exceptions\ForbiddenException;
use Extasy\Users\User;
class ProfileUsecase
{
    protected function validateUser( User $user ) {
        if ( !empty($user->confirmation_code->getValue()) ) {
            throw new ForbiddenException('Profile action failed. User banned');
        }
    }
}
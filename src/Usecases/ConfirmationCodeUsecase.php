<?php


namespace Extasy\UserProfile\Usecases;

use Extasy\UserProfile\Exceptions\ForbiddenException;
use Extasy\Users\User;
use Extasy\UserProfile\Exceptions\WrongKeyException;

class ConfirmationCodeUsecase extends ProfileUsecase
{
    /**
     * @var User
     */
    protected $user = null;

    protected $confirmationCode = null;

    protected function validateCode()
    {
        $result = $this->user->email_confirmation_code == $this->confirmationCode;

        if ( empty( $this->user->email_confirmation_code->getValue()  )) {
            throw new ForbiddenException('Confirmation code not generated');
        }
        if ( empty( $this->user->new_email->getValue() ) ) {
            throw new ForbiddenException('New email empty');
        }
        if (!$result) {
            throw new WrongKeyException('Confirmation code incorrect');
        }

    }
}
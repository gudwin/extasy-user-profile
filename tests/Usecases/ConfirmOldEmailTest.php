<?php


namespace Extasy\UserProfile\tests\Usecases;

use Extasy\UserProfile\Usecases\ConfirmOldEmail;

class ConfirmOldEmailTest extends ProfileUsecaseTest
{
    /**
     * @expectedException \Extasy\UserProfile\Exceptions\ForbiddenException
     */
    public function testConfirmByBannedUser() {
        $user = $this->userFactory();
        $user->confirmation_code = '123';
        $usecase = new ConfirmOldEmail( $this->repository, $user, self::ConfirmationCode, $this->notificationInterface);
        $usecase->execute();
    }

    /**
     * @expectedException \Extasy\UserProfile\Exceptions\WrongKeyException
     */
    public function testConfirmByWrongCode() {
        $user = $this->userFactory();
        $usecase = new ConfirmOldEmail( $this->repository, $user, 'wrong key', $this->notificationInterface);
        $usecase->execute();
    }
    /**
     * @expectedException \Extasy\UserProfile\Exceptions\ForbiddenException
     */
    public function testConfirmWhenConfirmationCodeEmpty() {
        $user = $this->userFactory();
        $user->email_confirmation_code = '';
        $usecase = new ConfirmOldEmail( $this->repository, $user, self::ConfirmationCode, $this->notificationInterface);
        $usecase->execute();
    }
    /**
     * @expectedException \Extasy\UserProfile\Exceptions\ForbiddenException
     */
    public function testConfirmWhenNewEmailFieldEmpty() {
        $user = $this->userFactory();
        $user->new_email = '';
        $usecase = new ConfirmOldEmail( $this->repository, $user, self::ConfirmationCode, $this->notificationInterface);
        $usecase->execute();
    }
    public function testConfirm() {
        $user = $this->userFactory();
        $usecase = new ConfirmOldEmail( $this->repository, $user, self::ConfirmationCode, $this->notificationInterface);
        $usecase->execute();

        $this->assertNotEmpty( $this->notificationInterface->getUser());
        $this->assertNotEquals( self::ConfirmationCode, $user->email_confirmation_code);
    }
}
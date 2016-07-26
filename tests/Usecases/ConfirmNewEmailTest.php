<?php


namespace Extasy\UserProfile\tests\Usecases;

use Extasy\UserProfile\Usecases\ConfirmNewEmail;
use Extasy\Users\User;


class ConfirmNewEmailTest extends ProfileUsecaseTest
{


    /**
     * @expectedException \Extasy\UserProfile\Exceptions\ForbiddenException
     */
    public function testConfirmByBannedUser() {
        $user = $this->userFactory();
        $user->confirmation_code = '123456';
        //
        $usecase = new ConfirmNewEmail( $this->repository, $user, self::ConfirmationCode );
        $usecase->execute();
    }

    /**
     * @expectedException \Extasy\UserProfile\Exceptions\WrongKeyException
     */
    public function testConfirmWithWrongConfirmationCode() {
        $user = $this->userFactory();
        //
        $usecase = new ConfirmNewEmail( $this->repository, $user, 'wrong confirmation code' );
        $usecase->execute();
    }

    /**
     * @expectedException \Extasy\UserProfile\Exceptions\ForbiddenException
     */
    public function testConfirmWhenConfirmationCodeEmpty() {
        $user = $this->userFactory();
        $user->email_confirmation_code = '';
        //
        $usecase = new ConfirmNewEmail( $this->repository, $user, self::ConfirmationCode);
        $usecase->execute();
    }

    /**
     * @expectedException \Extasy\UserProfile\Exceptions\ForbiddenException
     */
    public function testConfirmWhenNewEmailFieldEmpty() {
        $user = $this->userFactory();
        $user->new_email= '';
        //
        $usecase = new ConfirmNewEmail( $this->repository, $user, self::ConfirmationCode);
        $usecase->execute();
    }
    public function testConfirmEmail() {
        $user = $this->userFactory();
        $usecase = new ConfirmNewEmail( $this->repository, $user, self::ConfirmationCode );
        $usecase->execute();
        //
        $this->assertEmpty( $user->email_confirmation_code->getValue());
        $this->assertEmpty( $user->new_email->getValue());
        $this->assertEquals( self::NewEmail, $user->email->getValue());
    }
}
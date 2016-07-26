<?php
namespace Extasy\UserProfile\tests\Usecases;

use Extasy\UserProfile\Usecases\UpdateEmail;
use Extasy\Users\User;

class UpdateEmailTest extends ProfileUsecaseTest
{

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testUpdateWithInvalidEmail() {
        $user = $this->userFactory();
        //
        $usecase = new UpdateEmail($this->repository, $user, 'not an email', $this->notificationInterface);
        $usecase->execute();
    }

    /**
     * @expectedException \Extasy\UserProfile\Exceptions\ForbiddenException
     */
    public function testUpdateToAlreadyRegisteredEmail()
    {
        $user = new User(['email' => self::NewEmail], $this->configurationRepository);
        $this->repository->insert($user);
        //
        $user = new User(['email' => self::OldEmail], $this->configurationRepository);
        $this->repository->insert($user);
        //
        $usecase = new UpdateEmail($this->repository, $user, self::NewEmail, $this->notificationInterface);
        $usecase->execute();
    }

    /**
     * @expectedException \Extasy\UserProfile\Exceptions\ForbiddenException
     */
    public function testUpdateWithBannedUser() {
        $user = new User(['email' => self::OldEmail,'confirmation_code' => 'blocked user'], $this->configurationRepository);
        $this->repository->insert($user);
        //
        $usecase = new UpdateEmail($this->repository, $user, self::NewEmail, $this->notificationInterface);
        $usecase->execute();
    }

    public function testUpdateMail()
    {
        $user = new User(['email' => self::OldEmail], $this->configurationRepository);
        $this->repository->insert($user);
        //
        $usecase = new UpdateEmail($this->repository, $user, self::NewEmail, $this->notificationInterface);
        $usecase->execute();//
        //
        $user = $this->repository->get( $user->id->getValue());

        $this->assertEquals(self::NewEmail, $user->new_email->getValue());
        $this->assertNotEmpty($user->email_confirmation_code);
    }

}
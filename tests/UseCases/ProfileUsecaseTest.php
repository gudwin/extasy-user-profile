<?php


namespace Extasy\UserProfile\tests\Usecases;

use Extasy\Users\tests\Samples\MemoryUsersRepository;
use Extasy\Users\tests\BaseTest;
use Extasy\Users\User;
use Extasy\UserProfile\tests\InfrastructureSamples\MemoryEmailNotificator;
abstract class ProfileUsecaseTest extends BaseTest
{
    const OldEmail = 'old@email.com';
    const NewEmail = 'new@email.com';
    const ConfirmationCode = '123456';
    /**
     * @var MemoryEmailNotificator
     */
    protected $notificationInterface = null;

    /**
     * @var MemoryUsersRepository
     */
    protected $repository = null;

    public function setUp()
    {
        parent::setUp();
        $this->repository = new MemoryUsersRepository();
        $this->notificationInterface = new MemoryEmailNotificator();
    }

    protected function userFactory() {
        $user = new User([
            'email' => self::OldEmail,
            'new_email' => self::NewEmail,
            'email_confirmation_code' => self::ConfirmationCode
        ], $this->configurationRepository);
        $this->repository->insert( $user );
        return $user;
    }
}
<?php


namespace Extasy\UserProfile\tests\Usecases;

use Extasy\UserProfile\Usecases\UpdatePassword;
use Extasy\Users\User;
class UpdatePasswordTest extends ProfileUsecaseTest
{
    const OldPasswordFixture = 'a123456!';
    const NewPasswordFixture = 'HeloW0rld!';

    /**
     * @expectedException \Extasy\UserProfile\Exceptions\ForbiddenException
     */
    public function testUpdateByBannedUser() {
        $user = $this->userFactory();
        $user->password = self::OldPasswordFixture;
        $user->confirmation_code = '123';
        $usecase = new UpdatePassword( $this->repository, $user, self::OldPasswordFixture, self::NewPasswordFixture );
        $usecase->execute();


    }
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWithWrongOldPassword() {
        $user = $this->userFactory();
        $user->password = self::OldPasswordFixture;
        $usecase = new UpdatePassword( $this->repository, $user, '12345', self::NewPasswordFixture );
        $usecase->execute();
    }
    public function testChangePassword() {
        $user = $this->userFactory();
        $user->password = self::OldPasswordFixture;
        $usecase = new UpdatePassword( $this->repository, $user, self::OldPasswordFixture, self::NewPasswordFixture );
        $usecase->execute();
        //
        $user = $this->repository->get( $user->id->getValue() );

        $this->assertEquals( $user->password->hash( self::NewPasswordFixture), $user->password->getValue());
    }
}
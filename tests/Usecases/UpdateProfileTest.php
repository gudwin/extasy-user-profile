<?php


namespace Extasy\UserProfile\tests\Usecases;

use Extasy\Users\User;
use Extasy\Users\tests\BaseTest;
use Extasy\UserProfile\Usecases\UpdateProfile;

class UpdateProfileTest extends ProfileUsecaseTest
{
    const NewNameFixture = 'New Name';
    const NameFixture = 'Old Name';
    public function setUp()
    {
        parent::setUp();
        $config = $this->configurationRepository->read();
        $config->fields = [
            'name' => '\\Extasy\\Model\\Columns\\Input'
        ];
    }
    protected function updateDataFixture() {
        return [
            'name' => self::NewNameFixture
        ];
    }

    /**
     * @return User
     */
    protected function userFactory() {
        $user = new User(['name' => self::NameFixture], $this->configurationRepository );
        $this->repository->insert( $user );
        return $user ;
    }
    public function testWithFieldsNotAllowedToUpdate() {
        $fields = [
            'email' => 'some@email.em',
            'password' => 'newPass1!',
            'id'=> '3',
            'login' => 'NewLogin'
        ];

        foreach ( $fields as $key => $row ) {
            $data = $this->updateDataFixture();
            $data[ $key ] = $row;
            $user = $this->userFactory();
            $usecase = new UpdateProfile( $this->repository, $user, $data );
            try {
                $usecase->execute();
                $this->fail(sprintf( 'Failed on test with field `%s` ', $key));
            } catch ( \InvalidArgumentException $e ) {

            }
        }

    }

    /**
     * @expectedException \Extasy\UserProfile\Exceptions\ForbiddenException
     */
    public function testUpdateProfileByBannedUser() {
        $user = $this->userFactory();
        $user->confirmation_code = 'banned user';
        $data = $this->updateDataFixture();
        $usecase = new UpdateProfile( $this->repository, $user, $data );
        $usecase->execute();
    }
    public function testUpdateProfile() {
        $user = $this->userFactory();
        $data = $this->updateDataFixture();
        $usecase = new UpdateProfile( $this->repository, $user, $data );
        $usecase->execute();

        $user = $this->repository->get( $user->id->getValue() );
        $this->assertEquals( self::NewNameFixture, $user->name->getValue());


    }
}
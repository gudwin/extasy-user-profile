<?php


namespace Extasy\UserProfile\Usecases;

use Extasy\Usecase\Usecase;
use Extasy\Users\RepositoryInterface;
use Extasy\Users\User;
use \InvalidArgumentException;

class UpdatePassword extends ProfileUsecase
{
    use Usecase;
    /**
     * @var User|null
     */
    protected $user = null;
    protected $oldPassword = '';
    protected $newPassword = '';

    /**
     * @var RepositoryInterface
     */
    protected $repository = null;

    public function __construct( RepositoryInterface $repositoryInterface,  User $user, $oldPassword, $newPassword )
    {
        $this->repository = $repositoryInterface;
        $this->user = $user;
        $this->oldPassword = $oldPassword;
        $this->newPassword = $newPassword;
    }

    protected function action() {
        $this->validateUser( $this->user );

        $passwordValid = ( $this->user->password->hash( $this->oldPassword ) == $this->user->password->getValue());
        if ( !$passwordValid ) {
            throw new InvalidArgumentException('Failed to update password. Old password incorrect');
        }

        $this->user->password->setValue( $this->newPassword );
        $this->repository->update( $this->user );
    }
}
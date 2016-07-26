<?php


namespace Extasy\UserProfile\Usecases;

use Extasy\UserProfile\Exceptions\WrongKeyException;
use Extasy\Usecase\Usecase;
use Extasy\Users\RepositoryInterface;
use Extasy\Users\User;

class ConfirmNewEmail extends ConfirmationCodeUsecase
{
    use Usecase;

    /**
     * @var RepositoryInterface
     */
    protected $repository;

    public function __construct(RepositoryInterface $repositoryInterface, User $user, $confirmationCode)
    {
        $this->repository = $repositoryInterface;
        $this->user = $user;
        $this->confirmationCode = $confirmationCode;
    }

    protected function action()
    {
        $this->validateUser($this->user);
        $this->validateCode();
        $this->user->email = $this->user->new_email->getValue();
        $this->user->email_confirmation_code = '';
        $this->user->new_email = '';
        $this->repository->update($this->user);
    }

}
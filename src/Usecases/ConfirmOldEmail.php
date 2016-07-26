<?php


namespace Extasy\UserProfile\Usecases;

use Extasy\Usecase\Usecase;
use Extasy\UserProfile\Exceptions\ForbiddenException;
use Extasy\UserProfile\Infrastructure\ConfirmNewEmailNotificationInterface;
use Extasy\Users\RepositoryInterface;
use Extasy\Users\User;

class ConfirmOldEmail extends ConfirmationCodeUsecase
{
    use Usecase;

    /**
     * @var ConfirmNewEmailNotificationInterface
     */
    protected $notificationInteface = null;

    /**
     * @var RepositoryInterface
     */
    protected $repository = null;

    public function __construct(
        RepositoryInterface $repositoryInterface,
        User $user,
        $confirmationCode,
        ConfirmNewEmailNotificationInterface $notificationInterface
    ) {
        $this->repository = $repositoryInterface;
        $this->user = $user;
        $this->confirmationCode = $confirmationCode;
        $this->notificationInteface = $notificationInterface;
    }

    protected function action()
    {
        $this->validateUser($this->user);
        $this->validateCode();

        $this->user->email_confirmation_code->generate();
        $this->repository->update($this->user);

        $this->notificationInteface->notificate($this->user);
    }
}
<?php


namespace Extasy\UserProfile\Usecases;

use Extasy\Usecase\Usecase;
use Extasy\UserProfile\Exceptions\ForbiddenException;
use Extasy\Users\RepositoryInterface;
use Extasy\Users\Search\Request;
use Extasy\Users\User;
use Extasy\UserProfile\Infrastructure\ConfirmOldEmailNotificationInterface;

class UpdateEmail extends ProfileUsecase
{
    use Usecase;

    /**
     * @var User
     */
    protected $user = null;


    protected $newEmail = '';

    /**
     * @var ConfirmOldEmailNotificationInterface
     */
    protected $notificationInterface = null;

    /**
     * @var RepositoryInterface
     */
    protected $repositoryInterface = null;

    public function __construct(
        RepositoryInterface $repositoryInterface,
        User $user,
        $newEmail,
        ConfirmOldEmailNotificationInterface $notificationInterface
    ) {
        $this->repositoryInterface = $repositoryInterface;
        $this->user = $user;
        $this->newEmail = $newEmail;
        $this->notificationInterface = $notificationInterface;
    }

    protected function action()
    {
        $this->validateUser($this->user);
        $this->validateEmailAlreadyExists();
        $this->user->new_email = $this->newEmail;
        $this->user->email_confirmation_code->generate();
        $this->repositoryInterface->update($this->user);
        $this->notificationInterface->notificate($this->user);
    }

    protected function validateEmailAlreadyExists()
    {
        $func = function ($condition) {
            $result = $this->repositoryInterface->findOne($condition);
            if (!empty($result)) {
                throw new ForbiddenException('Email already exists');
            }
        };
        $condition = new Request();
        $condition->fields = [
            'email' => $this->newEmail
        ];
        $func($condition);
        $condition->fields = [
            'new_email' => $this->newEmail
        ];
        $func($condition);
    }
}
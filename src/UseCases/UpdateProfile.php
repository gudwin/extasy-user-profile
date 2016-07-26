<?php
namespace Extasy\UserProfile\Usecases;

use Extasy\Usecase\Usecase;
use Extasy\UserProfile\Exceptions\ForbiddenException;
use Extasy\Users\User;
use Extasy\Users\RepositoryInterface;
use \InvalidArgumentException;

class UpdateProfile extends ProfileUsecase
{
    use Usecase;

    protected $user = null;
    /**
     * @var RepositoryInterface
     */
    protected $repository = null;
    protected $newData = [];
    const DissAllowedFields = [
        'id',
        'login',
        'email',
        'password',
        'time_access',
        'registered',
        'confirmation_code',
        'email_confirmation_code',
        'email',
        'new_email'
    ];

    public function __construct(RepositoryInterface $repositoryInterface, User $user, $newData)
    {
        $this->repository = $repositoryInterface;
        $this->user = $user;
        $this->newData = $newData;
    }

    protected function action()
    {
        $this->validateUser($this->user);
        foreach ($this->newData as $key => $row) {
            if (in_array($key, self::DissAllowedFields)) {
                throw new InvalidArgumentException(sprintf('Field `%s` prohibited for updates', $key));
            }
            $this->user->$key = $row;
        }
    }
}
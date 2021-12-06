<?php
declare(strict_types=1);

namespace App\Components\User\Business;

use App\Components\User\Business\Model\createUser;
use App\Components\User\Business\Model\createUserInterface;
use App\Components\User\Business\Model\deleteUser;
use App\Components\User\Business\Model\deleteUserInterface;
use App\Components\User\Business\Model\updateUser;
use App\Components\User\Business\Model\updateUserInterface;
use App\Components\User\Business\Model\validate;
use App\Components\User\Business\Model\validationInterface;
use App\Components\User\Business\Model\Verification\validateEmail;
use App\Components\User\Business\Model\Verification\validateRegistrationPasswords;
use App\Components\User\Business\Model\Verification\validateUpdateEmail;
use App\Components\User\Persistence\EntityManager\UserEntityManagerInterface;
use App\Components\User\Persistence\Repository\UserRepositoryInterface;

class FactoryUser
{
    public function __construct(private UserRepositoryInterface $userRepository,
                                private UserEntityManagerInterface $userEntityManager,
    )
    {
    }

    public function createCreateUser(): createUserInterface
    {
        return new createUser($this->userEntityManager, $this->createRegistrationValidator());
    }

    public function createUpdateUser(): updateUserInterface
    {
        return new updateUser($this->userEntityManager, $this->createUpdateValidator());
    }

    public function createDeleteUser(): deleteUserInterface
    {
        return new deleteUser($this->userEntityManager);
    }

    private function createRegistrationValidator(): validationInterface
    {
        return new validate(
            new validateEmail($this->userRepository),
            new validateRegistrationPasswords(),
        );
    }

    private function createUpdateValidator(): validationInterface
    {
        return new validate(
            new validateUpdateEmail($this->userRepository),
            new validateRegistrationPasswords(),
        );
    }
}
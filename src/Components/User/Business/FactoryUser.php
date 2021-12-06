<?php
declare(strict_types=1);

namespace App\Components\User\Business;

use App\Components\User\Business\Model\createUser;
use App\Components\User\Business\Model\createUserInterface;
use App\Components\User\Business\Model\validateRegistration;
use App\Components\User\Business\Model\validationInterface;
use App\Components\User\Business\Model\Verification\validateEmail;
use App\Components\User\Business\Model\Verification\validateRegistrationPasswords;
use App\Components\User\Persistence\EntityManager\UserEntityManagerInterface;
use App\Components\User\Persistence\Repository\UserRepositoryInterface;

class FactoryUser
{
    public function __construct(private UserRepositoryInterface $userRepository,
                                private UserEntityManagerInterface $userEntityManager,
    )
    {
    }

    public function createRegistrationValidator(): validationInterface
    {
        return new validateRegistration(
            new validateEmail($this->userRepository),
            new validateRegistrationPasswords(),
        );
    }

    public function createCreateUser(): createUserInterface
    {
        return new createUser($this->userEntityManager, $this->createRegistrationValidator());
    }
}
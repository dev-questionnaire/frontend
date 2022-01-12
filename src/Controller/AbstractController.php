<?php
declare(strict_types=1);

namespace App\Controller;

use App\Components\User\Persistence\Mapper\UserMapper;
use App\DataTransferObject\UserDataProvider;
use App\Entity\User;

class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    protected function getUserDataProvider(): UserDataProvider
    {
        $user = $this->getUser();
        $userMapper = new UserMapper();

        if($user instanceof User) {
            return $userMapper->map($user);
        }

        throw new \RuntimeException('user is not logged in');
    }
}
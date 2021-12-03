<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\User\Business\Model\Verification;

use App\Components\Business\Model\Verification\validateEmail;
use App\Components\User\Persistence\DataTransferObject\UserDataProvider;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\Repository\UserRepository;
use App\Entity\User as UserEntity;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use function PHPUnit\Framework\assertCount;

class validateEmailTest extends KernelTestCase
{
    private validateEmail $validateEmail;
    private ?EntityManager $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $userMapper = new UserMapper();
        $userEntityRepository = self::$container->get(\App\Repository\UserRepository::class);
        $userRepository = new UserRepository($userEntityRepository, $userMapper);
        $this->validateEmail = new validateEmail($userRepository);

        $currentTime = new \DateTime();

        $userEntity = new UserEntity();

        $userEntity->setEmail('test@nexus-united.com');
        $userEntity->setPassword('test');
        $userEntity->setRoles(['ROLE_USER']);
        $userEntity->setCreatedAt($currentTime);
        $userEntity->setUpdatedAt($currentTime);

        $this->entityManager->persist($userEntity);
        $this->entityManager->flush();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $connection = $this->entityManager->getConnection();

        $connection->executeQuery('DELETE FROM user');
        $connection->executeQuery('ALTER TABLE user AUTO_INCREMENT=0');

        $connection->close();
        $this->entityManager = null;
    }

    public function testGetErrors(): void
    {
        $userDTO = new UserDataProvider();

        $userDTO->setEmail('email@valantic.com');

        $errors = $this->validateEmail->getErrors($userDTO);

        self::assertEmpty($errors);

        $userDTO->setEmail('test@nexus-united.com');

        $errors = $this->validateEmail->getErrors($userDTO);

        self::assertCount(1, $errors);
        self::assertSame('Email is already taken', $errors[0]);

        $userDTO->setEmail('test@nexus.com');

        $errors = $this->validateEmail->getErrors($userDTO);

        self::assertCount(1, $errors);
        self::assertSame('Email is not valid', $errors[0]);
    }
}
<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\User\Persistence\Mapper;

use App\Components\User\Persistence\Mapper\UserMapper;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserMapperTest extends KernelTestCase
{
    private User $user;
    private UserMapper $userMapper;
    private ?EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->user = new User();
        $this->userMapper = new UserMapper();

        $currentTime = new \DateTime();

        $this->user->setEmail('test@email.com');
        $this->user->setPassword('password');
        $this->user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $this->user->setCreatedAt($currentTime);
        $this->user->setUpdatedAt($currentTime);

        $this->entityManager->persist($this->user);
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

    public function testMap(): void
    {
        $userDTO = $this->userMapper->map($this->user);

        self::assertSame(1, $userDTO->getId());
        self::assertSame('test@email.com', $userDTO->getEmail());
        self::assertSame('password', $userDTO->getPassword());
        self::assertSame('ROLE_USER', $userDTO->getRoles()[0]);
        self::assertSame('ROLE_ADMIN', $userDTO->getRoles()[1]);
    }
}
<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\User\Business;

use App\Components\User\Business\FacadeUser;
use App\GeneratedDataTransferObject\UserDataProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FacadeUserTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;
    private ?FacadeUser $facadeUser;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->facadeUser = self::$container->get(FacadeUser::class);

    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $connection = $this->entityManager->getConnection();

        $connection->executeQuery('DELETE FROM user');
        $connection->executeQuery('ALTER TABLE user AUTO_INCREMENT=0');

        $connection->close();

        $this->facadeUser = null;
        $this->entityManager = null;
    }

    public function testCreatePositiv(): void
    {
        $userDTO = new UserDataProvider();
        $userDTO->setEmail('email@nexus-united.com')
            ->setPassword('!aA45678')
            ->setVerificationPassword('!aA45678')
            ->setRoles(['ROLE_USER'])
            ->setCreatedAt('06.12.2021')
            ->setUpdatedAt('07.12.2021');

        $errors = $this->facadeUser->create($userDTO);
        self::assertEmpty($errors);
    }

    public function testCreateNegativ(): void
    {
        $userDTO = new UserDataProvider();
        $userDTO->setEmail('email@nex.com')
            ->setPassword('45678')
            ->setVerificationPassword('!aA45678')
            ->setRoles(['ROLE_USER'])
            ->setCreatedAt('06.12.2021')
            ->setUpdatedAt('07.12.2021');

        $errors = $this->facadeUser->create($userDTO);
        self::assertNotEmpty($errors);
    }
}
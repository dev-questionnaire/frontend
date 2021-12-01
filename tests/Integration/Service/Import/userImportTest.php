<?php
declare(strict_types=1);

namespace App\Tests\Integration\Service\Import;

use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\Mapper\UserMapperCSV;
use App\Repository\UserRepository;
use App\Service\Import\CSVFileReader;
use App\Service\Import\UserImport;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class userImportTest extends KernelTestCase
{
    private ?EntityManager $entityManager;

    private UserRepository $userRepository;

    private UserImport $userImport;

    protected function setUp(): void
    {
        parent::setUp();
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->userRepository = self::$container->get(UserRepository::class);
        $this->userImport = new UserImport(new UserMapperCSV(), new \App\Components\User\Persistence\Repository\UserRepository($this->userRepository,new UserMapper()),new CSVFileReader(),$this->entityManager);
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

    public function testImport(): void
    {
        $countedImports = $this->userImport->import(__DIR__ . '/user.test.csv');

        self::assertSame(2, $countedImports[0]);
        self::assertSame(3, $countedImports[1]);

        $userList = $this->userRepository->findAll();

        self::assertCount(2, $userList);

        self::assertSame('admin@email.com', $userList[0]->getEmail());
        self::assertTrue(password_verify('admin', $userList[0]->getPassword()));
        self::assertSame('ROLE_ADMIN', $userList[0]->getRoles()[0]);
        self::assertSame('ROLE_USER', $userList[0]->getRoles()[1]);
        self::assertSame('01.12.2021', $userList[0]->getCreatedAt()->format('d.m.Y'));
        self::assertSame('01.12.2021', $userList[0]->getUpdatedAt()->format('d.m.Y'));

        self::assertSame('user@email.com', $userList[1]->getEmail());
        self::assertTrue(password_verify('user', $userList[1]->getPassword()));
        self::assertSame('ROLE_USER', $userList[1]->getRoles()[0]);
        self::assertSame('01.12.2021', $userList[1]->getCreatedAt()->format('d.m.Y'));
        self::assertSame('01.12.2021', $userList[1]->getUpdatedAt()->format('d.m.Y'));
    }
}
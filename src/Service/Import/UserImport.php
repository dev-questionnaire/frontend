<?php
declare(strict_types=1);

namespace App\Service\Import;

use App\GeneratedDataTransferObject\UserDataProvider;
use App\Components\User\Business\Model\UserMapperCSV;
use App\Components\User\Persistence\Repository\UserRepository;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserImport
{
    public function __construct(private UserMapperCSV $userMapper,
                                private UserRepository $userRepository,
                                private CSVFileReader $CSVFileReader,
                                private EntityManagerInterface $entityManager,
    )
    {
    }

    public function import(string $filePath): array
    {
        $countImports = [0, 0];

        $userDTOList = $this->convertToDTO($filePath);

        foreach ($userDTOList as $userDTO) {
            ++$countImports[1];
            $userDTOCheck = $this->userRepository->getByEmail($userDTO->getEmail());

            if (!$userDTOCheck instanceof UserDataProvider) {
                ++$countImports[0];

                $userEntity = new User();
                $userEntity
                    ->setEmail($userDTO->getEmail())
                    ->setRoles($userDTO->getRoles())
                    ->setPassword(password_hash($userDTO->getPassword(), PASSWORD_DEFAULT))
                    ->setCreatedAt(date_create_from_format('d.m.Y', $userDTO->getCreatedAt()))
                    ->setUpdatedAt(date_create_from_format('d.m.Y', $userDTO->getUpdatedAt()));

                $this->entityManager->persist($userEntity);
                $this->entityManager->flush();
            }
        }

        return $countImports;
    }

    private function convertToDTO(string $filePath): array
    {
        $userDTOList = [];

        $records = $this->CSVFileReader->read($filePath);

        foreach ($records as $record) {
            $userDTOList[] = $this->userMapper->map($record);
        }

        return $userDTOList;
    }
}
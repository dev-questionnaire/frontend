<?php
declare(strict_types=1);

namespace App\Components\User\Business\Model;

use App\Components\User\Business\Model\Verification\ValidateCollectionInterface;
use App\DataTransferObject\ErrorDataProvider;
use App\DataTransferObject\UserDataProvider;

class ValidateUpdate implements ValidateInterface
{
    /**
     * @var ValidateCollectionInterface[]
     */
    private array $validateCollection;

    public function __construct(ValidateCollectionInterface...$validateCollection)
    {
        $this->validateCollection = $validateCollection;
    }

    public function getErrors(UserDataProvider $userDTO): ErrorDataProvider
    {
        $errorDataProvider = new ErrorDataProvider();

        foreach ($this->validateCollection as $validateObject) {
            $errorDataProvider = $validateObject->getErrors($userDTO, $errorDataProvider);
        }

        return $errorDataProvider;
    }
}
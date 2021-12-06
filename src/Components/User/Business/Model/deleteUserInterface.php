<?php
declare(strict_types=1);

namespace App\Components\User\Business\Model;

interface deleteUserInterface
{
    public function delete(int $id): void;
}
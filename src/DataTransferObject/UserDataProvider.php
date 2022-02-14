<?php
declare(strict_types=1);

namespace App\DataTransferObject;

use Symfony\Component\Lock\Strategy\UnanimousStrategy;

/**
 * Auto generated data provider
 */
final class UserDataProvider
{
    protected ?int $id;

    protected ?string $email;

    protected ?string $password = '';

    protected ?string $verificationPassword = '';

    protected ?array $roles;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id = null): UserDataProvider
    {
        $this->id = $id;

        return $this;
    }

    public function unsetId(): UserDataProvider
    {
        $this->id = null;

        return $this;
    }

    public function hasId(): bool
    {
        return ($this->id !== null && $this->id !== []);
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email = null): UserDataProvider
    {
        $this->email = $email;

        return $this;
    }

    public function unsetEmail(): UserDataProvider
    {
        $this->email = null;

        return $this;
    }

    public function hasEmail(): bool
    {
        return ($this->email !== null && $this->email !== []);
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password = null): UserDataProvider
    {
        $this->password = $password;

        return $this;
    }

    public function unsetPassword(): UserDataProvider
    {
        $this->password = null;

        return $this;
    }

    public function hasPassword(): bool
    {
        return ($this->password !== null && $this->password !== []);
    }

    public function getVerificationPassword(): ?string
    {
        return $this->verificationPassword;
    }

    public function setVerificationPassword(?string $verificationPassword): void
    {
        $this->verificationPassword = $verificationPassword;
    }

    public function unsetVerificationPassword(): UserDataProvider
    {
        $this->verificationPassword = null;

        return $this;
    }

    public function hasVerificationPassword(): bool
    {
        return ($this->verificationPassword !== null && $this->verificationPassword !== []);
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): UserDataProvider
    {
        $this->roles = $roles;

        return $this;
    }

    public function unsetRoles(): UserDataProvider
    {
        $this->roles = null;

        return $this;
    }

    public function hasRoles(): bool
    {
        return ($this->roles !== null && $this->roles !== []);
    }

    /**
     * @return array
     */
    protected function getElements(): array
    {
        return [
            'id' =>
                [
                    'name' => 'id',
                    'allownull' => true,
                    'default' => '',
                    'type' => 'int',
                    'is_collection' => false,
                    'is_dataprovider' => false,
                    'isCamelCase' => false,
                ],
            'email' =>
                [
                    'name' => 'email',
                    'allownull' => true,
                    'default' => '',
                    'type' => 'string',
                    'is_collection' => false,
                    'is_dataprovider' => false,
                    'isCamelCase' => false,
                ],
            'password' =>
                [
                    'name' => 'password',
                    'allownull' => true,
                    'default' => '',
                    'type' => 'string',
                    'is_collection' => false,
                    'is_dataprovider' => false,
                    'isCamelCase' => false,
                ],
            'verificationPassword' =>
                [
                    'name' => 'verificationPassword',
                    'allownull' => true,
                    'default' => '',
                    'type' => 'string',
                    'is_collection' => false,
                    'is_dataprovider' => false,
                    'isCamelCase' => false,
                ],
            'roles' =>
                [
                    'name' => 'roles',
                    'allownull' => false,
                    'default' => '',
                    'type' => 'array',
                    'is_collection' => false,
                    'is_dataprovider' => false,
                    'isCamelCase' => false,
                ],
        ];
    }
}

<?php
declare(strict_types=1);
namespace App\DataTransferObject;

/**
 * Auto generated data provider
 */
final class UserDataProvider extends \Xervice\DataProvider\Business\Model\DataProvider\AbstractDataProvider implements \Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $email;

    /** @var string */
    protected $password;

    /** @var string */
    protected $verificationPassword = '';

    /** @var array */
    protected $roles;

    /** @var string */
    protected $createdAt = '';

    /** @var string */
    protected $updatedAt = '';


    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @param int $id
     * @return UserDataProvider
     */
    public function setId(?int $id = null)
    {
        $this->id = $id;

        return $this;
    }


    /**
     * @return UserDataProvider
     */
    public function unsetId()
    {
        $this->id = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasId()
    {
        return ($this->id !== null && $this->id !== []);
    }


    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }


    /**
     * @param string $email
     * @return UserDataProvider
     */
    public function setEmail(?string $email = null)
    {
        $this->email = $email;

        return $this;
    }


    /**
     * @return UserDataProvider
     */
    public function unsetEmail()
    {
        $this->email = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasEmail()
    {
        return ($this->email !== null && $this->email !== []);
    }


    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }


    /**
     * @param string $password
     * @return UserDataProvider
     */
    public function setPassword(?string $password = null)
    {
        $this->password = $password;

        return $this;
    }


    /**
     * @return UserDataProvider
     */
    public function unsetPassword()
    {
        $this->password = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasPassword()
    {
        return ($this->password !== null && $this->password !== []);
    }


    /**
     * @return string
     */
    public function getVerificationPassword(): string
    {
        return $this->verificationPassword;
    }


    /**
     * @param string $verificationPassword
     * @return UserDataProvider
     */
    public function setVerificationPassword(string $verificationPassword = '')
    {
        $this->verificationPassword = $verificationPassword;

        return $this;
    }


    /**
     * @return UserDataProvider
     */
    public function unsetVerificationPassword()
    {
        $this->verificationPassword = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasVerificationPassword()
    {
        return ($this->verificationPassword !== null && $this->verificationPassword !== []);
    }


    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }


    /**
     * @param array $roles
     * @return UserDataProvider
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }


    /**
     * @return UserDataProvider
     */
    public function unsetRoles()
    {
        $this->roles = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasRoles()
    {
        return ($this->roles !== null && $this->roles !== []);
    }


    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }


    /**
     * @param string $createdAt
     * @return UserDataProvider
     */
    public function setCreatedAt(string $createdAt = '')
    {
        $this->createdAt = $createdAt;

        return $this;
    }


    /**
     * @return UserDataProvider
     */
    public function unsetCreatedAt()
    {
        $this->createdAt = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasCreatedAt()
    {
        return ($this->createdAt !== null && $this->createdAt !== []);
    }


    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }


    /**
     * @param string $updatedAt
     * @return UserDataProvider
     */
    public function setUpdatedAt(string $updatedAt = '')
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


    /**
     * @return UserDataProvider
     */
    public function unsetUpdatedAt()
    {
        $this->updatedAt = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasUpdatedAt()
    {
        return ($this->updatedAt !== null && $this->updatedAt !== []);
    }


    /**
     * @return array
     */
    protected function getElements(): array
    {
        return array (
          'id' =>
          array (
            'name' => 'id',
            'allownull' => true,
            'default' => '',
            'type' => 'int',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'email' =>
          array (
            'name' => 'email',
            'allownull' => true,
            'default' => '',
            'type' => 'string',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'password' =>
          array (
            'name' => 'password',
            'allownull' => true,
            'default' => '',
            'type' => 'string',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'verificationPassword' =>
          array (
            'name' => 'verificationPassword',
            'allownull' => false,
            'default' => '\'\'',
            'type' => 'string',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'roles' =>
          array (
            'name' => 'roles',
            'allownull' => false,
            'default' => '',
            'type' => 'array',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'createdAt' =>
          array (
            'name' => 'createdAt',
            'allownull' => false,
            'default' => '\'\'',
            'type' => 'string',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'updatedAt' =>
          array (
            'name' => 'updatedAt',
            'allownull' => false,
            'default' => '\'\'',
            'type' => 'string',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
        );
    }
}

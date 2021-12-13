<?php
declare(strict_types=1);
namespace App\DataTransferObject;

/**
 * Auto generated data provider
 */
final class UserExamDataProvider extends \Xervice\DataProvider\Business\Model\DataProvider\AbstractDataProvider implements \Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface
{
    /** @var int */
    protected $id;

    /** @var int */
    protected $userId;

    /** @var int */
    protected $examQuestionId;

    /** @var bool */
    protected $answer;

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
     * @return UserExamDataProvider
     */
    public function setId(?int $id = null)
    {
        $this->id = $id;

        return $this;
    }


    /**
     * @return UserExamDataProvider
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
     * @return int
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }


    /**
     * @param int $userId
     * @return UserExamDataProvider
     */
    public function setUserId(?int $userId = null)
    {
        $this->userId = $userId;

        return $this;
    }


    /**
     * @return UserExamDataProvider
     */
    public function unsetUserId()
    {
        $this->userId = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasUserId()
    {
        return ($this->userId !== null && $this->userId !== []);
    }


    /**
     * @return int
     */
    public function getExamQuestionId(): ?int
    {
        return $this->examQuestionId;
    }


    /**
     * @param int $examQuestionId
     * @return UserExamDataProvider
     */
    public function setExamQuestionId(?int $examQuestionId = null)
    {
        $this->examQuestionId = $examQuestionId;

        return $this;
    }


    /**
     * @return UserExamDataProvider
     */
    public function unsetExamQuestionId()
    {
        $this->examQuestionId = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasExamQuestionId()
    {
        return ($this->examQuestionId !== null && $this->examQuestionId !== []);
    }


    /**
     * @return bool
     */
    public function getAnswer(): ?bool
    {
        return $this->answer;
    }


    /**
     * @param bool $answer
     * @return UserExamDataProvider
     */
    public function setAnswer(?bool $answer = null)
    {
        $this->answer = $answer;

        return $this;
    }


    /**
     * @return UserExamDataProvider
     */
    public function unsetAnswer()
    {
        $this->answer = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasAnswer()
    {
        return ($this->answer !== null && $this->answer !== []);
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
     * @return UserExamDataProvider
     */
    public function setCreatedAt(string $createdAt = '')
    {
        $this->createdAt = $createdAt;

        return $this;
    }


    /**
     * @return UserExamDataProvider
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
     * @return UserExamDataProvider
     */
    public function setUpdatedAt(string $updatedAt = '')
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


    /**
     * @return UserExamDataProvider
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
          'userId' =>
          array (
            'name' => 'userId',
            'allownull' => true,
            'default' => '',
            'type' => 'int',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'examQuestionId' =>
          array (
            'name' => 'examQuestionId',
            'allownull' => true,
            'default' => '',
            'type' => 'int',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'answer' =>
          array (
            'name' => 'answer',
            'allownull' => true,
            'default' => '',
            'type' => 'bool',
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

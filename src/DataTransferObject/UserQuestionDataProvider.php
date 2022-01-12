<?php
declare(strict_types=1);
namespace App\DataTransferObject;

/**
 * Auto generated data provider
 */
final class UserQuestionDataProvider
{
    /** @var int */
    protected $id;

    /** @var int */
    protected $userId;

    /** @var string */
    protected $questionSlug;

    /** @var string */
    protected $examSlug;

    /** @var bool */
    protected $answer = true;

    /** @var string */
    protected $createdAt;

    /** @var string */
    protected $updatedAt;


    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @param int $id
     * @return UserQuestionDataProvider
     */
    public function setId(?int $id = null)
    {
        $this->id = $id;

        return $this;
    }


    /**
     * @return UserQuestionDataProvider
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
     * @return UserQuestionDataProvider
     */
    public function setUserId(?int $userId = null)
    {
        $this->userId = $userId;

        return $this;
    }


    /**
     * @return UserQuestionDataProvider
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
     * @return string
     */
    public function getQuestionSlug(): ?string
    {
        return $this->questionSlug;
    }


    /**
     * @param string $questionSlug
     * @return UserQuestionDataProvider
     */
    public function setQuestionSlug(?string $questionSlug = null)
    {
        $this->questionSlug = $questionSlug;

        return $this;
    }


    /**
     * @return UserQuestionDataProvider
     */
    public function unsetQuestionSlug()
    {
        $this->questionSlug = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasQuestionSlug()
    {
        return ($this->questionSlug !== null && $this->questionSlug !== []);
    }

    /**
     * @return string
     */
    public function getExamSlug(): ?string
    {
        return $this->examSlug;
    }


    /**
     * @param string $examSlug
     * @return UserQuestionDataProvider
     */
    public function setExamSlug(?string $examSlug = null)
    {
        $this->examSlug = $examSlug;

        return $this;
    }


    /**
     * @return UserQuestionDataProvider
     */
    public function unsetExamSlug()
    {
        $this->examSlug = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasExamSlug()
    {
        return ($this->examSlug !== null && $this->examSlug !== []);
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
     * @return UserQuestionDataProvider
     */
    public function setAnswer(?bool $answer = true)
    {
        $this->answer = $answer;

        return $this;
    }


    /**
     * @return UserQuestionDataProvider
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
     * @return UserQuestionDataProvider
     */
    public function setCreatedAt(string $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }


    /**
     * @return UserQuestionDataProvider
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
     * @return UserQuestionDataProvider
     */
    public function setUpdatedAt(string $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


    /**
     * @return UserQuestionDataProvider
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
            'questionSlug' =>
                array (
                    'name' => 'questionSlug',
                    'allownull' => true,
                    'default' => '',
                    'type' => 'string',
                    'is_collection' => false,
                    'is_dataprovider' => false,
                    'isCamelCase' => false,
                ),
            'examSlug' =>
                array (
                    'name' => 'examSlug',
                    'allownull' => true,
                    'default' => '',
                    'type' => 'string',
                    'is_collection' => false,
                    'is_dataprovider' => false,
                    'isCamelCase' => false,
                ),
            'answer' =>
                array (
                    'name' => 'answer',
                    'allownull' => true,
                    'default' => 'null',
                    'type' => 'bool',
                    'is_collection' => false,
                    'is_dataprovider' => false,
                    'isCamelCase' => false,
                ),
            'createdAt' =>
                array (
                    'name' => 'createdAt',
                    'allownull' => false,
                    'default' => '',
                    'type' => 'string',
                    'is_collection' => false,
                    'is_dataprovider' => false,
                    'isCamelCase' => false,
                ),
            'updatedAt' =>
                array (
                    'name' => 'updatedAt',
                    'allownull' => false,
                    'default' => '',
                    'type' => 'string',
                    'is_collection' => false,
                    'is_dataprovider' => false,
                    'isCamelCase' => false,
                ),
        );
    }
}

<?php
declare(strict_types=1);
namespace App\DataTransferObject;

/**
 * Auto generated data provider
 */
final class QuestionDataProvider
{
    /** @var int */
    protected $id;

    /** @var int */
    protected $examId;

    /** @var string */
    protected $question;

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
     * @return QuestionDataProvider
     */
    public function setId(?int $id = null)
    {
        $this->id = $id;

        return $this;
    }


    /**
     * @return QuestionDataProvider
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
    public function getExamId(): ?int
    {
        return $this->examId;
    }


    /**
     * @param int $examId
     * @return QuestionDataProvider
     */
    public function setExamId(?int $examId = null)
    {
        $this->examId = $examId;

        return $this;
    }


    /**
     * @return QuestionDataProvider
     */
    public function unsetExamId()
    {
        $this->examId = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasExamId()
    {
        return ($this->examId !== null && $this->examId !== []);
    }


    /**
     * @return string
     */
    public function getQuestion(): string
    {
        return $this->question;
    }


    /**
     * @param string $question
     * @return QuestionDataProvider
     */
    public function setQuestion(string $question)
    {
        $this->question = $question;

        return $this;
    }


    /**
     * @return QuestionDataProvider
     */
    public function unsetQuestion()
    {
        $this->question = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasQuestion()
    {
        return ($this->question !== null && $this->question !== []);
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
     * @return QuestionDataProvider
     */
    public function setCreatedAt(string $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }


    /**
     * @return QuestionDataProvider
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
     * @return QuestionDataProvider
     */
    public function setUpdatedAt(string $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


    /**
     * @return QuestionDataProvider
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
          'examId' =>
          array (
            'name' => 'examId',
            'allownull' => true,
            'default' => '',
            'type' => 'int',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'question' =>
          array (
            'name' => 'question',
            'allownull' => false,
            'default' => '',
            'type' => 'string',
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

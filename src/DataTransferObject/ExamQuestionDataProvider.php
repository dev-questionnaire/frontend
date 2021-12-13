<?php
declare(strict_types=1);
namespace App\DataTransferObject;

/**
 * Auto generated data provider
 */
final class ExamQuestionDataProvider extends \Xervice\DataProvider\Business\Model\DataProvider\AbstractDataProvider implements \Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface
{
    /** @var int */
    protected $id;

    /** @var int */
    protected $examId;

    /** @var array */
    protected $question;

    /** @var bool */
    protected $correct;

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
     * @return ExamQuestionDataProvider
     */
    public function setId(?int $id = null)
    {
        $this->id = $id;

        return $this;
    }


    /**
     * @return ExamQuestionDataProvider
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
     * @return ExamQuestionDataProvider
     */
    public function setExamId(?int $examId = null)
    {
        $this->examId = $examId;

        return $this;
    }


    /**
     * @return ExamQuestionDataProvider
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
     * @return array
     */
    public function getQuestion(): array
    {
        return $this->question;
    }


    /**
     * @param array $question
     * @return ExamQuestionDataProvider
     */
    public function setQuestion(array $question)
    {
        $this->question = $question;

        return $this;
    }


    /**
     * @return ExamQuestionDataProvider
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
     * @return bool
     */
    public function getCorrect(): ?bool
    {
        return $this->correct;
    }


    /**
     * @param bool $correct
     * @return ExamQuestionDataProvider
     */
    public function setCorrect(?bool $correct = null)
    {
        $this->correct = $correct;

        return $this;
    }


    /**
     * @return ExamQuestionDataProvider
     */
    public function unsetCorrect()
    {
        $this->correct = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasCorrect()
    {
        return ($this->correct !== null && $this->correct !== []);
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
     * @return ExamQuestionDataProvider
     */
    public function setCreatedAt(string $createdAt = '')
    {
        $this->createdAt = $createdAt;

        return $this;
    }


    /**
     * @return ExamQuestionDataProvider
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
     * @return ExamQuestionDataProvider
     */
    public function setUpdatedAt(string $updatedAt = '')
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


    /**
     * @return ExamQuestionDataProvider
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
            'type' => 'array',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'correct' =>
          array (
            'name' => 'correct',
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

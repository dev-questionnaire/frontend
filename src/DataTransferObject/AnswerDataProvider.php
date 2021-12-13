<?php
declare(strict_types=1);
namespace App\DataTransferObject;

/**
 * Auto generated data provider
 */
final class AnswerDataProvider extends \Xervice\DataProvider\Business\Model\DataProvider\AbstractDataProvider implements \Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface
{
    /** @var int */
    protected $id;

    /** @var int */
    protected $questionId;

    /** @var string */
    protected $answer;

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
     * @return AnswerDataProvider
     */
    public function setId(?int $id = null)
    {
        $this->id = $id;

        return $this;
    }


    /**
     * @return AnswerDataProvider
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
    public function getQuestionId(): ?int
    {
        return $this->questionId;
    }


    /**
     * @param int $questionId
     * @return AnswerDataProvider
     */
    public function setQuestionId(?int $questionId = null)
    {
        $this->questionId = $questionId;

        return $this;
    }


    /**
     * @return AnswerDataProvider
     */
    public function unsetQuestionId()
    {
        $this->questionId = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasQuestionId()
    {
        return ($this->questionId !== null && $this->questionId !== []);
    }


    /**
     * @return string
     */
    public function getAnswer(): string
    {
        return $this->answer;
    }


    /**
     * @param string $answer
     * @return AnswerDataProvider
     */
    public function setAnswer(string $answer)
    {
        $this->answer = $answer;

        return $this;
    }


    /**
     * @return AnswerDataProvider
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
     * @return bool
     */
    public function getCorrect(): ?bool
    {
        return $this->correct;
    }


    /**
     * @param bool $correct
     * @return AnswerDataProvider
     */
    public function setCorrect(?bool $correct = null)
    {
        $this->correct = $correct;

        return $this;
    }


    /**
     * @return AnswerDataProvider
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
     * @return AnswerDataProvider
     */
    public function setCreatedAt(string $createdAt = '')
    {
        $this->createdAt = $createdAt;

        return $this;
    }


    /**
     * @return AnswerDataProvider
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
     * @return AnswerDataProvider
     */
    public function setUpdatedAt(string $updatedAt = '')
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


    /**
     * @return AnswerDataProvider
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
          'questionId' =>
          array (
            'name' => 'questionId',
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
            'allownull' => false,
            'default' => '',
            'type' => 'string',
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

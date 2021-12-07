<?php
declare(strict_types=1);
namespace App\DataTransferObject;

/**
 * Auto generated data provider
 */
final class QuestionDataProvider extends \Xervice\DataProvider\Business\Model\DataProvider\AbstractDataProvider implements \Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $name;

    /** @var array */
    protected $answers;

    /** @var string */
    protected $createdAt = '';

    /** @var string */
    protected $updatedAt = '';

    /** @var int */
    protected $exam_id;


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
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }


    /**
     * @param string $name
     * @return QuestionDataProvider
     */
    public function setName(?string $name = null)
    {
        $this->name = $name;

        return $this;
    }


    /**
     * @return QuestionDataProvider
     */
    public function unsetName()
    {
        $this->name = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasName()
    {
        return ($this->name !== null && $this->name !== []);
    }


    /**
     * @return array
     */
    public function getAnswers(): array
    {
        return $this->answers;
    }


    /**
     * @param array $answers
     * @return QuestionDataProvider
     */
    public function setAnswers(array $answers)
    {
        $this->answers = $answers;

        return $this;
    }


    /**
     * @return QuestionDataProvider
     */
    public function unsetAnswers()
    {
        $this->answers = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasAnswers()
    {
        return ($this->answers !== null && $this->answers !== []);
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
    public function setCreatedAt(string $createdAt = '')
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
    public function setUpdatedAt(string $updatedAt = '')
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
     * @return int
     */
    public function getExam_id(): ?int
    {
        return $this->exam_id;
    }


    /**
     * @param int $exam_id
     * @return QuestionDataProvider
     */
    public function setExam_id(?int $exam_id = null)
    {
        $this->exam_id = $exam_id;

        return $this;
    }


    /**
     * @return QuestionDataProvider
     */
    public function unsetExam_id()
    {
        $this->exam_id = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasExam_id()
    {
        return ($this->exam_id !== null && $this->exam_id !== []);
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
          'name' =>
          array (
            'name' => 'name',
            'allownull' => true,
            'default' => '',
            'type' => 'string',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'answers' =>
          array (
            'name' => 'answers',
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
          'exam_id' =>
          array (
            'name' => 'exam_id',
            'allownull' => true,
            'default' => '',
            'type' => 'int',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
        );
    }
}

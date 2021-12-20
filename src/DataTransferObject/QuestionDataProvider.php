<?php
declare(strict_types=1);
namespace App\DataTransferObject;

/**
 * Auto generated data provider
 */
final class QuestionDataProvider
{
    /** @var string */
    protected $question;

    /** @var array */
    protected $rightQuestions;

    /** @var array */
    protected $answers;

    /** @var string */
    protected $slug;

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
     * @return array
     */
    public function getRightQuestions(): array
    {
        return $this->rightQuestions;
    }


    /**
     * @param array $rightQuestions
     * @return QuestionDataProvider
     */
    public function setRightQuestions(array $rightQuestions)
    {
        $this->rightQuestions = $rightQuestions;

        return $this;
    }


    /**
     * @return QuestionDataProvider
     */
    public function unsetRightQuestions()
    {
        $this->rightQuestions = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasRightQuestions()
    {
        return ($this->rightQuestions !== null && $this->rightQuestions !== []);
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
    public function getSlug(): string
    {
        return $this->slug;
    }


    /**
     * @param string $slug
     * @return QuestionDataProvider
     */
    public function setSlug(string $slug)
    {
        $this->slug = $slug;

        return $this;
    }


    /**
     * @return QuestionDataProvider
     */
    public function unsetSlug()
    {
        $this->slug = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasSlug()
    {
        return ($this->slug !== null && $this->slug !== []);
    }

    /**
     * @return array
     */
    protected function getElements(): array
    {
        return array (
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
            'rightQuestions' =>
                array (
                    'name' => 'rightQuestions',
                    'allownull' => false,
                    'default' => '',
                    'type' => 'array',
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
            'slug' =>
                array (
                    'name' => 'slug',
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
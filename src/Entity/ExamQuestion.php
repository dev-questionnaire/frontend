<?php

namespace App\Entity;

use App\Repository\ExamQuestionRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=ExamQuestionRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class ExamQuestion
{
    use TimestampableEntity;

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updatedTimestamps(): void
    {
        $this->setUpdatedAt(new \DateTime());

        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTime());
        }
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Exam::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $exam;

    /**
     * @ORM\Column(type="string")
     */
    private $question;

    /**
     * @ORM\Column(type="boolean")
     */
    private $correct;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExam(): ?Exam
    {
        return $this->exam;
    }

    public function setExam(?Exam $exam): self
    {
        $this->exam = $exam;

        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getCorrect(): ?bool
    {
        return $this->correct;
    }

    public function setCorrect(bool $correct): self
    {
        $this->correct = $correct;

        return $this;
    }
}

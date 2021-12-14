<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: QuestionRepository::class), ORM\HasLifecycleCallbacks]
class Question
{
    use TimestampableEntity;

    #[ORM\PrePersist, ORM\PreUpdate]
    public function updatedTimestamps(): void
    {
        $this->setUpdatedAt(new \DateTime());

        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTime());
        }
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $question;

    #[ORM\ManyToOne(targetEntity: Exam::class, inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private $exam;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Answer::class)]
    private $answers;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: UserQuestion::class)]
    private $userQuestions;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->userQuestions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getExam(): ?Exam
    {
        return $this->exam;
    }

    public function setExam(?Exam $exam): self
    {
        $this->exam = $exam;

        return $this;
    }

    /**
     * @return Collection|Answer[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addNewAnswer(Answer $newAnswer): self
    {
        if (!$this->answers->contains($newAnswer)) {
            $this->answers[] = $newAnswer;
            $newAnswer->setQuestion($this);
        }

        return $this;
    }

    public function removeNewAnswer(Answer $newAnswer): self
    {
        if ($this->answers->removeElement($newAnswer)) {
            // set the owning side to null (unless already changed)
            if ($newAnswer->getQuestion() === $this) {
                $newAnswer->setQuestion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserQuestion[]
     */
    public function getUserQuestions(): Collection
    {
        return $this->userQuestions;
    }

    public function addNewUserQuestion(UserQuestion $newUserQuestion): self
    {
        if (!$this->userQuestions->contains($newUserQuestion)) {
            $this->userQuestions[] = $newUserQuestion;
            $newUserQuestion->setQuestion($this);
        }

        return $this;
    }

    public function removeNewUserQuestion(UserQuestion $newUserQuestion): self
    {
        if ($this->userQuestions->removeElement($newUserQuestion)) {
            // set the owning side to null (unless already changed)
            if ($newUserQuestion->getQuestion() === $this) {
                $newUserQuestion->setQuestion(null);
            }
        }

        return $this;
    }
}

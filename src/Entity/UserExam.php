<?php

namespace App\Entity;

use App\Repository\UserExamRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=UserExamRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class UserExam
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
     * @ORM\ManyToOne(targetEntity=ExamQuestion::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $examQuestion;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $answer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExamQuestion(): ?ExamQuestion
    {
        return $this->examQuestion;
    }

    public function setExamQuestion(?ExamQuestion $examQuestion): self
    {
        $this->examQuestion = $examQuestion;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAnswer(): ?bool
    {
        return $this->answer;
    }

    public function setAnswer(?bool $answer): self
    {
        $this->answer = $answer;

        return $this;
    }
}

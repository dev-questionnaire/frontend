<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\QuestionRepository;
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

    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    public ?int $id;

    #[ORM\Column(length: 255)]
    private ?string $question;

    #[ORM\ManyToOne, ORM\JoinColumn(nullable: false)]
    private Exam $exam;

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
}
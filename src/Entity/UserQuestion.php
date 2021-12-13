<?php

namespace App\Entity;

use App\Repository\UserQuestionRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: UserQuestionRepository::class), ORM\HasLifecycleCallbacks]
class UserQuestion
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
    private ?int $id;

    #[ORM\ManyToOne, ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\ManyToOne, ORM\JoinColumn(nullable: false)]
    private Question $question;

    #[ORM\Column(nullable: true)]
    private ?bool $answer;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }
}

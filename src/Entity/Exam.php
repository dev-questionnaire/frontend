<?php

namespace App\Entity;

use App\Repository\ExamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: ExamRepository::class), ORM\HasLifecycleCallbacks]
class Exam
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

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\OneToMany(mappedBy: 'exam', targetEntity: Question::class)]
    private $questions;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addNewQuestion(Question $newQuestion): self
    {
        if (!$this->questions->contains($newQuestion)) {
            $this->questions[] = $newQuestion;
            $newQuestion->setExam($this);
        }

        return $this;
    }

    public function removeNewQuestion(Question $newQuestion): self
    {
        if ($this->questions->removeElement($newQuestion)) {
            // set the owning side to null (unless already changed)
            if ($newQuestion->getExam() === $this) {
                $newQuestion->setExam(null);
            }
        }

        return $this;
    }
}

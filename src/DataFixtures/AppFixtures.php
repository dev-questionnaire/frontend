<?php

namespace App\DataFixtures;

use App\Entity\Exam;
use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private array $entityList = [];

    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadUser();
        $this->loadExamAndQuestionAndAnswer();

        foreach ($this->entityList as $entity) {
            $manager->persist($entity);
        }

        $manager->flush();
    }

    private function loadUser(): void
    {
        $user = new User();

        $user
            ->setEmail('admin@email.com')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user, 'admin')
        );

        $this->entityList[] = $user;

        $user = new User();

        $user
            ->setEmail('user@email.com')
            ->setRoles(['ROLE_USER']);

        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user, 'user')
        );

        $this->entityList[] = $user;
    }

    private function loadExamAndQuestionAndAnswer(): void
    {
        $exam = new Exam();

        $exam
            ->setName('SOLID');

        $this->entityList[] = $exam;

        $question = new Question();
        $question
            ->setExam($exam)
            ->setQuestion('What does S in SOLID stand for?');

        $this->entityList[] = $question;

        $answer = new Answer();
        $answer
            ->setQuestion($question)
            ->setAnswer('Single possibility')
            ->setCorrect(true);

        $this->entityList[] = $answer;

        $answer = new Answer();
        $answer
            ->setQuestion($question)
            ->setAnswer('Single like a pringle')
            ->setCorrect(false);

        $this->entityList[] = $answer;
    }

}

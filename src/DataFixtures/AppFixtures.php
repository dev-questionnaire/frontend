<?php

namespace App\DataFixtures;

use App\Entity\Exam;
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
        $this->loadExamAndQuestions();

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

    private function loadExamAndQuestions(): void
    {
        $exam = new Exam();

        $exam
            ->setName('SOLID');

        $this->entityList[] = $exam;

        $exam = new Exam();

        $exam
            ->setName('Testing');

        $this->entityList[] = $exam;
    }
}

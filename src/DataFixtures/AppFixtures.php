<?php

namespace App\DataFixtures;

use App\Entity\Exam;
use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\User;
use App\Entity\UserQuestion;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher,
        private UserRepository              $userRepository,
    )
    {
    }

    public function load(ObjectManager $manager, array $options = []): void
    {
        if (empty($options)) {
            $this->loadUser($manager);

            $user = $this->userRepository->findOneBy(['email' => 'user@email.com']);

            $this->loadUserQuestion($manager, $user);
        } elseif ($options['user'] === true) {
            $this->loadUser($manager);
        }
    }

    private function loadUser(ObjectManager $manager): void
    {
        $user = new User();
        $entityList = [];

        $user
            ->setEmail('admin@email.com')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user, 'admin')
        );

        $entityList[] = $user;

        $user = new User();

        $user
            ->setEmail('user@email.com')
            ->setRoles(['ROLE_USER']);

        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user, 'user')
        );

        $entityList[] = $user;

        foreach ($entityList as $entity) {
            $manager->persist($entity);
        }

        $manager->flush();
    }

    private function loadUserQuestion(ObjectManager $manager, User $userEntity): void
    {
        $userQuestion = new UserQuestion();
        $entityList = [];

        $userQuestion
            ->setAnswer(true)
            ->setUser($userEntity)
            ->setQuestionSlug('question_1')
            ->setExamSlug('exam');

        $entityList[] = $userQuestion;

        $userQuestion = new UserQuestion();

        $userQuestion
            ->setAnswer(false)
            ->setUser($userEntity)
            ->setQuestionSlug('question_2')
            ->setExamSlug('exam');

        $entityList[] = $userQuestion;

        $userQuestion = new UserQuestion();

        $userQuestion
            ->setAnswer(null)
            ->setUser($userEntity)
            ->setQuestionSlug('question_3')
            ->setExamSlug('exam');

        $entityList[] = $userQuestion;

        foreach ($entityList as $entity) {
            $manager->persist($entity);
        }

        $manager->flush();
    }
}

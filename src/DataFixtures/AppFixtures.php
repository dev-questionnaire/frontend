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

/** @psalm-suppress PropertyNotSetInConstructor */
class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher,
        private UserRepository              $userRepository,
    )
    {
    }

    /**
     * @param bool[] $options
     */
    public function load(ObjectManager $manager, array $options = []): void
    {
        $this->loadUser($manager);

        if (isset($options['test']) && $options['test'] === true) {

            $user = $this->userRepository->findOneBy(['email' => 'user@valantic.com']);

            if(!$user instanceof User) {
                throw new \PDOException("No User Found");
            }

            $this->loadUserQuestion($manager, $user);
        }
    }

    private function loadUser(ObjectManager $manager): void
    {
        $user = new User();
        $entityList = [];

        $user
            ->setEmail('admin@valantic.com')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user, 'admin')
        );

        $entityList[] = $user;

        $user = new User();

        $user
            ->setEmail('user@valantic.com')
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
            ->setAnswers(["Solid" => false, "Sexy_Programming" => false, "Single_possibility" => true, "Single_like_a_pringle" => false])
            ->setUser($userEntity)
            ->setQuestionSlug('s_in_solid')
            ->setExamSlug('solid');

        $entityList[] = $userQuestion;

        $userQuestion = new UserQuestion();

        $userQuestion
            ->setAnswers(['Open_relation' => true, 'Oral__ex' => false, 'Open_close' => false, 'Opfer' => false])
            ->setUser($userEntity)
            ->setQuestionSlug('o_in_solid')
            ->setExamSlug('solid');

        $entityList[] = $userQuestion;

        $userQuestion = new UserQuestion();

        $userQuestion
            ->setAnswers(null)
            ->setUser($userEntity)
            ->setQuestionSlug('harun_alter')
            ->setExamSlug('harun');

        $entityList[] = $userQuestion;

        foreach ($entityList as $entity) {
            $manager->persist($entity);
        }

        $manager->flush();
    }
}

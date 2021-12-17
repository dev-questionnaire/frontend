<?php

namespace App\Components\Exam\Communication;

use App\Components\Exam\Persistence\Repository\ExamRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExamController extends AbstractController
{
    public function __construct(
        private ExamRepositoryInterface $examRepository,
    )
    {
    }

    #[Route("/", name: "app_exam")]
    public function index(): Response
    {
        $examDataProviderList = $this->examRepository->getAll();

        return $this->render('exam/exam.html.twig', [
            'examList' => $examDataProviderList,
        ]);
    }
}

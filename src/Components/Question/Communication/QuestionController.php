<?php

namespace App\Components\Question\Communication;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    public function __construct(

    )
    {
    }

    /**
     * @Route ("/exam/{exam}/question/{question}", name="app_question")
     */
    public function index(string $exam, int $question = 0): Response
    {
        return $this->render('question/question.html.twig', [
            'question' => 'Empty',
            'name' => $exam,
            'nextQuestion' => $question+1,
        ]);
    }
}

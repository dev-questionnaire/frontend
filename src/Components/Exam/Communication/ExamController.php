<?php

namespace App\Components\Exam\Communication;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExamController extends AbstractController
{
    /**
     * @Route ("/", name="app_exam")
     */
    public function index(): Response
    {
        return $this->render('exam/exam.html.twig', [
            'controller_name' => 'ExamController',
        ]);
    }
}

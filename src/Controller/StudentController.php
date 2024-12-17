<?php

namespace App\Controller;

use App\Repository\StudentRepository;
use App\service\StudentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
    private StudentService $studentService;
    
    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }


    #[Route('/students', name: 'app_student', methods:["GET"])]
    public function index(): Response
    {
       $response = $this->studentService->getAllStudents();

       return $this->json($response);
    }

    #[Route('/students/{id}', name: 'app_student_id', methods:["GET"])]
    public function getStudent(int $id): Response
    {
        $response = $this->studentService->getStudentById($id);
        return $this->json($response);
    }
}
    
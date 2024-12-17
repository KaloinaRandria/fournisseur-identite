<?php

namespace App\Controller;

use App\Repository\SemesterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SemesterController extends AbstractController
{
    #[Route('/semester', name: 'app_semester',methods:"GET")]
    public function index(SemesterRepository $repository): Response
    {
        return $this->json($repository->findAll());
    }
}

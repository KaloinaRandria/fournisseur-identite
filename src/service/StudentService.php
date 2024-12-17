<?php

namespace App\service;

use App\Repository\StudentRepository;

class StudentService
{
    private StudentRepository $studentRepository;

    public function __construct(StudentRepository $studentRepository) {
        $this->studentRepository = $studentRepository;
    }

    public function getAllStudents() : array {
        $students = $this->studentRepository->findAll();
        $data = [];

        foreach($students as $student) {
            $data[] = [
                'id' => $student->getId(),
                'last_name' => $student->getLastName(),
                'first_name' => $student->getFirstName(),
                'date_birth' => $student->getDateBirth()
            ];
        }
        return [
            'status' => "success",
            'data' => $data,
            'error' => null
        ];
    }

    public function getStudentById(int $id) : array {
        $student = $this->studentRepository->find($id);
        if(!$student) {
            return [
                'status' => "error",
                'data' => null,
                'error' => "Student not found"
            ];
        }

        return [
            'status' => 'success',
            'data' => [
                'id' => $student->getId(),
                'last_name' => $student->getLastName(),
                'first_name' => $student->getFirstName(),
                'date_birth' => $student->getDateBirth()
            ],
            'error' => null,
        ];
    }
}
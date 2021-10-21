<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function PHPUnit\Framework\throwException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class StudentController extends AbstractController
{
    #[Route('/student', name: 'student_index')]
    public function index(): Response
    {
        $students = $this->getDoctrine()->getRepository(Student::class)->findAll();
        if ($students == null) {
            $this->addFlash('Error', 'student List is empty');
        }
        return $this->render('student/index.html.twig', [
            'students' => $students,
        ]);
    }

    #[Route('/student/detail/{id}', name: 'student_detail')]
    public function studentDetail($id)
    {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
        if ($student === null) {
            $this->addFlash('Error', 'Undefined student!');
            return $this->redirectToRoute('student_index');
        } else {
            return $this->render(
                'student/detail.html.twig',
                [
                    'student' => $student
                ]
            );
        }
    }
    #[Route('/student/delete/{id}', name: 'student_delete')]
    public function studentDetele($id)
    {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
        if ($student === null) {
            $this->addFlash('Error', 'Undefined Student!');
        } else {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($student);
            $manager->flush();
            $this->addFlash('Success', 'Student has been deleted!');
        }
        return $this->redirectToRoute('student_index');
    }

    #[Route('/student/add', name: 'student_add')]
    public function studentAdd(Request $request)
    {
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // B1: Lay anh tu file upload
            $image = $student->getAvatar();
            // B2: tao ten moi cho anh => ten file anh la duy nhat
            $imageName = uniqid(); // unique ID
            // B3: lay ra duoi cua anh
            $imageExtension = $image->guessExtension();
            // B4: Merge ten moi + duoi cua anh
            $imageName = $imageName . '.' . $imageExtension;
            // B5: di chuyen file anh upload vao thu muc chi dinh
            try {
                $image->move(
                    $this->getParameter('student_avatar'),
                    $imageName
                    // can khai bao tham so duong dan cua thu muc
                    // cho student_AgetAvatar o file config/services.yaml 
                );
            } catch (FileException $e) {
                throwException($e);
            }
            // B6: Luu ten anh vao database
            $student->setAvatar($imageName);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($student);
            $manager->flush();
            $this->addFlash('Success', 'student has been added successfully!');
            return $this->redirectToRoute('student_index');
        }
        return $this->render(
            'student/add.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    #[Route('/student/edit/{id}', name: 'student_edit')]
    public function studentEdit(Request $request, $id)
    {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // B1: Lay du lieu anh tu form
            $file = $form['avatar']->getData();
            // kiem tra nguoi dung co chon anh hay khong
            if ($file != null) {
                // B1: Lay anh tu file upload
                $image = $student->getAvatar();
                // B2: tao ten moi cho anh => ten file anh la duy nhat
                $imageName = uniqid(); // unique ID
                // B3: lay ra duoi cua anh
                $imageExtension = $image->guessExtension();
                // B4: Merge ten moi + duoi cua anh
                $imageName = $imageName . '.' . $imageExtension;
                // B5: di chuyen file anh upload vao thu muc chi dinh
                try {
                    $image->move(
                        $this->getParameter('student_avatar'),
                        $imageName
                        // can khai bao tham so duong dan cua thu muc
                        // cho student_AgetAvatar o file config/services.yaml 
                    );
                } catch (FileException $e) {
                    throwException($e);
                }
                // B6: Luu ten anh vao database
                $student->setAvatar($imageName);
            }

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($student);
            $manager->flush();
            $this->addFlash('Success', 'Edit student has been added successfully!');
            return $this->redirectToRoute('student_index');
        }
        return $this->render(
            'student/edit.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }
}

<?php

namespace App\Controller;

use App\Entity\Course;
use App\Form\CourseType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CourseController extends AbstractController
{
    #[Route('/course', name: 'course_index')]
    public function index(): Response
    {
        $course = $this->getDoctrine()->getRepository(Course::class)->findAll();
        if($course == null){
            $this->addFlash('Error', 'Course List is empty');
        }
        return $this->render('course/index.html.twig', [
            'course' => $course,
        ]);
    }

    /**
     * @Route("/course/detail/{id}", name="course_detail")
     */
    public function courseDetail($id){
        $aCourse= $this->getDoctrine()->getRepository(Course::class)->find($id);
        if ($aCourse == null) {
            $this->addFlash('Error', 'Course not found');
            return $this->redirectToRoute('course_index');
        } else {
            return $this->render(
                'course/detail.html.twig',
                [
                    'aCourse' => $aCourse
                ]
            );
        }
    }

    /**
     * @Route("/category/add", name="course_add")
     */
    public function courseAdd(Request $request){
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($course);
            $manager->flush();
            $this->addFlash('Success', "Add Course successfully !");
            return $this->redirectToRoute('course_index');
        }
        return $this->render(
            "course/add.html.twig",
            [
                'form' => $form->createView()
            ]
        );
    }

     /**
     * @Route("/course/edit/{id}", name="course_edit")
     */
    public function courseEdit(Request $request, $id){
        $course = $this->getDoctrine()->getRepository(Course::class)->find($id);
        if ($course == null) {
            $this->addFlash('Error', 'Course not found');
            return $this->redirectToRoute('course_index');
        } else {
            $form = $this->createForm(CourseType::class, $course);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($course);
                $manager->flush();
                $this->addFlash('Success', "Edit Course successfully !");
                return $this->redirectToRoute('course_index');
            }
            return $this->render(
                "category/edit.html.twig",
                [
                    'form' => $form->createView()
                ]
            );
        }
    }

    /**
     * @Route("category/delete/{id}", name="course_delete")
     */
    public function deleteCourse($id)
    {
        $course = $this->getDoctrine()->getRepository(Course::class)->find($id);
        if ($course == null) {
            $this->addFlash('Error', 'Course not found');
        } else {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($course);
            $manager->flush();
            $this->addFlash('Success', 'Course has been deleted');
        }
        return $this->redirectToRoute('course_index');
    }
}

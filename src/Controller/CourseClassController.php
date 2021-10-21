<?php

namespace App\Controller;

use App\Entity\CourseClass;
use App\Form\CourseClassType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CourseClassController extends AbstractController
{
    /**
     * @Route("/courseClass", name="course_class_index")
     */
    public function courseClassIndex(): Response
    {
        $courseClass = $this->getDoctrine()->getRepository(CourseClass::class)->findAll();
        if($courseClass == null){
            $this->addFlash('Error', 'Class list is empty!');
        }
        return $this->render(
            'course_class/index.html.twig', 
            [
            'courseClass' => $courseClass,
            ]
        );
    }

    /**
     * @Route("/courseClass/detail/{id}", name="course_class_detail")
     */
    public function courseClassDetail($id){
        $aCourseClass= $this->getDoctrine()->getRepository(CourseClass::class)->find($id);
        if ($aCourseClass == null) {
            $this->addFlash('Error', 'Class not found');
            return $this->redirectToRoute('course_class_index');
        } else {
            return $this->render(
                'course_class/detail.html.twig',
                [
                    'aCourseClass' => $aCourseClass
                ]
            );
        }
    }

    /**
     * @Route("/courseClass/delete/{id}", name="course_class_delete")
     */
    public function courseClassDelete($id){
        $aCourseClass= $this->getDoctrine()->getRepository(CourseClass::class)->find($id);
        if ($aCourseClass == null) {
            $this->addFlash('Error', 'Class not found');
        } else {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($aCourseClass);
            $manager->flush();
            $this->addFlash('Success', 'Book has been deleted');
        }
        return $this->redirectToRoute('course_class_index');
    }

    /**
     * @Route("/courseClass/add", name="course_class_add")
     */
    public function courseClassAdd(Request $request){
        $courseClass = new CourseClass();
        $form = $this->createForm(CourseClassType::class, $courseClass);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($courseClass);
            $manager->flush();
            $this->addFlash('Success', "Add book successfully !");
            return $this->redirectToRoute('course_class_index');
        }
        return $this->render(
            "course_class/add.html.twig",
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/courseClass/edit/{id}", name="course_class_edit")
     */
    public function courseClassEdit(Request $request, $id){
        $aCourseClass = $this->getDoctrine()->getRepository(CourseClass::class)->find($id);
        if ($aCourseClass == null) {
            $this->addFlash('Error', 'Class not found');
            return $this->redirectToRoute('course_class_index');
        } else {
            $form = $this->createForm(CourseClassType::class, $aCourseClass);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($aCourseClass);
                $manager->flush();
                $this->addFlash('Success', "Edit book successfully !");
                return $this->redirectToRoute('course_class_index');
            }
            return $this->render(
                "course_class/edit.html.twig",
                [
                    'form' => $form->createView()
                ]
            );
        }
    }
}

<?php

namespace App\Controller;

use App\Entity\CourseClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourseClassController extends AbstractController
{
    #[Route('/courseClass', name: 'course_class_index')]
    public function courseClassIndex(): Response
    {
        $courseClass = $this->getDoctrine()->getRepository(CourseClass::class)->findAll();
        if($courseClass == null){
            $this->addFlash('Error', 'Class list is empty!');
        }
        return $this->render('course_class/index.html.twig', [
            'courseClass' => 'courseClass',
        ]);
    }

    /**
     * @Route('/courseClass/detail/{id}, name="course_class_detail")
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
}

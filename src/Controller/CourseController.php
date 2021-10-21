<?php

namespace App\Controller;

use App\Entity\Course;
use App\Form\CourseType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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
     * @Route("/course/add", name="course_add")
     */
    public function courseAdd(Request $request){
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //code xử lý ảnh upload
            //B1: lấy ảnh từ file upload
            $image = $course->getImage();
            //B2: tạo tên mới cho ảnh => tên file ảnh là duy nhất
            $imgName = uniqid(); //unique ID
            //B3: lấy ra phần đuôi (extension) của ảnh
            $imgExtension = $image->guessExtension();
            //B4: gộp tên mới + đuôi tạo thành tên file ảnh hoàn thiện
            $imageName = $imgName . "." . $imgExtension;
            //B5: di chuyển file ảnh upload vào thư mục chỉ định
            try {
                $image->move(
                    $this->getParameter('course_image'),
                    $imageName
                    //Lưu ý: cần khai báo tham số đường dẫn của thư mục
                    //cho "book_cover" ở file config/services.yaml
                );
            } catch (FileException $e) {

            }
            //B6: lưu tên vào database
            $course->setImage($imageName);


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
                //code xử lý ảnh upload
                //B1: lấy dữ liệu ảnh từ form 
                $file = $form['image']->getData();
                //B2: check xem file ảnh upload có null không
                if ($file != null) {
                    //B3: lấy ảnh từ file upload
                    $image = $course->getImage();
                    //B4: tạo tên mới cho ảnh => tên file ảnh là duy nhất
                    $imgName = uniqid(); //unique ID
                    //B5: lấy ra phần đuôi (extension) của ảnh
                    $imgExtension = $image->guessExtension();
                    //B6: gộp tên mới + đuôi tạo thành tên file ảnh hoàn thiện
                    $imageName = $imgName . "." . $imgExtension;
                    //B7: di chuyển file ảnh upload vào thư mục chỉ định
                    try {
                        $image->move(
                            $this->getParameter('course_image'),
                            $imageName
                            //Lưu ý: cần khai báo tham số đường dẫn của thư mục
                            //cho "book_cover" ở file config/services.yaml
                        );
                    } catch (FileException $e) {
                        
                    }
                    //B8: lưu tên vào database
                    $course->setImage($imageName);
                }


                $manager = $this->getDoctrine()->getManager();
                $manager->persist($course);
                $manager->flush();
                $this->addFlash('Success', "Edit Course successfully !");
                return $this->redirectToRoute('course_index');
            }
            return $this->render(
                "course/edit.html.twig",
                [
                    'form' => $form->createView()
                ]
            );
        }
    }

    /**
     * @Route("course/delete/{id}", name="course_delete")
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

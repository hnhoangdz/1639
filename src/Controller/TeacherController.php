<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Teacher;
use App\Form\TeacherType;
use Symfony\Component\HttpFoundation\Request;
use function PHPUnit\Framework\throwException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @IsGranted("ROLE_USER")
 */
class TeacherController extends AbstractController
{
    private $hasher;
    public function __construct (UserPasswordHasherInterface $hasher){
        $this->hasher = $hasher;
    }
    #[Route('/teacher', name: 'teacher_index')]
    public function index(): Response
    {
        $teachers = $this->getDoctrine()->getRepository(Teacher::class)->findAll();
        if ($teachers == null) {
            $this->addFlash('Error', 'Teacher List is empty');
        }
        return $this->render('teacher/index.html.twig', [
            'teachers' => $teachers,
        ]);
    }

    #[Route('/teacher/detail/{id}', name: 'teacher_detail')]
    public function teacherDetail($id)
    {
        $teacher = $this->getDoctrine()->getRepository(Teacher::class)->find($id);
        if ($teacher === null) {
            $this->addFlash('Error', 'Undefined teacher!');
            return $this->redirectToRoute('teacher_index');
        } else {
            return $this->render(
                'teacher/detail.html.twig',
                [
                    'teacher' => $teacher
                ]
            );
        }
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/teacher/delete/{id}', name: 'teacher_delete')]
    public function teacherDetele($id)
    {
        $teacher = $this->getDoctrine()->getRepository(Teacher::class)->find($id);
        if ($teacher === null) {
            $this->addFlash('Error', 'Undefined teacher!');
        } else {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($teacher);
            $manager->flush();
            $this->addFlash('Success', 'Teacher has been deleted!');
        }
        return $this->redirectToRoute('teacher_index');
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/teacher/add', name: 'teacher_add')]
    public function teacherAdd(Request $request)
    {
        $teacher = new Teacher();
        $form = $this->createForm(TeacherType::class, $teacher);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // B1: Lay anh tu file upload
            $image = $teacher->getAvatar();
            // B2: tao ten moi cho anh => ten file anh la duy nhat
            $imageName = uniqid(); // unique ID
            // B3: lay ra duoi cua anh
            $imageExtension = $image->guessExtension();
            // B4: Merge ten moi + duoi cua anh
            $imageName = $imageName . '.' . $imageExtension;
            // B5: di chuyen file anh upload vao thu muc chi dinh
            try {
                $image->move(
                    $this->getParameter('teacher_avatar'),
                    $imageName
                    // can khai bao tham so duong dan cua thu muc
                    // cho teacher_AgetAvatar o file config/services.yaml 
                );
            } catch (FileException $e) {
                throwException($e);
            }
            // B6: Luu ten anh vao database
            $teacher->setAvatar($imageName);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($teacher);
            $user = new User();
            $user->setUsername($teacher->getEmail());
            $user->setPassword($this->hasher->hashPassword($user,"123456"));
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('Success', 'Teacher has been added successfully!');
            return $this->redirectToRoute('teacher_index');
        }
        return $this->render(
            'teacher/add.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/teacher/edit/{id}', name: 'teacher_edit')]
    public function teacherEdit(Request $request, $id)
    {
        $teacher = $this->getDoctrine()->getRepository(Teacher::class)->find($id);
        $form = $this->createForm(TeacherType::class, $teacher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // B1: Lay du lieu anh tu form
            $file = $form['avatar']->getData();
            // kiem tra nguoi dung co chon anh hay khong
            if ($file != null) {
                // B1: Lay anh tu file upload
                $image = $teacher->getAvatar();
                // B2: tao ten moi cho anh => ten file anh la duy nhat
                $imageName = uniqid(); // unique ID
                // B3: lay ra duoi cua anh
                $imageExtension = $image->guessExtension();
                // B4: Merge ten moi + duoi cua anh
                $imageName = $imageName . '.' . $imageExtension;
                // B5: di chuyen file anh upload vao thu muc chi dinh
                try {
                    $image->move(
                        $this->getParameter('teacher_avatar'),
                        $imageName
                        // can khai bao tham so duong dan cua thu muc
                        // cho teacher_AgetAvatar o file config/services.yaml 
                    );
                } catch (FileException $e) {
                    throwException($e);
                }
                // B6: Luu ten anh vao database
                $teacher->setAvatar($imageName);
            }

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($teacher);
            $manager->flush();
            $this->addFlash('Success', 'Edit teacher has been added successfully!');
            return $this->redirectToRoute('teacher_index');
        }
        return $this->render(
            'teacher/edit.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }
}

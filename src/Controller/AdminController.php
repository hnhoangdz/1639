<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\AdminType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @IsGranted("ROLE_USER")
 */
class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_index')]
    public function adminIndex(): Response
    {
        $admin = $this->getDoctrine()->getRepository(Admin::class)->findAll();
        return $this->render(
            'admin/index.html.twig',
            [
                'admins' => $admin
            ]
        );
    }

    #[Route('/admin/detail/{id}', name: 'admin_detail')]
    public function adminDetail($id)
    {
        $admin = $this->getDoctrine()->getRepository(Admin::class)->find($id);
        if ($admin == null) {
            $this->addFlash('Error', 'Admin not found !');
            return $this->redirectToRoute('admin_index');
        } else { //$author != null
            return $this->render(
                'admin/detail.html.twig',
                [
                    'admin' => $admin
                ]
            );
        }
    }
    
    /**
     * @Route("admin/delete/{id}", name="admin_delete")
     */
    public function deleteAdmin($id)
    {
        $admin = $this->getDoctrine()->getRepository(Admin::class)->find($id);
        if ($admin == null) {
            $this->addFlash('Error', 'Admin not found !');
        } else { 
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($admin);
            $manager->flush();
            $this->addFlash('Success', 'Admin has been deleted !');
        }
        return $this->redirectToRoute('admin_index');
    }

    /**
     * @Route("admin/add", name="admin_add")
     */
    public function addAdmin(Request $request)
    {
        $admin = new Admin();
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //code xử lý ảnh upload
            //B1: lấy ảnh từ file upload
            $image = $admin->getAvatar();
            //B2: tạo tên mới cho ảnh => tên file ảnh là duy nhất
            $imgName = uniqid(); //unique ID
            //B3: lấy ra phần đuôi (extension) của ảnh
            $imgExtension = $image->guessExtension();
            //B4: gộp tên mới + đuôi tạo thành tên file ảnh hoàn thiện
            $imageName = $imgName . "." . $imgExtension;
            //B5: di chuyển file ảnh upload vào thư mục chỉ định
            try {
                $image->move(
                    $this->getParameter('admin_avatar'),
                    $imageName
                    //Lưu ý: cần khai báo tham số đường dẫn của thư mục
                    //cho "author_avatar" ở file config/services.yaml
                );
            } catch (FileException $e) {
                //throwException($e);
            }
            //B6: lưu tên vào database
            $admin->setAvatar($imageName);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($admin);
            $manager->flush();

            $this->addFlash('Success', "Add admin successfully !");
            return $this->redirectToRoute("admin_index");
        }

        return $this->render(
            "admin/add.html.twig",
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("admin/edit/{id}", name="admin_edit")
     */
    public function editAdmin(Request $request, $id)
    {
        $admin = $this->getDoctrine()->getRepository(Admin::class)->find($id);
        if ($admin == null) {
            $this->addFlash('Error', 'Admin not found !');
            return $this->redirectToRoute('admin_index');
        } else { 
            $form = $this->createForm(AuthorType::class, $admin);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $file = $form['avatar']->getData();
                if ($file != null) {
                    $image = $admin->getAvatar();
                    $imgName = uniqid();
                    $imgExtension = $image->guessExtension();
                    $imageName = $imgName . "." . $imgExtension;
                    try {
                        $image->move(
                            $this->getParameter('admin_avatar'),
                            $imageName
                            //Lưu ý: cần khai báo tham số đường dẫn của thư mục
                            //cho "author_avatar" ở file config/services.yaml
                        );
                    } catch (FileException $e) {
                        //throwException($e);
                    }
                    $admin->setAvatar($imageName);
                }

                    $manager = $this->getDoctrine()->getManager();
                    $manager->persist($admin);
                    $manager->flush();

                    $this->addFlash('Success', "Update admin successfully !");
                    return $this->redirectToRoute("admin_index");
            }

            return $this->render(
                "admin/edit.html.twig",
                [
                    'form' => $form->createView()
                ]
            );
        }
    }

}

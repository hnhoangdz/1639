<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @IsGranted("ROLE_USER")
 */
class CategoryController extends AbstractController
{
    #[Route('/category', name: 'category_index')]
    public function index(): Response
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $total = count($category);
        if($category == null){
            $this->addFlash('Error', 'Category List is empty');
        }
        return $this->render('category/index.html.twig', [
            'category' => $category,
            'total' => $total
        ]);
    }

    /**
     * @Route("/category/detail/{id}", name="category_detail")
     */
    public function categoryDetail($id){
        $aCategory= $this->getDoctrine()->getRepository(Category::class)->find($id);
        if ($aCategory == null) {
            $this->addFlash('Error', 'Category not found');
            return $this->redirectToRoute('category_index');
        } else {
            return $this->render(
                'category/detail.html.twig',
                [
                    'aCategory' => $aCategory
                ]
            );
        }
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/category/add", name="category_add")
     */
    public function categoryAdd(Request $request){
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //code xử lý ảnh upload
            //B1: lấy ảnh từ file upload
            $image = $category->getImage();
            //B2: tạo tên mới cho ảnh => tên file ảnh là duy nhất
            $imgName = uniqid(); //unique ID
            //B3: lấy ra phần đuôi (extension) của ảnh
            $imgExtension = $image->guessExtension();
            //B4: gộp tên mới + đuôi tạo thành tên file ảnh hoàn thiện
            $imageName = $imgName . "." . $imgExtension;
            //B5: di chuyển file ảnh upload vào thư mục chỉ định
            try {
                $image->move(
                    $this->getParameter('category_image'),
                    $imageName
                    //Lưu ý: cần khai báo tham số đường dẫn của thư mục
                    //cho "book_cover" ở file config/services.yaml
                );
            } catch (FileException $e) {

            }
            //B6: lưu tên vào database
            $category->setImage($imageName);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($category);
            $manager->flush();
            $this->addFlash('Success', "Add Category successfully !");
            return $this->redirectToRoute('category_index');
        }
        return $this->render(
            "category/add.html.twig",
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/category/edit/{id}", name="category_edit")
     */
    public function categoryEdit(Request $request, $id){
        $aCategory = $this->getDoctrine()->getRepository(Category::class)->find($id);
        if ($aCategory == null) {
            $this->addFlash('Error', 'Category not found');
            return $this->redirectToRoute('course_index');
        } else {
            $form = $this->createForm(CategoryType::class, $aCategory);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                //code xử lý ảnh upload
                //B1: lấy dữ liệu ảnh từ form 
                $file = $form['image']->getData();
                //B2: check xem file ảnh upload có null không
                if ($file != null) {
                    //B3: lấy ảnh từ file upload
                    $image = $aCategory->getImage();
                    //B4: tạo tên mới cho ảnh => tên file ảnh là duy nhất
                    $imgName = uniqid(); //unique ID
                    //B5: lấy ra phần đuôi (extension) của ảnh
                    $imgExtension = $image->guessExtension();
                    //B6: gộp tên mới + đuôi tạo thành tên file ảnh hoàn thiện
                    $imageName = $imgName . "." . $imgExtension;
                    //B7: di chuyển file ảnh upload vào thư mục chỉ định
                    try {
                        $image->move(
                            $this->getParameter('category_image'),
                            $imageName
                            //Lưu ý: cần khai báo tham số đường dẫn của thư mục
                            //cho "book_cover" ở file config/services.yaml
                        );
                    } catch (FileException $e) {
                        
                    }
                    //B8: lưu tên vào database
                    $aCategory->setImage($imageName);
                }


                $manager = $this->getDoctrine()->getManager();
                $manager->persist($aCategory);
                $manager->flush();
                $this->addFlash('Success', "Edit Category successfully !");
                return $this->redirectToRoute('category_index');
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
     * @IsGranted("ROLE_ADMIN")
     * @Route("category/delete/{id}", name="category_delete")
     */
    public function categoryDelete($id)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        if ($category == null) {
            $this->addFlash('Error', 'Category not found');
        } else {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($category);
            $manager->flush();
            $this->addFlash('Success', 'Category has been deleted');
        }
        return $this->redirectToRoute('category_index');
    }

}

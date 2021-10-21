<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER")
 */
class CategoryController extends AbstractController
{
    #[Route('/category', name: 'category_index')]
    public function index(): Response
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->findAll();
        if($category == null){
            $this->addFlash('Error', 'Category List is empty');
        }
        return $this->render('category/index.html.twig', [
            'category' => $category,
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
     * @Route("/category/add", name="category_add")
     */
    public function categoryAdd(Request $request){
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
     * @Route("/category/edit/{id}", name="category_edit")
     */
    public function categoryEdit(Request $request, $id){
        $aCategory = $this->getDoctrine()->getRepository(Category::class)->find($id);
        if ($aCategory == null) {
            $this->addFlash('Error', 'Category not found');
            return $this->redirectToRoute('category_index');
        } else {
            $form = $this->createForm(CategoryType::class, $aCategory);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
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

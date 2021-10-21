<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', TextType::class, [
            'label' => 'Course Name',
            'required' => true
        ])
        ->add('description', TextType::class, [
            'label' => 'Course Description',
            'required' => true
        ])
        ->add('categoryID', EntityType::class,[
            'label' => 'Category',
            'class' => Category::class, 
            'choice_label' => "name",
            'multiple' => false,
            'expanded' => false
        ])
        ->add('image', FileType::class,[
            'label' => 'Course Image',
            'data_class' => null,
            'required' => is_null($builder->getData()->getImage())
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}

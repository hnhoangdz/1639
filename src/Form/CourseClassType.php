<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\Teacher;
use App\Entity\CourseClass;
use App\Entity\Student;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CourseClassType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,
            [
                'label' => 'Class Name',
                'required' => true
            ])
            ->add('dateStart', DateType::class, 
            [
                'label' => 'Date Start',
                'required' => true,
                'widget' => 'single_text'
            ])
            ->add('dateEnd', DateType::class, 
            [
                'label' => 'Date End',
                'required' => true,
                'widget' => 'single_text'
            ])
            ->add('teacherID', EntityType::class,
            [
                'label' => 'Teacher',
                'class' => Teacher::class, 
                'choice_label' => "name",
                'multiple' => false,
                'expanded' => false
            ])
            ->add('courseID', EntityType::class,
            [
                'label' => 'Course',
                'class' => Course::class, 
                'choice_label' => "name",
                'multiple' => false,
                'expanded' => false
            ])
            ->add('studentID', EntityType::class,
            [
                'label' => 'Student',
                'class' => Student::class, 
                'choice_label' => "name",
                'multiple' => true,
                'expanded' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CourseClass::class,
        ]);
    }
}

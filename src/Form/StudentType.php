<?php

namespace App\Form;

use App\Entity\Student;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,
            [
                'label' => 'Student Name',
                'required' => true
            ])
            ->add('email',TextType::class,
            [
                'label' => 'Student Email',
                'required' => true
            ])
            ->add('birthday',DateType::class,
            [
                'label' => "Student Birthday",
                'required' => true,
                'widget' => "single_text"
            ])
            ->add('avatar',FileType::class,
            [
                'label' => "Student Avatar",
                'data_class' => null,
                'required' => is_null($builder->getData()->getAvatar())
            ])
            //->add('courseClasses',Entity::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}

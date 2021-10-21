<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Teacher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class TeacherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name',TextType::class,
        [
            'label' => 'Teacher Name',
            'required' => true
        ])
        ->add('email',TextType::class,
        [
            'label' => 'Teacher Email',
            'required' => true
        ])
        ->add('birthday',DateType::class,
        [
            'label' => "Teacher Birthday",
            'required' => true,
            'widget' => "single_text"
        ])
        ->add('avatar',FileType::class,
        [
            'label' => 'Teacher Avatar',
            'data_class' => null,
            'required' => is_null($builder->getData()->getAvatar())
        ])
        //->add('courseClasses',Entity::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Teacher::class,
        ]);
    }
}

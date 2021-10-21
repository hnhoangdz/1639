<?php

namespace App\Form;

use App\Entity\Admin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, 
            [
                'label' => 'Admin Name',
                'required' => true
            ])
            ->add('email', TextType::class, 
            [
                'label' => 'Admin Email',
                'required' => true
            ])
            ->add('birthday', DateType::class, 
            [
                'label' => 'Admin Birthday',
                'required' => true,
                'widget' => 'single_text'
            ])
            ->add('avatar', FileType::class,
            [
                'label' => "Admin Avatar",
                'data_class' => null,
                'required' => is_null($builder->getData()->getAvatar())
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Admin::class,
        ]);
    }
}

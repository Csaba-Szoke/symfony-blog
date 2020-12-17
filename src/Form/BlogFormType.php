<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\BlogCategory;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class BlogFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('public', CheckboxType::class, ['required' => false])
            ->add('title')
            ->add('slug')
            ->add('category', EntityType::class, [
                'class' => BlogCategory::class,
                'choice_label' => 'title',
                'required'   => false,
            ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'name',
                'required'   => false,
                'multiple'   => true,
                'expanded'   => true,
            ])
            ->add('content', CKEditorType::class, [
                'config'      => [
                    'toolbar' => 'full'
                ],
            ])
            ->add('imgFile', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '5M'
                    ])
                ]
            ]);
    }
}

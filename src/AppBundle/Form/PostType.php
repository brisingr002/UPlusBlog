<?php

namespace AppBundle\Form;

use AppBundle\DataTransformer\TagsTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType {

    private $transformer;

    public function __construct(TagsTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('text', TextareaType::class, array(
                'required' => false,
                'attr' => array('class' => 'tinymce', 'novalidate' => true)
            ))
            ->add('date', DateTimeType::class)
            ->add('slug', TextType::class)
            ->add('active', CheckboxType::class, array(
                'required' => false
            ))
            ->add('tags', TextType::class, array(
                'required' => false,
                'label' => 'Tags (separated by comma)'
            ))
            ->add('Save', SubmitType::class);

        $builder->get('tags')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'authenticate'
        ));
    }
}
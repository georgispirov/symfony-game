<?php

namespace AppBundle\Form;

use AppBundle\Entity\Comments;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddCommentToProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('comment', TextareaType::class, [
            'label' => 'Review'
        ])->add('vote', RangeType::class, [
            'label' => 'Rating',
            'attr'  => [
                'min' => 1,
                'max' => 5,
                "data-provide"      => "slider",
                "data-slider-ticks" => "[1,2,3,4,5]",
                "data-slider-min"   => "1",
                "data-slider-max"   => "5",
                "data-slider-step"  => "1",
                "data-slider-value" => "1",
                "style"             => "width:100%;"
            ],
        ])->add('Comment', SubmitType::class, ['attr' => ['class' => 'btn btn-primary']]);
    }

    public function getName()
    {
        return 'app_add_comment_on_product';
    }

    public function getBlockPrefix()
    {
        return $this->getName();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => Comments::class,
            'translation_domain' => false
        ]);
    }
}
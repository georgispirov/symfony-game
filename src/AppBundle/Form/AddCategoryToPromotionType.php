<?php

namespace AppBundle\Form;

use AppBundle\Entity\Categories;
use AppBundle\Entity\Promotion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddCategoryToPromotionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('discount', PercentType::class, [
            'label' => 'Product Discount',
            'scale' => 2,
            'type'  => 'integer'
        ])->add('startDate', DateTimeType::class, [
            'label' => 'Start Date'
        ])->add('endDate', DateTimeType::class, [
            'label' => 'End Date'
        ])->add('category', EntityType::class, [
            'label' => 'Category',
            'class' => Categories::class
        ])->add('isActive', ChoiceType::class, [
            'label'    => 'Active',
            'data'     =>  true,
            'choices'  => [
                    'No'   => false,
                    'Yes'  => true
                ],
            'required' => true,
        ])->add('Add Category To Promotion', SubmitType::class, [
            'attr'  => ['class' => 'btn btn-success']
        ]);
    }

    public function getName()
    {
        return 'app_add_category_to_promotion';
    }

    public function getBlockPrefix()
    {
        return $this->getName();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Promotion::class,
            'translation_domain' => false
        ]);
    }
}
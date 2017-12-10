<?php

namespace AppBundle\Form;

use AppBundle\Entity\Product;
use AppBundle\Entity\Promotion;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddPromotionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('discount', MoneyType::class, [
                    'label' => 'Product Discount',
              ])->add('startDate', DateTimeType::class, [
                    'label' => 'Start Date'
              ])->add('endDate', DateTimeType::class, [
                    'label' => 'End Date'
              ])->add('product', EntityType::class, [
                    'label'         => 'Product',
                    'class'         =>  Product::class,
                    'multiple'      =>  true,
                    'group_by'      =>  function ($product, $key, $index) {
                        /* @var Product $product */
                        return $product->getCategory()->getName();
                    },
                    'query_builder' =>  function (EntityRepository $er) {
                        return $er->createQueryBuilder('p')
                                  ->orderBy('p.title', 'ASC');
                    }
              ])->add('Add Promotion', SubmitType::class, [
                    'attr' => [
                            'class' => 'btn btn-primary'
                    ]
              ]);
    }

    public function getName()
    {
        return 'app_add_promotion';
    }

    public function getBlockPrefix()
    {
        return $this->getName();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => Promotion::class,
            'translation_domain' => false
        ]);
    }
}
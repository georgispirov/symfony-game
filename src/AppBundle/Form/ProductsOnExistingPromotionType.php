<?php

namespace AppBundle\Form;

use AppBundle\Entity\Categories;
use AppBundle\Entity\Product;
use AppBundle\Entity\Promotion;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductsOnExistingPromotionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('discount', EntityType::class, [
            'label'         => 'Discounts',
            'class'         =>  Promotion::class,
            'query_builder' =>  function (EntityRepository $er)  {
                return $er->createQueryBuilder('prom')
                          ->orderBy('prom.startDate', 'ASC');
            }
        ])->add('category', EntityType::class, [
            'class'         =>  Categories::class,
            'label'         => 'Categories',
            'query_builder' =>  function (EntityRepository $er) {
                return $er->createQueryBuilder('c')
                          ->orderBy('c.name', 'ASC');
            }
        ])->add('product', EntityType::class, [
            'label'         =>  'Products',
            'disabled'      =>   true,
            'multiple'      =>   true,
            'class'         =>   Product::class,
            'query_builder' =>   function (EntityRepository $er) {
                return $er->createQueryBuilder('prod')
                          ->orderBy('prod.title', 'ASC');
            }
        ])->add('Add Products To Promotion', SubmitType::class, [
            'attr' => [
                        'class' => 'btn btn-primary'
                      ]
        ]);


    }

    public function getName()
    {
        return 'app_products_on_existing_promotion';
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
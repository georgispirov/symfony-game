<?php

namespace AppBundle\Form;

use AppBundle\Entity\Categories;
use AppBundle\Entity\Product;
use AppBundle\Entity\Promotion;
use AppBundle\Repository\ProductRepository;
use AppBundle\Repository\PromotionRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductsOnExistingPromotionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var Promotion $promotion */
        $promotion = $options['promotion'];

        /* @var array $selectedProducts */
        $selectedProducts = $options['selectedProducts'];

        $builder->add('discount', EntityType::class, [
            'label'         => 'Discount',
            'class'         =>  Promotion::class,
            'data'          =>  $promotion->getId(),
        ])->add('product', EntityType::class, [
            'label'         =>  'Products',
            'class'         =>   Product::class,
            'multiple'      =>   true,
            'disabled'      =>   true,
            'data'          =>   $selectedProducts
        ])->add('Add Products To Promotion', SubmitType::class, [
            'attr' => ['class' => 'btn btn-primary']
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
            'data_class'         => Promotion::class,
            'translation_domain' => false,
            'promotion'          => null,
            'selectedProducts'   => []
        ]);
    }
}
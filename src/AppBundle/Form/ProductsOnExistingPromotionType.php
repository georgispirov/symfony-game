<?php

namespace AppBundle\Form;

use AppBundle\Entity\Product;
use AppBundle\Entity\Promotion;
use AppBundle\Repository\PromotionRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductsOnExistingPromotionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var Promotion $promotion */
        $promotion = $options['promotion'];

        /* @var Product[] $selectedOptions */
        $activeProducts = $options['activeProducts'];

        $builder->add('discount', EntityType::class, [
            'label'         => 'Discount',
            'class'         =>  Promotion::class,
            'disabled'      =>  true,
            'query_builder' =>  function (PromotionRepository $promotionRepository) use ($promotion) {
                return $promotionRepository->createQueryBuilder('promotion')
                                           ->where('promotion = :promotion')
                                           ->setParameters([':promotion' => $promotion]);
            }
        ])->add('product', EntityType::class, [
            'label'         => 'Products',
            'class'         =>  Product::class,
            'multiple'      =>  true,
            'required'      =>  true,
            'choices'       =>  array_diff($activeProducts, $promotion->getProduct()->toArray())
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
            'error_bubbling'     => true,
            'activeProducts'     => []
        ]);
    }
}
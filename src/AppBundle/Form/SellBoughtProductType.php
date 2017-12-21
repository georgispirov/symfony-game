<?php

namespace AppBundle\Form;

use AppBundle\Entity\Categories;
use AppBundle\Entity\OrderedProducts;
use AppBundle\Entity\Product;
use AppBundle\Repository\CategoriesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class SellBoughtProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var OrderedProducts $orderedProduct */
        $orderedProduct = $options['orderedProduct'];

        $builder->add('imageFile', VichFileType::class, [
            'label'    => 'Picture',
            'required' => false
        ])->add('title', null, [
            'label'    => 'Product Title',
            'required' => true
        ])->add('description', TextareaType::class, [
            'label'    => 'Description'
        ])->add('price', MoneyType::class, [
            'label'    => 'Price',
            'required' => true
        ])->add('quantity', RangeType::class, [
            'label'    => 'Quantity',
            'required' => true,
            'data'     => $orderedProduct->getConfirmed(),
            'attr'     => [
                'min'               => 1,
                'max'               => $orderedProduct->getConfirmed(),
                "data-provide"      => "slider",
                "data-slider-min"   => "1",
                "data-slider-max"   => $orderedProduct->getConfirmed(),
                "data-slider-step"  => "1",
                "data-slider-value" => "1",
                "style"             => "width:100%;"
            ]
        ])->add('category', EntityType::class, [
            'class'         => Categories::class,
            'required'      => true,
            'query_builder' => function (CategoriesRepository $categoriesRepository) {
                return $categoriesRepository->createQueryBuilder('c')
                                            ->orderBy('c.name', 'ASC');
            }
        ])->add('Sell Product', SubmitType::class, [
            'attr' => ['class' => 'btn btn-success']
        ]);
    }

    public function getName()
    {
        return 'app_sell_bought_product';
    }

    public function getBlockPrefix()
    {
        return $this->getName();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => Product::class,
            'translation_domain' => false,
            'orderedProduct'     => null
        ]);
    }
}
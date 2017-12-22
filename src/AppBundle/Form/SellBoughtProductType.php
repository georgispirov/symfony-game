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

        $builder->add('title', null, [
            'label'    => 'Product Title',
            'attr'     => [
                'readonly' => true
            ]
        ])->add('description', TextareaType::class, [
            'label'    => 'Description',
            'attr'     => [
                'readonly' => true
            ]
        ])->add('price', MoneyType::class, [
            'label'    => 'Price',
            'attr'     => [
                'readonly' => true
            ]
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
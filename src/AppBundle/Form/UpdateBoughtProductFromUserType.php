<?php

namespace AppBundle\Form;

use AppBundle\Entity\OrderedProducts;
use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateBoughtProductFromUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var User $userOwn */
        $userOwn = $options['userOwn'];

        /* @var int $confirmed */
        $confirmed = $options['confirmed'];

        /* @var int $quantity */
        $quantity  = $options['quantity'];

        $builder->add('orderedDate', DateTimeType::class,  [
                    'label' => 'Ordered Date'
                ])->add('confirmed', RangeType::class, [
                    'label' => 'Confirmed Quantity',
                    'attr'  => [
                        'min' => 0,
                        'max' => 100,
                        "data-provide"      => "slider",
                        "data-slider-ticks" => "[0-100]",
                        "data-slider-min"   => "0",
                        "data-slider-max"   => "100",
                        "data-slider-step"  => "1",
                        "data-slider-value" => $confirmed,
                        "style"             => "width:100%;"
                    ]
                ])->add('quantity', RangeType::class, [
                    'label' => 'Ordered Quantity',
                    'attr'  => [
                        'min' => 0,
                        'max' => 100,
                        "data-provide"      => "slider",
                        "data-slider-min"   => "0",
                        "data-slider-max"   => "100",
                        "data-slider-step"  => "1",
                        "data-slider-value" => $quantity,
                        "style"             => "width:100%;"
                    ]
                ])->add('Update Bought Product', SubmitType::class, [
                    'attr' => ['class' => 'btn btn-success']
                ]);
    }

    public function getName()
    {
        return 'app_update_bought_product_from_user';
    }

    public function getBlockPrefix()
    {
        return $this->getName();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => OrderedProducts::class,
            'translation_domain' => false,
            'userOwn'            => null,
            'quantity'           => null,
            'confirmed'          => null,
            'product'            => null
        ]);
    }
}
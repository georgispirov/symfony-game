<?php

namespace AppBundle\Form;

use AppBundle\Entity\Categories;
use AppBundle\Entity\Product;
use AppBundle\Entity\Promotion;
use AppBundle\Repository\ProductRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddPromotionType extends AbstractType
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
                    'label' => 'Categories',
                    'class' =>  Categories::class,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                                  ->orderBy('c.name', 'ASC');
                    },
              ])->add('product', EntityType::class, [
                    'label'         => 'Product',
                    'required'      =>  true,
                    'class'         =>  Product::class,
                    'disabled'      =>  true,
                    'multiple'      =>  true,
                    'query_builder' =>  function (ProductRepository $productRepository) {
                        return $productRepository->createQueryBuilder('p')
                                                 ->select('p')
                                                 ->where('p.quantity > 0')
                                                 ->andWhere('p.outOfStock = 0')
                                                 ->orderBy('p.title', 'ASC');
                    }
              ])->add('isActive', ChoiceType::class, [
                    'label'    => 'Active',
                    'data'     =>  true,
                    'choices'  => [
                            'No'   => false,
                            'Yes'  => true
                    ],
                    'required' => true,
              ])->add('Add Promotion', SubmitType::class, [
                    'attr' => [
                            'class' => 'btn btn-primary'
                    ]
              ]);

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            $form    =  $event->getForm();
            $product =  $form->get('product')->getConfig()->getData();

            if (null === $product) {
                $form->get('product')->addError(new FormError('At least one Product must be selected.'));
                $form->get('product')->addError(new FormError('Here are listed only active products.'));
                $event->stopPropagation();
            }
        });
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
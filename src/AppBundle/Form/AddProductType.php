<?php

namespace AppBundle\Form;

use AppBundle\Entity\Product;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class AddProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', null, [
                                'label' => 'Title'
                ])->add('description', TextareaType::class, [
                                'label' => 'Description', 'required'  => true
                ])->add('price', MoneyType::class, [
                                'label' => 'Price',       'required'  => true
                ])->add('quantity', IntegerType::class, [
                                'label' => 'Quantity',    'required'  => true
                ])->add('imageFile', VichFileType::class, [
                                'label' => 'Picture',     'required'  => true
                ])->add('category', EntityType::class, [
                    'class'         => 'AppBundle\Entity\Categories',
                    'query_builder' => function (EntityRepository $repository) {
                        return $repository->createQueryBuilder('c')
                                          ->orderBy('c.name', 'ASC');
                    },
                    'expanded' => false,
                    'multiple' => false
                ])->add('Add Product', SubmitType::class);
    }

    public function getBlockPrefix()
    {
        return 'app_add_product';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => Product::class,
            'translation_domain' => false
        ]);
    }
}
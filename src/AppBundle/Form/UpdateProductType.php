<?php

namespace AppBundle\Form;

use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

/**
 * In case of needed form requirements, below they can be added.
 * [[AddProductType]] form is extended to avoid overwriting.
 *
 * Class UpdateProductType
 * @package AppBundle\Form
 */
class UpdateProductType extends AddProductType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var User $user */
        $user = $options['user'];

        parent::buildForm($builder, $options);
        $builder->remove('Add Product');
        $builder->remove('quantity');
        $builder->remove('category');
        $imageOptions['required'] = false;
        $builder->add('imageFile', VichFileType::class, $imageOptions);

        if ($user->hasRole('ROLE_EDITOR') || $user->hasRole('ROLE_ADMIN')) {
            $builder->add('quantity', IntegerType::class, [
                'label'         => 'Quantity',
                'required'      => true
            ])->add('category', EntityType::class, [
                'class'         => 'AppBundle\Entity\Categories',
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
                'expanded'      => false,
                'multiple'      => false
            ]);
        }

        $builder->add('Update Product', SubmitType::class, ['attr' => ['class' => 'btn btn-success']]);
    }

    public function getName()
    {
        return 'app_update_product';
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
            'user'               => null
        ]);
    }
}
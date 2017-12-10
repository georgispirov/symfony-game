<?php

namespace AppBundle\Form;

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
        parent::buildForm($builder, $options);
        $builder->remove('Add Product');
        $imageOptions['required'] = false;
        $builder->add('imageFile', VichFileType::class, $imageOptions);
        $builder->add('Update Product', SubmitType::class);
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
        parent::configureOptions($resolver);
    }
}
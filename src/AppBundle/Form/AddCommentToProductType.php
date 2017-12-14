<?php

namespace AppBundle\Form;

use AppBundle\Entity\Comments;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddCommentToProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('comment', TextareaType::class, [
            'label' => 'Review'
        ])->add('vote', IntegerType::class, [
            'label' => 'Rating'
        ])->add('Comment', SubmitType::class, ['attr' => ['class' => 'btn btn-primary']]);
    }

    public function getName()
    {
        return 'app_add_comment_on_product';
    }

    public function getBlockPrefix()
    {
        return $this->getName();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => Comments::class,
            'translation_domain' => false
        ]);
    }
}
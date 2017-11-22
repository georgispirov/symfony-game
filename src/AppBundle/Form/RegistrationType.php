<?php

namespace AppBundle\Form;

use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\EmailType'),
                            [
                                'label'              => 'Email',
                                'translation_domain' => 'FOSUserBundle',
                ])
                ->add('username', null, [
                                'label'              => 'Username',
                                'translation_domain' => 'FOSUserBundle',
                ])
                ->add('plainPassword', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\RepeatedType'),
                           [
                                'type' => LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\PasswordType'),
                                'options' => ['translation_domain' => 'FOSUserBundle'],
                                'first_options' => ['label' => 'Password'],
                                'second_options' => ['label' => 'Repeat Password'],
                                'invalid_message' => 'Passwords mismatched!',
                ]);
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => ['Registration'],
        ]);
    }
}

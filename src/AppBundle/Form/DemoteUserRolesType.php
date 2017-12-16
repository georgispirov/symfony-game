<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemoteUserRolesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var User $user */
        $user = $options['user'];

        $currentRoles = array_combine($user->getRoles(), $user->getRoles());

        $builder->add('roles', ChoiceType::class, [
            'label'    => 'Demote Roles',
            'multiple' => true,
            'choices'  => $currentRoles,
            'required' => true
        ])->add('Demote', SubmitType::class, [
            'attr' => [
                'class' => 'btn btn-primary'
            ]
        ]);
    }

    public function getName()
    {
        return 'app_demote_user_roles';
    }

    public function getBlockPrefix()
    {
        return $this->getName();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => User::class,
            'translation_domain' => false,
            'user'               => null
        ]);
    }
}
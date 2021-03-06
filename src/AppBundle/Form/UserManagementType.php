<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserManagementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var User $user */
        $user = $options['user'];

        /* @var array $roles */
        $roles = $options['roles'];

        $allRoles   = array_keys($roles);
        $difference = array_diff($allRoles, $user->getRoles());

        $keys   = array_values($difference);
        $result = array_combine($keys, $difference);

        if ( !$user instanceof User ) {
            throw new LogicException('Applied user for setting roles must be a valid Entity.');
        }

        $builder->add('roles', ChoiceType::class, [
            'label'         => 'Set Roles',
            'multiple'      =>  true,
            'choices'       =>  $result,
            'required'      =>  true
        ])->add('Set Roles', SubmitType::class, [
            'attr' => [
                'class' => 'btn btn-primary'
            ]
        ]);
    }

    public function getName()
    {
        return 'app_user_management';
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
            'user'               => null,
            'roles'              => []
        ]);
    }
}
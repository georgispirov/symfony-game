<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use ReflectionClass;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserManagementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var null|User $user */
        $user = $options['user'];

        if ( !$user instanceof User ) {
            throw new LogicException('Applied user for setting roles must be a valid Entity.');
        }

        $allRoles = (new ReflectionClass(User::class))->getConstants();
        $rolesCanBeChecked = array_diff($allRoles, $user->getRoles());

        $builder->add('roles', ChoiceType::class, [
            'label'         => 'Set Roles',
            'multiple'      => true,
            'choices'       => array_flip($rolesCanBeChecked)
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
            'user'               => null
        ]);
    }
}
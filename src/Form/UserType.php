<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
            ->add('roles', ChoiceType::class, [
                "choices" => [
                    'administrateur' => 'ROLE_ADMIN',
                    'benevole' => 'ROLE_BENEVOLE'
                ],
                'multiple' => true,
                'required' => true,
            ])
            ->add('password', textType::class, [
                'required'   => false,
                'data'=> null,
                'mapped'    => false
            ])
            ->add('nomUser')
            ->add('prenomUser')
            ->add('telPerso')
            ->add('telAssoc')
            ->add('mailPerso')
            ->add('isActif')
            ->add('validation', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Beneficiaire;
use App\Entity\Demande;
use App\Entity\Origine;
use App\Entity\PositionDemande;
use App\Entity\TypeDemande;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('created_at', null, [
                'widget' => 'single_text',
            ])
            ->add('adresse1_demande')
            ->add('adresse2_demande')
            ->add('cp_demande')
            ->add('ville_demande')
            ->add('situation_logt')
            ->add('nb_enfant')
            ->add('patrimoine')
            ->add('complement_origine')
            ->add('cause_besoin')
            ->add('commentaires')
            ->add('type_demande', EntityType::class, [
                'class' => TypeDemande::class,
                'choice_label' => 'id',
            ])
            ->add('position_demande', EntityType::class, [
                'class' => PositionDemande::class,
                'choice_label' => 'id',
            ])
            ->add('origine', EntityType::class, [
                'class' => Origine::class,
                'choice_label' => 'id',
            ])
            ->add('beneficiaires', EntityType::class, [
                'class' => Beneficiaire::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('users', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Demande::class,
        ]);
    }
}

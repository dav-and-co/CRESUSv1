<?php

namespace App\Form;

use App\Entity\Beneficiaire;
use App\Entity\Demande;
use App\Entity\Origine;
use App\Entity\PositionDemande;
use App\Entity\TypeDemande;
use App\Entity\User;
use App\Entity\Revenu;
use App\Entity\Charge;
use App\Entity\Dette;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ModifDemandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('adresse1_demande')
            ->add('adresse2_demande')
            ->add('cp_demande')
            ->add('ville_demande')
            ->add('situation_logt')
            ->add('nb_enfant')
            ->add('patrimoine')
            ->add('complement_origine')
            ->add('cause_besoin')
            ->add('commentaires', TextareaType::class)

            // Champ TypeDemande avec choix multiple
            ->add('type_demande', EntityType::class, [
                'class' => TypeDemande::class,
                'choice_label' => 'libelleDemande',
                'multiple' => true,
                'expanded' => true, // Pour afficher les choix sous forme de cases à cocher
            ])

            // Champ Origine avec choix multiple
            ->add('origine', EntityType::class, [
                'class' => Origine::class,
                'choice_label' => 'libelleOrigine',
                'multiple' => true,
                'expanded' => true,
            ])

            // Champ Revenu avec choix multiple
            ->add('revenus', EntityType::class, [
                'class' => Revenu::class,
                'choice_label' => 'typeRevenu.libelleRevenu',
                'multiple' => true,
                'expanded' => true,
            ])

            // Champ Charge avec choix multiple
            ->add('charges', EntityType::class, [
                'class' => Charge::class,
                'choice_label' => 'typeCharge.libelleCharge',
                'multiple' => true,
                'expanded' => true,
            ])

            // Champ Dette avec choix multiple
            ->add('dettes', EntityType::class, [
                'class' => Dette::class,
                'choice_label' => 'typeDette.libelleDette',
                'multiple' => true,
                'expanded' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Demande::class,
        ]);
    }
}
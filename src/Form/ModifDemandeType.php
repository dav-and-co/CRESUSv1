<?php

// src/Form/DemandeType.php

namespace App\Form;

use App\Entity\Demande;
use App\Entity\Origine;
use App\Entity\PositionDemande;
use App\Entity\TypeDemande;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ModifDemandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type_demande', EntityType::class, [
                'class' => TypeDemande::class,
                'choice_label' => 'libelle_demande', // Le champ à afficher dans le select
                'choice_value' => 'id',
            ])
            ->add('position_demande', EntityType::class, [
                'class' => PositionDemande::class,
                'choice_label' => 'libelle_position',
                'choice_value' => 'id',
            ])
            ->add('origine', EntityType::class, [
                'class' => Origine::class,
                'choice_label' => 'libelle_origine',
                'choice_value' => 'id',
            ])
            ->add('complement_origine', TextType::class, [
                'label' => 'Complément Origine',
                'required' => false,
            ])
            ->add('cause_besoin', TextType::class, [
                'label' => 'Cause du Besoin',
                'required' => false,
            ])
            ->add('adresse1_demande', TextType::class, [
                'label' => 'Adresse 1',
                'required' => false,
            ])
            ->add('adresse2_demande', TextType::class, [
                'label' => 'Adresse 2',
                'required' => false,
            ])
            ->add('cp_demande', IntegerType::class, [
                'label' => 'Code Postal',
                'required' => false,
            ])
            ->add('ville_demande', TextType::class, [
                'label' => 'Ville',
                'required' => false,
            ])
            ->add('situation_logt', TextType::class, [
                'label' => 'Situation Logement',
                'required' => false,
            ])
            ->add('nb_enfant', IntegerType::class, [
                'label' => 'Nombre d\'enfants à charge',
                'required' => false,
            ])
            ->add('gardeAlternee', IntegerType::class, [
                'label' => 'Nombre d\'enfants en garde alternée',
                'required' => false,
            ])
            ->add('droitVisite', IntegerType::class, [
                'label' => 'Nombre d\'enfants avec droit de visite',
                'required' => false,
            ])



            ->add('patrimoine', TextType::class, [
                'label' => 'Patrimoine',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Demande::class,
        ]);
    }
}
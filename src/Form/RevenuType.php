<?php

namespace App\Form;

use App\Entity\Beneficiaire;
use App\Entity\Demande;
use App\Entity\Revenu;
use App\Entity\TypeRevenu;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RevenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant_mensuel', IntegerType::class, [
                'label' => 'Montant Mensuel',
            ])
            ->add('type_revenu', EntityType::class, [
                'class' => TypeRevenu::class,
                'choices' => $options['type_revenus'],
                'choice_label' => 'libelleRevenu',
                'placeholder' => 'Sélectionner un type de revenu',
                'choice_value' => 'id',
            ])
            ->add('beneficiaire', EntityType::class, [
                'class' => Beneficiaire::class,
                'choices' => $options['beneficiaires'],
                'choice_label' => function (Beneficiaire $beneficiaire) {
                    return $beneficiaire->getPrenomBeneficiaire() . ' ' . $beneficiaire->getNomBeneficiaire();
                },
                'choice_value' => 'id',
                'placeholder' => 'Sélectionnez un bénéficiaire',
            ])
            // pas utile car repris depuis le contexte
            //->add('demande', EntityType::class, [
            //    'class' => Demande::class,
            //    'choice_label' => 'id',
            //])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Revenu::class,
            'beneficiaires' => [],
            'type_revenus' => [],
        ]);

   // Déclarer les types autorisés pour l'option 'beneficiaires'
    $resolver->setAllowedTypes('beneficiaires', 'array');

    }

}

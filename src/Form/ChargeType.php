<?php

namespace App\Form;

use App\Entity\Beneficiaire;
use App\Entity\Charge;
use App\Entity\Demande;
use App\Entity\TypeCharge;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChargeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant_mensuel', IntegerType::class, [
                'label' => 'Montant Mensuel',
            ])

            ->add('type_charge', EntityType::class, [
                'class' => TypeCharge::class,
                'placeholder' => 'Sélectionner un type de charge',
                'choices' => $options['type_charges'],
                'choice_label' => 'libelleCharge',
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
            ->add('commentaires', null, [
                'label' => 'commentaires',
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
            'data_class' => Charge::class,
            'beneficiaires' => [],
            'type_charges' => [],
        ]);

        // Déclarer les types autorisés pour l'option 'beneficiaires'
        $resolver->setAllowedTypes('beneficiaires', 'array');

    }

}
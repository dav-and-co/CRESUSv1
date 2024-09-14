<?php

namespace App\Form;

use App\Entity\Beneficiaire;
use App\Entity\Demande;
use App\Entity\Dette;
use App\Entity\TypeDette;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('organisme', TextType::class, [
                'label' => 'Organisme',
            ])
            ->add('montant_initial', IntegerType::class, [
                'label' => 'Montant initial',
            ])
            ->add('mensualite', IntegerType::class, [
                'label' => 'Mensualité',
            ])
            ->add('montant_du', IntegerType::class, [
                'label' => 'Restant à devoir',
            ])
            ->add('titulaire_principal', EntityType::class, [
                'class' => Beneficiaire::class,
                'choices' => $options['beneficiaires'],
                'choice_label' => function (Beneficiaire $beneficiaire) {
                    return $beneficiaire->getPrenomBeneficiaire() . ' ' . $beneficiaire->getNomBeneficiaire();
                },
                'choice_value' => 'id',
                'placeholder' => 'Sélectionnez un bénéficiaire',
            ])
            ->add('type_dette', EntityType::class, [
                'class' => TypeDette::class,
                'choices' => $options['type_dettes'],
                'placeholder' => 'Sélectionner un type de dette/crédit',
                'choice_label' => 'libelleDette',
                'choice_value' => 'id',

            ])
            ->add('commentaires', null, [
                'label' => 'commentaires',
            ])

            // pas utile car repris depuis le contexte
           // ->add('demande', EntityType::class, [
            //    'class' => Demande::class,
            //    'choice_label' => 'id',
            //])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dette::class,
            'beneficiaires' => [],
            'type_dettes' => [],
        ]);

        // Déclarer les types autorisés pour l'option 'beneficiaires'
        $resolver->setAllowedTypes('beneficiaires', 'array');

    }

}

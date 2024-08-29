<?php

namespace App\Form;

use App\Entity\Beneficiaire;
use App\Entity\Demande;
use App\Entity\TypeProf;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BeneficiaireAndDemandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('civilite_beneficiaire', ChoiceType::class, [
                "choices" => [
                    'Madame' => 'Madame',
                    'Monsieur' => 'Monsieur'
                ],
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('nom_beneficiaire', null, [
                'required' => true,
            ])
            ->add('prenom_beneficiaire', null, [
                'required' => true,
            ])
            ->add('ddn_beneficiaire', null, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('mail_beneficiaire', null, [
                'required' => false,
            ])
            ->add('telephone_beneficiaire', null, [
                'required' => false,
            ])
            ->add('profession_beneficiaire', null, [
                'required' => false,
            ])
            ->add('libelle_prof', null, [
                'required' => false,
            ])
            ->add('demandes', null, [
                'required' => false,
                'multiple' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Beneficiaire::class,
        ]);
    }
}
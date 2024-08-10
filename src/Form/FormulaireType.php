<?php

namespace App\Form;

use App\Entity\Formulaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormulaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_demandeur', TextType::class, [
                'label' => 'Votre nom',
                'required' => true,
            ])
            ->add('prenom_demandeur', TextType::class, [
                'label' => 'Votre prénom',
                'required' => true,
            ])
            ->add('mail_demandeur', EmailType::class, [
                'label' => 'Votre email',
                'required' => false,
            ])
            ->add('telephone_demandeur', TextType::class, [
                'label' => 'Votre téléphone',
                'required' => false,
            ])
            ->add('besoin_demandeur', ChoiceType::class, [
        "choices" => [
            'Accompagnement budgétaire' => 'PCB',
            'Demande de microcrédit' => 'microcrédit',
            'surendettement' => 'surendettement'
        ],
                'label' => 'Votre Besoin',
                'required' => true,
            ])
            ->add('description_besoin', TextareaType::class, [
                'label' => 'Message',
                'required' => false,
            ])
            ->add('isGdpr', CheckboxType::class, [
                'label' => 'J\'accepte les conditions GDPR',
                'mapped' => true,
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer la demande',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formulaire::class,
        ]);
    }
}

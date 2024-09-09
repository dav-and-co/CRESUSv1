<?php

namespace App\Form;

use App\Entity\Beneficiaire;
use App\Entity\TypeProf;
use App\Form\DataTransformer\PhoneNumberTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use App\Form\DataTransformer\NullToDateTimeTransformer;

class BeneficiaireType extends AbstractType
{
    private $nullToDateTimeTransformer;
    private $phoneNumberTransformer;

    public function __construct(NullToDateTimeTransformer $nullToDateTimeTransformer, PhoneNumberTransformer $phoneNumberTransformer)
    {
        $this->nullToDateTimeTransformer = $nullToDateTimeTransformer;
        $this->phoneNumberTransformer = $phoneNumberTransformer;
    }

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
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom est obligatoire.'
                    ]),
                ],
            ])
            ->add('prenom_beneficiaire', null, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le prénom est obligatoire.'
                    ]),
                ],
            ])
            ->add('ddn_beneficiaire', DateType::class, [
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'html5' => false,
                'attr' => [
                    'placeholder' => 'jj/mm/aaaa',
                ],
                    'required' => false,
                    'invalid_message' => 'Veuillez entrer une date valide ou laisser vide.',
            ])
            ->add('mail_beneficiaire', EmailType::class, [
                'required' => false,
                'constraints' => [
                    new Email([
                        'message' => 'Le format de l\'email est invalide.',
                    ]),
                ],
            ])
            ->add('telephone_beneficiaire', TextType::class, [
                'required' => false,
            ])
            ->add('profession_beneficiaire', null, [
                'required' => false,
            ])
            ->add('libelle_prof', EntityType::class, [
                'class' => TypeProf::class,
                'choice_label' => 'libelle_prof',
                'placeholder' => 'Choisissez un type professionnel',
                'required' => false,
            ])
        ;
        // Ajout du transformer au champ 'ddn_beneficiaire'
        $builder->get('ddn_beneficiaire')->addModelTransformer($this->nullToDateTimeTransformer);
        // Appliquer le transformer au champ téléphone
        $builder->get('telephone_beneficiaire')->addModelTransformer($this->phoneNumberTransformer);
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Beneficiaire::class,
        ]);
    }
}
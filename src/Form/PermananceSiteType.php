<?php

// src/Form/PermananceSiteType.php

namespace App\Form;

use App\Entity\PermananceSite;
use App\Entity\Site;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class PermananceSiteType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateAt', DateType::class, [
                'widget' => 'single_text', // Utiliser un calendrier sans les heures/minutes
                'html5' => true, // Pour les navigateurs modernes
                'label' => 'Date de la permanence',
                'attr' => [
                    'class' => 'form-control', // Personnalisation CSS
                ]
             ])
            ->add('heureAt', TimeType::class, [
                'widget' => 'single_text', // Format heure/minutes
                'input' => 'datetime',
                'html5' => true,
                'attr' => [
                    'class' => 'form-control', // Personnalisation CSS
                ]
            ])
            ->add('heureEnd', TimeType::class, [
                'widget' => 'single_text',
                'input' => 'datetime',
                'html5' => true,
                'attr' => [
                    'class' => 'form-control', // Personnalisation CSS
                ]
            ])
            ->add('benevole1', ChoiceType::class, [
                'label' => 'Bénévole 1',
                'choices' => $options['activeUsers'], // Utilisation de l'option activeUsers
                'choice_label' => function ($user) {
                    return $user->getUsername(); // Affiche le nom d'utilisateur
                },
                'choice_value' => function ($user) {
                    return $user ? $user->getUsername() : ''; // Sauvegarde le nom d'utilisateur
                },
                'required' => false,
            ])
            ->add('benevole2', ChoiceType::class, [
                'label' => 'Bénévole 2',
                'choices' => $options['activeUsers'], // Utilisation de l'option activeUsers
                'choice_label' => function ($user) {
                    return $user->getUsername(); // Affiche le nom d'utilisateur
                },
                'choice_value' => function ($user) {
                    return $user ? $user->getUsername() : ''; // Sauvegarde le nom d'utilisateur
                },
                'required' => false,
            ])
            ->add('benevole3', ChoiceType::class, [
                'label' => 'Bénévole 3',
                'choices' => $options['activeUsers'], // Utilisation de l'option activeUsers
                'choice_label' => function ($user) {
                    return $user->getUsername(); // Affiche le nom d'utilisateur
                },
                'choice_value' => function ($user) {
                    return $user ? $user->getUsername() : ''; // Sauvegarde le nom d'utilisateur
                },
                'required' => false,
            ])
            ->add('benevole4', ChoiceType::class, [
                'label' => 'Bénévole 4',
                'choices' => $options['activeUsers'], // Utilisation de l'option activeUsers
                'choice_label' => function ($user) {
                    return $user->getUsername(); // Affiche le nom d'utilisateur
                },
                'choice_value' => function ($user) {
                    return $user ? $user->getUsername() : ''; // Sauvegarde le nom d'utilisateur
                },
                'required' => false,
            ])
            ->add('idSite', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'nomSite',
                'label' => 'Site',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PermananceSite::class,
        ]);
    }
}

<?php

// src/Form/RendezVousType.php

namespace App\Form;

use App\Entity\RendezVous;
use App\Entity\PermananceSite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;

class RendezVousType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Menu déroulant pour la sélection de PermananceSite
            ->add('idSite', EntityType::class, [
                'label' => 'Choix de la permanence',
                'class' => PermananceSite::class,
                'choice_label' => function (PermananceSite $permananceSite) {
                    // Construction de l'étiquette : dateAt - nom_site (benevole1 benevole2 benevole3 benevole4)
                    $benevoles = array_filter([
                        $permananceSite->getBenevole1(),
                        $permananceSite->getBenevole2(),
                        $permananceSite->getBenevole3(),
                        $permananceSite->getBenevole4()
                    ]);
                    $benevoleList = implode(' ', $benevoles);
                    return sprintf(
                        '%s - %s (%s)',
                        $permananceSite->getDateAt()->format('d-m-Y'),
                        $permananceSite->getIdSite()->getNomSite(),
                        $benevoleList
                    );
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->where('p.dateAt >= :today')  // Limiter aux dates >= aujourd'hui
                        ->setParameter('today', new \DateTimeImmutable('today'))
                        ->orderBy('p.dateAt', 'ASC');  // Tri par date croissante
                },
                'placeholder' => 'Choisissez une permanence',
                'required' => true,
            ])
            // Heure de début du rendez-vous
            ->add('heureAt', TimeType::class, [
                'widget' => 'single_text',
                'label' => 'Heure de début',
            ])
            // Heure de fin du rendez-vous
            ->add('heureEnd', TimeType::class, [
                'widget' => 'single_text',
                'label' => 'Heure de fin',
            ])
            // Commentaires sur le rendez-vous
            ->add('commentaires', TextareaType::class, [
                'label' => 'Commentaires',
                'required' => false,
                'attr' => ['rows' => 4],
            ])
            // Statut du rendez-vous (Choix unique)
            ->add('statut', ChoiceType::class, [
                'choices'  => [
                    'Annulé' => 'annulé',
                    'Confirmé' => 'confirmé',
                    'Non confirmé' => 'non confirmé',
                ],
                'expanded' => true,  // Affiche sous forme de boutons radio
                'multiple' => false, // Choix unique
                'label' => 'Statut du rendez-vous',
                'attr' => [
                    'class' => 'form-check-input',  // Style pour les boutons radio
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RendezVous::class,
        ]);
    }
}
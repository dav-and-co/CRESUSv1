<?php

namespace App\Form;

use App\Entity\Avancement;
use App\Entity\Demande;
use App\Entity\HistoriqueAvct;
use App\Repository\AvancementRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class HistoriqueAvctType extends AbstractType
{
    private $avancementRepository;

    public function __construct(AvancementRepository $avancementRepository)
    {
        $this->avancementRepository = $avancementRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // supprimé du formulaire car donnée sera renseigné dans la méthode avec la date du jour
            //->add('createdAt', DateType::class, [
            //    'widget' => 'single_text',
            //])

            ->add('avancement', EntityType::class, [
                'class' => Avancement::class,
                'query_builder' => function (AvancementRepository $repo) use ($options) {
                    return $repo->createQueryBuilder('a')
                        ->where('a.type_demande = :type')
                        ->setParameter('type', $options['type_demande'])
                        ->orderBy('a.libelle_avancement', 'ASC');
                },
                'choice_label' => 'libelle_avancement',
                'choice_value' => 'id',
            ])

            ->add('commentairesAvct', TextareaType::class, [
                'required' => false,
            ])

            // supprimé du formulaire car donnée sera reprise du contexte et non saisie
            //->add('demande', EntityType::class, [
            //    'class' => Demande::class,
            //    'choice_label' => 'id',
            //])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HistoriqueAvct::class,
            'type_demande' => null,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Evenement;
use App\Entity\Lieu;
use App\Entity\TagEvenement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => '📝 Titre',
                'attr' => [
                    'placeholder' => 'Saisissez le titre de l\'événement...',
                    'class' => 'form-control',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => '📄 Description',
                'attr' => [
                    'rows' => 8,
                    'placeholder' => 'Décrivez votre événement...',
                    'class' => 'form-control',
                ],
            ])
            ->add('dateDebut', DateTimeType::class, [
                'label' => '🕐 Date et heure de début',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dateFin', DateTimeType::class, [
                'label' => '🕐 Date et heure de fin',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'choice_label' => 'nom',
                'label' => '📍 Lieu',
                'placeholder' => '-- Choisir un lieu --',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('capaciteMax', TextType::class, [
                'label' => '👥 Capacité maximale',
                'attr' => [
                    'type' => 'number',
                    'min' => 1,
                    'placeholder' => 'Nombre de places',
                    'class' => 'form-control',
                ],
            ])
            ->add('prix', MoneyType::class, [
                'label' => '💰 Prix',
                'currency' => 'EUR',
                'divisor' => 1,
                'required' => false,
                'attr' => [
                    'placeholder' => '0.00',
                    'class' => 'form-control',
                ],
            ])
            ->add('categorie', ChoiceType::class, [
                'label' => '🎯 Catégorie',
                'choices' => [
                    '🎤 Conférence' => 'conference',
                    '🔧 Atelier' => 'atelier',
                    '👥 Meetup' => 'meetup',
                    '📚 Formation' => 'formation',
                    '🎵 Concert' => 'concert',
                ],
                'placeholder' => '-- Choisir une catégorie --',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('statut', ChoiceType::class, [
                'label' => '📊 Statut',
                'choices' => [
                    '📝 Brouillon' => 'brouillon',
                    '🟢 Publié' => 'publie',
                    '🔴 Complet' => 'complet',
                    '⚫ Annulé' => 'annule',
                ],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('imageName', FileType::class, [
                'label' => '🖼️ Image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG, PNG ou WebP)',
                    ])
                ],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('tags', EntityType::class, [
                'class' => TagEvenement::class,
                'choice_label' => 'nom',
                'label' => '🏷️ Tags',
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
                'required' => false,
                'attr' => ['class' => 'form-check'],
            ])
            ->add('enregistrer', SubmitType::class, [
                'label' => '💾 Enregistrer',
                'attr' => ['class' => 'btn btn-primary w-100 mt-3'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}

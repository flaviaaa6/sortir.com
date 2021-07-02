<?php

namespace App\Form;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Site;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la sortie'
            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'label' => 'Date et heure du début de la sortie',
                'years' => range(2021,2030),
                'widget' => 'single_text'
            ])
            ->add('dateLimiteInscription', DateTimeType::class, [
                'label' => 'Date limite d\'inscription',
                'years' => range(2021,2030),
                'widget' => 'single_text'
            ])
            ->add('nbrInscriptionMax', IntegerType::class, [
                'label' => 'Nombre limite de participants',
                'attr' => [
                    'min' => '1',
                    'max' => '50'
                ]
            ])
            ->add('duree', IntegerType::class, [
                'label' => 'La durée de la sortie (min)'
            ])
            ->add('infoSortie', TextareaType::class, [
                'label' => 'Description et infos :',
                'required' => 'false',
                'attr' => [
                    'label' => 'Merci de faire une description',
                    'maxlength' => 255,
                    'rows'=>5, 'cols'=>10
                ]
            ])
            ->add('site', EntityType::class, [
                'label' => 'Ville organisatrice',
                'class' => Site::class,
                'choice_label' => 'nom'
    
            ])
            ->add('lieu', EntityType::class, [
                'label' => 'Lieu :',
                'class' => Lieu::class,
                'choice_label' => 'nom'
               
            ])
            ->add('etat', EntityType::class, [
                'label' => 'L\'état de la sortie',
                'class' => Etat::class,
                'choice_label' => 'libelle'
             
            ])
            /*    ->add('organisateur', IntegerType::class, [
                    'label' => 'Organisateur de la sortie'
                ])
             */
            ->add('publier', SubmitType::class, [
                'label' => 'Publier la sortie',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la sortie :',
                'required' => 'true',
            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'label' => 'Date et heure de la sortie :',
                'years' => range(2021,2030),
                'widget' => 'single_text',
                'required' => 'true',
            ])
            ->add('dateLimiteInscription', DateTimeType::class, [
                'label' => 'Date limite d\'inscription',
                'years' => range(2021,2030),
                'widget' => 'single_text',
                'required' => 'true',
            ])
            ->add('nbrInscriptionMax', IntegerType::class, [
                'label' => 'Nombre de places :',
                'required' => 'true',
                'attr' => [
                    'min' => '1',
                    'max' => '50'
                ]
            ])
            ->add('duree', IntegerType::class, [
                'label' => 'Durée :'
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
          /*  ->add('ville', EntityType::class, [
                'label' => 'Ville :',
                'class' => Ville::class,
                'choice_label' => 'nom',
                'required' => 'true',
                'placeholder'=>'Choisissez une ville',
                'mapped'=>false,
    
            ])
        ;
        $builder ->get('ville')->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event){
                $form = $event->getForm();
                $form->getParent()->add('lieux',EntityType::class,[
                    'class'=> Lieu::class,
                    'placeholder' => 'Selectionner une ville',
                    'choices' => $form->getData()->getLieux()
                ]);
            }
        )*/




               ->add('lieu', EntityType::class, [
                'label' => 'Lieu :',
                'class' => Lieu::class,
                'choice_label' => 'nom',
                'required' => 'true',

               
            ])
            /*    ->add('organisateur', IntegerType::class, [
                    'label' => 'Organisateur de la sortie'
                ])
             */
            ->add('enregistrer', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
            ->add('publier', SubmitType::class, [
                'label' => 'Publier la sortie',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
            'compound' => true,
        ]);
    }
}

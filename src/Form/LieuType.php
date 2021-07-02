<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Ville;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ville', EntityType::class, [
                'label' => 'Ville :',
                'class' => Ville::class,
                'choice_label' => 'nom',
                'required' => 'true',
    
            ])
            ->add('nom', TextType::class, [
                'label' => 'Lieu :',
                'required' => true
            ])
            ->add('rue', null, [
                'label' => 'Rue :'
            ])
            ->add('latitude', null, [
                'label' => 'La latitude'
            ])
            ->add('longitude', null, [
                'label' => 'La longitude'
            ])
          ->add('submit',SubmitType::class, [
            'label' => 'Ajouter',
            'attr' => [
                'class' => 'btn btn-success'
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}

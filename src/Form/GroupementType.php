<?php

namespace App\Form;

use App\DTO\GroupementFormDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, ['label' => 'Nom du groupement'])
            ->add('telephone', TextType::class, ['label' => 'Téléphone'])
            ->add('ville', TextType::class, ['label' => 'Ville'])
            ->add('adresse', TextType::class, ['label' => 'Adresse', 'required' => false])
            ->add('enregistrer', SubmitType::class, ['label' => 'Enregistrer']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GroupementFormDTO::class,   // le formulaire remplit ce DTO, pas l'entité
        ]);
    }
}
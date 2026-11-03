<?php

namespace App\Form;

use App\DTO\GroupementFiltreDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupementFiltreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('telephone', TextType::class, [
                'label' => 'Téléphone',
                'required' => false,
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'required' => false,
            ])
            ->add('filtrer', SubmitType::class, [
                'label' => 'Filtrer',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GroupementFiltreDTO::class,
            'method' => 'GET',            // un filtre se lit dans l'URL : on peut le partager et le garder en changeant de page
            'csrf_protection' => false,   // pas de jeton pour une simple recherche en lecture
            'allow_extra_fields' => true, // le paramètre « page » de la pagination n'est pas un champ
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';                        // URL propre : ?telephone=77&ville=Dakar (au lieu de ?groupement_filtre[ville]=...)
    }
}
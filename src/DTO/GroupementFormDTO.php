<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * DTO d'entrée : ce que le formulaire d'ajout d'un groupement remplit.
 * Les contraintes de validation sont ici, pas dans le contrôleur ni dans la vue.
 */
class GroupementFormDTO
{
    #[Assert\NotBlank(message: 'Le nom est obligatoire.')]
    #[Assert\Length(max: 100, maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères.')]
    public ?string $nom = null;

    #[Assert\NotBlank(message: 'Le téléphone est obligatoire.')]
    #[Assert\Regex(pattern: '/^[0-9]{9}$/', message: 'Le téléphone doit contenir exactement 9 chiffres.')]
    public ?string $telephone = null;

    #[Assert\NotBlank(message: 'La ville est obligatoire.')]
    #[Assert\Length(max: 100, maxMessage: 'La ville ne peut pas dépasser {{ limit }} caractères.')]
    public ?string $ville = null;

    #[Assert\Length(max: 255, maxMessage: 'L\'adresse ne peut pas dépasser {{ limit }} caractères.')]
    public ?string $adresse = null;
}
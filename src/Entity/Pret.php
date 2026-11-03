<?php

namespace App\Entity;

use App\Repository\PretRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PretRepository::class)]
class Pret extends Campagne
{
}
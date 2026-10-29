<?php

namespace App\Controller;

use App\Service\CampagneService;
use App\Service\SeanceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CampagneController extends AbstractController
{
    public function __construct(
        private readonly CampagneService $campagneService,
        private readonly SeanceService $seanceService,
    ) {
    }

    #[Route('/groupement/{id}/campagnes', name: 'groupement_campagnes', requirements: ['id' => '\d+'])]
    public function campagnesDuGroupement(int $id): Response
    {
        $nomGroupement = $this->campagneService->getNomGroupement($id);
        if ($nomGroupement === null) {
            throw $this->createNotFoundException('Groupement introuvable.');
        }

        return $this->render('campagne/index.html.twig', [
            'groupementId' => $id,
            'groupementNom' => $nomGroupement,
            'campagnes' => $this->campagneService->getCampagnesDuGroupement($id),
        ]);
    }

    #[Route('/campagne/{id}/membres', name: 'campagne_membres', requirements: ['id' => '\d+'])]
    public function membres(int $id): Response
    {
        $campagne = $this->campagneService->getCampagne($id);
        if ($campagne === null) {
            throw $this->createNotFoundException('Campagne introuvable.');
        }

        return $this->render('campagne/membres.html.twig', [
            'campagne' => $campagne,
            'membres' => $this->campagneService->getMembresDeLaCampagne($id),
        ]);
    }

    #[Route('/campagne/{id}/seances', name: 'campagne_seances', requirements: ['id' => '\d+'])]
    public function seances(int $id): Response
    {
        $campagne = $this->campagneService->getCampagne($id);
        if ($campagne === null) {
            throw $this->createNotFoundException('Campagne introuvable.');
        }

        return $this->render('campagne/seances.html.twig', [
            'campagne' => $campagne,
            'seances' => $this->seanceService->getSeancesDeLaCampagne($id),
        ]);
    }
}
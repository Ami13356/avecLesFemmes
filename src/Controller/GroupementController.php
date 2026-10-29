<?php

namespace App\Controller;

use App\Service\GroupementService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GroupementController extends AbstractController
{
    public function __construct(
        private readonly GroupementService $groupementService,
    ) {
    }

    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->redirectToRoute('groupement_index');
    }

    #[Route('/groupement', name: 'groupement_index')]
    public function index(): Response
    {
        return $this->render('groupement/index.html.twig', [
            'groupements' => $this->groupementService->getListe(),
        ]);
    }
}
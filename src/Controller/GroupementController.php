<?php

namespace App\Controller;

use App\DTO\GroupementFiltreDTO;
use App\DTO\GroupementFormDTO;
use App\Form\GroupementFiltreType;
use App\Form\GroupementType;
use App\Service\GroupementService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * Une seule fonction : elle récupère le filtre, pagine et affiche.
     * Le DTO du formulaire est transmis au service, qui fait tout le traitement.
     */
    #[Route('/groupement', name: 'groupement_index')]
    public function index(Request $request): Response
    {
        $filtre = new GroupementFiltreDTO();
        $form = $this->createForm(GroupementFiltreType::class, $filtre);
        $form->handleRequest($request);

        $pagination = $this->groupementService->getListe(
            $filtre,
            $request->query->getInt('page', 1),
        );

        return $this->render('groupement/index.html.twig', [
            'form' => $form,
            'pagination' => $pagination,
        ]);
    }

    /**
     * Le contrôleur récupère le DTO rempli par le formulaire et l'envoie au service.
     */
    #[Route('/groupement/nouveau', name: 'groupement_new')]
    public function new(Request $request): Response
    {
        $donnees = new GroupementFormDTO();
        $form = $this->createForm(GroupementType::class, $donnees);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $groupement = $this->groupementService->creer($donnees);
            $this->addFlash('succes', sprintf('Le groupement « %s » a été créé.', $groupement->nom));

            return $this->redirectToRoute('groupement_index');
        }

        return $this->render('groupement/new.html.twig', [
            'form' => $form,
        ]);
    }
}
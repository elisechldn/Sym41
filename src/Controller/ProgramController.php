<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Program;
use App\Form\ProgramType;
use App\Entity\Season;
use App\Entity\Episode;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();

        return $this->render('program/index.html.twig', [
            'programs' => $programs
            ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager) : Response
    {
        // Create a new Program Object
        $program = new Program();
        // Create the associated Form
        $form = $this->createForm(ProgramType::class, $program);
        // Get data from HTTP request
        $form->handleRequest($request);
        // Was the form submitted ?
        if ($form->isSubmitted()) {
            // Deal with the submitted data
            // For example : persiste & flush the entity
            $entityManager->persist($program);
            $entityManager->flush();            
    
            // Redirect to categories list
            return $this->redirectToRoute('program_index');
        }

        // Render the form
        return $this->render('program/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/show/{id<^[0-9]+$>}}', methods: ['GET'], name: 'show')]
    public function show(Program $program): Response
    {

        return $this->render('program/show.html.twig', [
            'program' => $program,
            ]);
    }

    #[Route('/{program_id}/season/{season_id}', methods: ['GET'], name: 'season_show')]
    public function showSeason(
    #[MapEntity(mapping: ['program_id' => 'id'])] Program $program, 
    #[MapEntity(mapping: ['season_id' => 'id'])] Season $season
    ): Response {

        return $this->render('program/season_show.html.twig', [
            'program' => $program, 
            'season' => $season,
            ]);  
    }

    #[Route('/{program_id}/season/{season_id}/episode/{episode_id}', methods: ['GET'], name: 'episode_show')]
    public function showEpisode(
    #[MapEntity(mapping: ['program_id' => 'id'])] Program $program, 
    #[MapEntity(mapping: ['season_id' => 'id'])] Season $season,
    #[MapEntity(mapping: ['episode_id' => 'id'])] Episode $episode
    ): Response {

        return $this->render('program/season_show.html.twig', [
            'program' => $program, 
            'season' => $season,
            'episode' => $episode,
            ]);  
    }
}
<?php
// src/Controller/CategoryController.php
namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/category', name: 'category_')]
class CategoryController extends AbstractController
{

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index (CategoryRepository $categoryRepository): Response
    { 
        $categories = $categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager) : Response
    {
        // Create a new Category Object
        $category = new Category();
        // Create the associated Form
        $form = $this->createForm(CategoryType::class, $category);
        // Get data from HTTP request
        $form->handleRequest($request);
        // Was the form submitted ?
        if ($form->isSubmitted()) {
            // Deal with the submitted data
            // For example : persiste & flush the entity
            $entityManager->persist($category);
            $entityManager->flush();

            // Once the form is submitted, valid and the data inserted in database, you can define the success flash message
            $this->addFlash('success', 'Une nouvelle catégorie a été ajoutée');
            
            // And redirect to a route that display the result
            return $this->redirectToRoute('category_index');
        }

        // Render the form
        return $this->render('category/new.html.twig', [
            'form' => $form,
            ]);
    }

    #[Route('/{categoryName}', name: 'show')]
    public function show (string $categoryName, CategoryRepository $categoryRepository, ProgramRepository $programRepository): Response
    { 
        $category = $categoryRepository->findOneBy(
            ['name'=> $categoryName]
        );

        if(!$category) {
            throw $this->createNotFoundException(
                'No program with name : ' . $categoryName . ' found in category\'s table.'
            );
        }
// requête SQL : Si une catégorie existe, tu récupères depuis le Program, la catégorie par son id et l'id du program
        if ($category) {
            $program = $programRepository->findBy(
                ['category'=> $category->getID()], 
                ['id'=> 'DESC'],
                3,
                0
            );
        }
        return $this->render('category/show.html.twig', [
        'categories' => $categoryName, 'programs' => $program
        ]);
    }
}
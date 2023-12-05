<?php
// src/Controller/CategoryController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;

#[Route('/category', name: 'category_')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index (CategoryRepository $categoryRepository): Response
    { 
        $categories = $categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories
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
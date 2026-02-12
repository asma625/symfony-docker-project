<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use App\Form\AddCategoriesFormType;
use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/category', name: 'app_admin_category_')]
final class CategoryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    #[Route('/add', name: 'add')]
    public function addCategory(Request $request, CategoriesRepository $categoriesRepository): Response
    {
       
        $categorie = new Categories();
        $categorieForm = $this->createForm(AddCategoriesFormType::class, $categorie);
        $categorieForm->handleRequest($request);
        if ($categorieForm->isSubmitted() && $categorieForm->isValid()) {
            $categoriesRepository->save($categorie, true);

            $this->addFlash('success', 'La catégorie a été ajoutée avec succès.');
            return $this->redirectToRoute('app_admin_category_index');
            
        }
        return $this->render('admin/category/add.html.twig', [
                'categorieForm' => $categorieForm->createView(),
            ]);
    }
}

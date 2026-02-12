<?php

namespace App\Controller\Admin;

use App\Entity\Keywords;
use App\Form\AddKeywordFormType;
use App\Repository\KeywordsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/keywords', name: 'app_admin_keywords_')]
final class KeywordsController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/keywords/index.html.twig', [
            'controller_name' => 'KeywordsController',
        ]);
    }

    #[Route('/add', name: 'add')]
    public function addKeyword(Request $request, KeywordsRepository $keywordsRepository): Response
    {
        $keyword = new Keywords();
        $keywordForm = $this->createForm(AddKeywordFormType::class, $keyword);
        $keywordForm->handleRequest($request);
        if ($keywordForm->isSubmitted() && $keywordForm->isValid()) {
            $keywordsRepository->save($keyword, true);

            $this->addFlash('success', 'Le mot-clé a été ajouté avec succès.');

            return $this->redirectToRoute('app_admin_keywords_index');
        }
        
        return $this->render('admin/keywords/add.html.twig', [
            'keywordForm' => $keywordForm->createView(),
        ]);
    }
}

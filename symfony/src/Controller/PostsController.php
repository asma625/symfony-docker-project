<?php

namespace App\Controller;

use App\Repository\PostsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PostsController extends AbstractController
{
    #[Route('/posts', name: 'app_posts')]
    public function index(PostsRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();

        return $this->render('posts/index.html.twig', [
            'controller_name' => 'PostsController',
            'posts' => $posts,
        ]);
    }

    #[Route('/posts/{category}', name: 'app_post_show')]
    public function show(string $category, PostsRepository $postRepository): Response
    {
        //$posts = $postRepository->findByCategories($category);
         $posts = $postRepository->findBy(['categories' => $category], ['createdAt' => 'DESC']);
        return $this->render('posts/show.html.twig', [
            'controller_name' => 'PostsController',
            'posts' => $posts,
        ]);


    }
}
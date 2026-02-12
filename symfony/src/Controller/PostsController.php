<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Entity\User;
use App\Form\AddPostsFormType;
use App\Form\Data\UsersRegistrationDto;
use App\Form\Type\UsersRegistrationFormType;
use App\Repository\PostsRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/posts', name: 'app_posts')]
final class PostsController extends AbstractController
{
    #[Route('/', name: '_index')]
    public function index(PostsRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();

        return $this->render('posts/index.html.twig', [
            'controller_name' => 'PostsController',
            'posts' => $posts,
        ]);
    }


    
    #[Route('/add', name: '_add')]
    public function addCategory(Request $request, PostsRepository $postRepository): Response
    {
       
        $post = new Posts();
        $postForm = $this->createForm(AddPostsFormType::class, $post);
        $postForm->handleRequest($request);

        if ($postForm->isSubmitted() && $postForm->isValid()) {
            $post->setUser($this->getUser());
            $post->setFeatureImage('test.jpg');
            $postRepository->save($post, true);

            $this->addFlash('success', 'L\'article a été ajouté avec succès.');
            return $this->redirectToRoute('app_posts_index');
            
        }
        return $this->render('posts/add.html.twig', [
                'postForm' => $postForm->createView(),
            ]);
    }

     #[Route('/travel-registration', name: 'app_travel_registration')]
    public function registerpost(Request $request, UserRepository $userRepository): Response
    {
        $UserData = new UsersRegistrationDto();

        /** @var FormFlowInterface $flow */
        $flow = $this->createForm(UsersRegistrationFormType::class, $UserData)
            ->handleRequest($request);

        if ($flow->isSubmitted() && $flow->isValid() && $flow->isFinished()) {
            // Stocker les données en session pour la page de succès
            $user = new User();
            $user->setFirstname($UserData->users->firstName);
            $user->setLastname($UserData->users->lastName);
            $user->setAdress($UserData->adress->adress);
            $user->setCity($UserData->adress->city);
            $user->setZipcode($UserData->adress->zipcode);

            $pos


            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_registration_success');
        }

        return $this->render('posts_registration/form.html.twig', [
            'form' => $flow->getStepForm(),
            'currentStep' => $UserData->currentStep,
            'data' => $UserData,
        ]);
    }

 
}
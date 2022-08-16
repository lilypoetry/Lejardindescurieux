<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\UserFormType;
use Knp\Component\Pager\PaginatorInterface;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }

    #[Route('/account/edit/{id}', name: 'edituser', requirements: ['id' => '\d+'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        
        $form = $this->createForm(UserFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);
            
            $this->addFlash('success', 'Votre compte a bien été modifié !');

            return $this->redirectToRoute('app_account');
            
        }
        return $this->render('account/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Route pour afficher toutes les utilisateur
    #[Route('/account/user', name: 'user_account')]
    public function users(UserRepository $userRepository, Request $request, PaginatorInterface $paginatorInterface): Response
    {
        $results = $userRepository->findAll();

        $query = $request->query->get('query');
        if ($query) {
            $results = $userRepository->findUserByName($query);
        }

        $users = $paginatorInterface->paginate(
            $results,
            $request->query->getInt('page', 1),
            $request->query->getInt('numbers', 5),
        );

        return $this->render('account/users.html.twig', [
            'users' => $users
        ]);
    }
    
    #[Route('/search', name: 'search')]
    public function handleSearch(Request $request, UserRepository $userRepository, PaginatorInterface $paginatorInterface)
    {
        /** Création d'une requête permettant de récupérer les articles pour la page actuelle, 
         * dont le titre ou le contenu contient la recherche de l'utilisateur 
         * */
        $query = $request->query->get('query');
        $users = $userRepository->findAll($query);
        if ($query) {
            // Création de la pagination résultats limité par 5
            $userRepository = $paginatorInterface->paginate(
                $userRepository->findUserByName($query),
                $request->query->getInt('page', 1),
                $request->query->getInt('numbers', 5)
            );

            // $categories = $categoryRepository->findAll($query);
            // $categories = $paginatorInterface->paginate(
            //     $categoryRepository->findAll($query),
            //     $request->query->getInt('page',1),6
            // );
        }
        // dd($users);

        return $this->render('account/users.html.twig', [
            'users' => $users
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\UserFormType;

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
}

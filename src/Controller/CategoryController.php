<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /** 
     * Route pour afficher la liste des categories
     */
    #[Route('/category', name: 'app_category')]
    public function index(CategoryRepository $categoryRepository, ArticleRepository $articleRepository, Request $request): Response
    {
        $category = $categoryRepository->findAll();
        $id = $request->query->get("id");
        $articles = $articleRepository->findBy(['category' => $id]);

        return $this->render('category/index.html.twig', [
            'categories' => $category,
            'articles' => $articles
        ]);
    }

    /** 
     * Route pour afficher le formulaire d'ajoute la nouvelle category
     */
    #[Route('/category/new', name: 'new_category')]
    public function new(Request $request, CategoryRepository $categoryRepository): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->add($category, true);
            $this->addFlash('success', 'La catégorie a bien été enregistrée');

            // Redirection vers une autre page
            return $this->redirectToRoute('app_category');
        }
        return $this->render('category/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /** 
     * Route pour recupecer une category par id et editer
     */
    #[Route('/category/edit/{id}', name: 'edit_category', requirements:['id' => '\d+'])]
    public function edit(Category $category, Request $request, CategoryRepository $categoryRepository): Response 
    {
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->add($category, true);
            $this->addFlash('success', 'La modification à bien été enregistrée');

            // Redirection vers une autre page
            return $this->redirectToRoute('app_category');
        }

        return $this->render('category/edit.html.twig', [
            'form' => $form->createView() 
        ]);
    }

    /** 
     * Route pour supprimer une category
     */
    #[Route('/category/delete/{id}', name: 'delete_category', requirements:['id' => '\d+'], methods: ['POST'])]
    public function delete(Category $category, Request $request, CategoryRepository $categoryRepository): RedirectResponse 
    {
       $tokenCsrf = $request->request->get('token');
       
            // Protection par token pour éviter de se faire supprimer des catégories à distanse
       if ($this->isCsrfTokenValid('delete-category-'. $category->getId(), $tokenCsrf)) {

            $categoryRepository->remove($category, true);
            $this->addFlash('success', 'La catégorie a bien été supprimée'); 
       }

       return $this->redirectToRoute('app_category');
    }
}

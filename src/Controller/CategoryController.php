<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
    public function index(PaginatorInterface $paginatorInterface, CategoryRepository $categoryRepository, ArticleRepository $articleRepository, Request $request): Response
    {
        $categories = $paginatorInterface->paginate(
            $categoryRepository->findAll(),
            $request->query->getInt('page', 1),
            $request->query->getInt('numbers', 5)
        );

        // $categories = $categoryRepository->findAll();
        $id = $request->query->get("id");
        $articles = $articleRepository->findBy(['category' => $id]);

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
            'articles' => $articles
        ]);
    }

    /** 
     * Route pour afficher le formulaire d'ajoute la nouvelle category
     */
    #[IsGranted('ROLE_ADMIN')]
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
    #[IsGranted('ROLE_ADMIN')]
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
    #[IsGranted('ROLE_ADMIN')]
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

    /** 
     * Route pour afficher les articles du même category
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/category/list', name: 'list_category')]
    public function category(Request $request, CategoryRepository $categoryRepository, ArticleRepository $articleRepository, PaginatorInterface $paginatorInterface): Response
    {
        $articles = $articleRepository->findArticleByCategoryId();
        
        // $categories = $categoryRepository->findAll();
        $id = $request->query->get("id");
        $categories = $categoryRepository->findBy(['article' => $id]);

        

        

        return $this->render('category/list.html.twig', [
            'categories' => $categories,
            'articles' => $articles
        ]);
    }
}

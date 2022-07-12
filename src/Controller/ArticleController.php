<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->findAll();

        return $this->render('article/index.html.twig', [
            'articles' => $article
        ]);
    }

    #[Route('/article/new', name: 'new_article')]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, ArticleRepository $articleRepository): Response
    {
        $article = new Article();

        $form = $this->createForm(ArticleFormType::class, $article, [
            'validation_groups' => ['new']
        ]);
        $form->handleRequest($request);
        //dd($article);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUser($this->getUser());
            $articleRepository->add($article, true);
            $this->addFlash('success', 'L\'article a bien été enregistrée');
            
            // Redirection vers une autre page
            return $this->redirectToRoute('app_article');
        }
        return $this->render('article/new.html.twig', [
            'form' => $form->createView()
        ]);        

    }

    #[Route('/article/edit/{id}', name: 'edit_article', requirements:['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Article $article, Request $request, ArticleRepository $articleRepository): Response
    {
        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articleRepository->add($article, true);
            $this->addFlash('success', 'La modification a bien été enregistrée');

            // Redirection vers une autre page
            return $this->$this->redirectToRoute('app_article');
        }
        return $this->render('article/edit.html.twig', [
            'form' => $form->createView()
        ]);        
    }

    #[Route('/article/delete/{id}', name: 'delete_article', requirements:['id' => '\d+'], methods: ['POST'])]
    public function delete(Article $article, Request $request, ArticleRepository $articleRepository): RedirectResponse 
    {
       $tokenCsrf = $request->request->get('token');
       
            // Protection par token pour éviter de se faire supprimer des catégories à distanse
       if ($this->isCsrfTokenValid('delete-article-'. $article->getId(), $tokenCsrf)) {

            $articleRepository->remove($article, true);
            $this->addFlash('success', 'L\'article a bien été supprimée'); 
       }

       return $this->redirectToRoute('app_article');
    }
}

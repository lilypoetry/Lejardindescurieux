<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use App\Entity\Market;
use App\Form\ArticleFormType;
use App\Form\CategoryFormType;
use App\Form\MarketFormType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\MarketRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;


class AdminController extends AbstractController
{
    // Route pour afficher toutes les articles
    #[Route('/admin', name: 'app_admin')]
    public function index(CategoryRepository $categoryRepository, ArticleRepository $articleRepository, Request $request, PaginatorInterface $paginatorInterface): Response
    {
        $results = $articleRepository->findAll();

        $query = $request->query->get('query');
        if ($query) {
            $results = $articleRepository->findArticlesByName($query);
        }

        $articles = $paginatorInterface->paginate(
            $results,
            $request->query->getInt('page', 1),
            $request->query->getInt('numbers', 100),
        );

        return $this->render('admin/index.html.twig', [
            'articles' => $articles
        ]);
    }

    // Route pour ajouter un article
    #[Route('/admin/article/new', name: 'admin_new_article')]
    #[IsGranted('ROLE_ADMIN')]
    public function newArticle(Request $request, ArticleRepository $articleRepository): Response
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
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/newArticle.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // Route pour editer un article
    #[Route('/admin/article/edit/{id}', name: 'admin_edit_article', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    public function editArticle($id = null, Article $article, Request $request, ArticleRepository $articleRepository): Response
    {
        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);
        $idarticle = $articleRepository->find($id);

        if (!$idarticle) {
            $this->addFlash('error', " L'id $id n'existe pas");
            return $this->render('bundles/TwigBundle/Exception/error404.html.twig');
        } else {
            if ($form->isSubmitted() && $form->isValid()) {
                $articleRepository->add($article, true);
                $this->addFlash('success', 'L\'article à bien été modifier');

                // Redirection vers une autre page
                return $this->redirectToRoute('app_admin');
            }
        }

        return $this->render('admin/editArticle.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    // Route pour supprimer un article
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/article/delete/{id}', name: 'admin_delete_article', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function deleteArticle(Article $article, Request $request, ArticleRepository $articleRepository): RedirectResponse
    {
        $tokenCsrf = $request->request->get('token');
        if ($this->isCsrfTokenValid('delete-article-' . $article->getId(), $tokenCsrf)) {
            $articleRepository->remove($article, true);
            $this->addFlash('success', 'L\'article à bien été supprimée');
            $success = true;
        }

        return $this->redirectToRoute('app_admin', [
            'success' => $success
        ]);
    }

    /** Route pour voir article en details */
    #[Route('/admin/article/{id}', name: 'admin_detail_article', requirements:['id' => '\d+'])]
    public function details(Article $article): Response 
    {
        return $this->render('admin/detailArticle.html.twig', [
            'article' => $article
        ]);
    }    

    // Route pour afficher les articles par catégorie
    #[Route('/admin/listcat', name: 'admin_listcat')]
    public function list(CategoryRepository $categoryRepository, Request $request, ArticleRepository $articleRepository, PaginatorInterface $paginatorInterface): Response
    {   
        // SuperGloball en GET 
        $id = $request->query->get("id");    

        // Cherche tous les articles
        $category = $categoryRepository->findAll();
        $articles = $paginatorInterface->paginate(
            // $articleRepository->findBy(['category' => $id]),
            $articleRepository->findAll(),
            $request->query->getInt('page', 1),
            5
        );   

        // Si il existe un article de catégorie
        if ($id) {
            $idcat = $categoryRepository->find($id);
            // // Si pas d'article page erreur 404
            if (!$idcat) {
                $this->addFlash('error', " L'id $id n'existe pas");
                return $this->render('bundles/TwigBundle/Exception/error404.html.twig');
            } 
            else {
                // Affiche l'article de categorie
                $articles = $paginatorInterface->paginate(
                    $articleRepository->findBy(['category' => $id]),
                    // $articleRepository->findAll(),
                    $request->query->getInt('page', 1),
                    5
                );
            }
        }
        // $categories = $categoryRepository->findAll();
        return $this->render('admin/list.html.twig', [
            'categories' => $category,
            'articles' => $articles,
        ]);
    }

    // Route pour afficher les catégories
    // #[Route('/admin/categorie', name: 'app_admin_category')]
    #[Route('/admin/category', name: 'admin_category')]
    public function categorie(CategoryRepository $categoryRepository, Request $request, PaginatorInterface $paginatorInterface): Response
    {
        $categories = $paginatorInterface->paginate(
            $categoryRepository->findAll(),
            $request->query->getInt('page', 1),
            $request->query->getInt('numbers', 100)
        );

        return $this->render('admin/category.html.twig', [
            'categories' => $categories
        ]);
    }

    // Route pour ajouter une nouvelle catégorie
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/category/new', name: 'admin_new_category')]
    public function new(Request $request, CategoryRepository $categoryRepository): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->add($category, true);
            $this->addFlash('success', 'La catégorie a bien été enregistrée');

            // Redirection vers une autre page
            return $this->redirectToRoute('admin_category');
        }
        return $this->render('admin/newCategory.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // Route pour editer un categorie
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/category/edit/{id}', name: 'admin_edit_category', requirements: ['id' => '\d+'])]
    public function edit($id = null, Category $category, Request $request, CategoryRepository $categoryRepository): Response
    {
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->add($category, true);
            $this->addFlash('success', 'La catégorie à bien été modifier');

            // Redirection vers une autre page
            return $this->redirectToRoute('admin_category');
        }
        return $this->render('admin/editCategories.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // Route pour supprimer un catégorie
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/categories/delete/{id}', name: 'admin_delete_category', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function deleteCate(Category $category, Request $request, CategoryRepository $categoryRepository): RedirectResponse
    {

        $tokenCsrf = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete-category-' . $category->getId(), $tokenCsrf)) {
            $categoryRepository->remove($category, true);
            $this->addFlash('success', 'La catégorie à bien été supprimée');
            $success = true;
        }

        return $this->redirectToRoute('admin_category', [
            'success' => $success
        ]);
    }

    // Route pour afficher toutes les utilisateur
    #[Route('/admin/users', name: 'admin_users')]
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
            $request->query->getInt('numbers', 100)
        );

        return $this->render('admin/users.html.twig', [
            'users' => $users
        ]);
    }

    // Route pour editer le role utilisateur
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/users/edit/{id}/{role}', name: 'app_admin_user_role', methods: ['POST'])]
    public function roles(User $user, string $role, UserRepository $userRepository,): JsonResponse
    {

        $user->setRoles([$role]);
        $userRepository->add($user, true);

        return $this->json(['role' => $role]);
    }
    
    // Route pour afficher tous les marchés
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/market', name: 'admin_market')]
    public function listMarket(Request $request, MarketRepository $marketRepository, PaginatorInterface $paginatorInterface): Response
    {
        $markets = $paginatorInterface->paginate(
            $marketRepository->findAll(),
            $request->query->getInt('page', 1),
            $request->query->getInt('numbers', 100)
        );
        
        return $this->render('admin/listMarket.html.twig', [
            'markets' => $markets
        ]);
    }

    // Route pour ajouter une nouveau marché
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/market/new', name: 'admin_new_market')]
    public function newMarket(Request $request, MarketRepository $marketRepository): Response
    {
        $market = new Market();

        $form = $this->createForm(MarketFormType::class, $market, [
            'validation_groups' => ['new']
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $market->setUser($this->getUser());
            $marketRepository->add($market, true);
            $this->addFlash('success', 'Le nouveau evenement a bien été enregistrée');

            // Redirection vers une autre page
            return $this->redirectToRoute('admin_market');
        }
        return $this->render('admin/newMarket.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // Route pour editer le marché
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/market/edit/{id}', name: 'admin_edit_market', requirements: ['id' => '\d+'])]
    public function editMarket($id = null, Request $request, Market $market, MarketRepository $marketRepository): Response
    {
        $form = $this->createForm(MarketFormType::class, $market);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $marketRepository->add($market, true);
            $this->addFlash('success', 'Le nouveau evenement a bien été enregistrée');

            // Redirection vers une autre page
            return $this->redirectToRoute('admin_market');
        }
        return $this->render('admin/editMarket.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    // Route pour supprimer un marché
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/market/delete/{id}', name: 'admin_delete_market', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function deleteMarket(Market $market, Request $request, MarketRepository $marketRepository): RedirectResponse
    {

        $tokenCsrf = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete-market-' . $market->getId(), $tokenCsrf)) {
            $marketRepository->remove($market, true);
            $this->addFlash('success', 'L\'évènement à bien été supprimé');
            $success = true;
        }

        return $this->redirectToRoute('admin_market', [
            'success' => $success
        ]);
    }
}

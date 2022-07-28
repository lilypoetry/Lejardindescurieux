<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\MarketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CategoryRepository $categoryRepository, Request $request, ArticleRepository $articleRepository, PaginatorInterface $paginatorInterface): Response
    {
        $category = $categoryRepository->findAll();
        $id = $request->query->get("id");
        // $articles = $articleRepository->findBy(['category' => $id]);

        // Pour afficher toutes les articles un page d'accueil
        $articles = $paginatorInterface->paginate(
            $articleRepository->findAll(),
            $request->query->getInt('page', 1),
            $request->query->getInt('numbers', 6),
        );

         if ($id) {
             $idcat = $categoryRepository->find($id);
            if (!$idcat) {
                
                 $this->addFlash('error', " L'id " . $id ."n\'existe pas");
                return $this->render('bundles/TwigBundle/Exception/error404.html.twig');
             } else {
        
                $articles = $paginatorInterface->paginate(
                    $articleRepository->findBy(['category' => $id]),
                    $request->query->getInt('page', 1),
                    5
                );
            }
         }

        return $this->render('home/index.html.twig', [
            'categories' => $category,
            'articles' => $articles,
        ]);
    }

    /**
     * Route pour l"histoire du société
     */
    #[Route('/home/about', name: 'about')]
    public function about()
    {
        return $this->render('home/about.html.twig');
    }

    /**
     * Route pour la liste du marché
     */
    #[Route('/home/market', name: 'market')]
    public function market(MarketRepository $marketRepository, Request $request, PaginatorInterface $paginatorInterface): Response
    {
        $markets = $paginatorInterface->paginate(
            $marketRepository->findAll(),        
            $request->query->getInt('page', 1),
            $request->query->getInt('numbers', 5)
        );
        return $this->render('home/market.html.twig', [
            'markets' => $markets
        ]);
    }

    /**
     * La route pour afficher les résultats des recherches par le formulaire de recherche dans la navbar
     */
    #[Route('/search', name: 'search')]
    public function handleSearch(Request $request, ArticleRepository $articleRepository, PaginatorInterface $paginatorInterface, CategoryRepository $categoryRepository)
    {
        /** Création d'une requête permettant de récupérer les articles pour la page actuelle, 
         * dont le titre ou le contenu contient la recherche de l'utilisateur 
         * */
        $query = $request->query->get('query');
        $articles = $articleRepository->findAll($query);
        $categories = $categoryRepository->findAll($query);
        if ($query) {
            // Création de la pagination résultats limité par 5
            $articles = $paginatorInterface->paginate(
                $articleRepository->findArticlesByName($query),
                $request->query->getInt('page', 1),
                6
            );

            $categories = $categoryRepository->findAll($query);
            // $categories = $paginatorInterface->paginate(
            //     $categoryRepository->findAll($query),
            //     $request->query->getInt('page',1),6
            // );
        }
        // dd($articles);

        return $this->render('home/result.html.twig', [
            'articles' => $articles,
            'categories' => $categories
        ]);
    }

}

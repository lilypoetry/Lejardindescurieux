<?php

namespace App\Controller;


// use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
// use Doctrine\Persistence\ManagerRegistry;
// use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]

    public function index(CategoryRepository $categoryRepository, Request $request, ArticleRepository $articleRepository, PaginatorInterface $paginatorInterface): Response
    {
        $category = $categoryRepository->findAll();
        $id = $request->query->get("id");
        $articles = $articleRepository->findBy(['category' => $id]);

        if($id)
        {
            $idcat = $categoryRepository->find($id);
            if(!$idcat){
                $this->addFlash('error'," L'id $id n'existe pas");
                return $this->render('bundles/TwigBundle/Exception/error404.html.twig');

               }
               else{
                
                $articles = $paginatorInterface->paginate(
                    $articleRepository->findBy(['category' => $id]),
                    $request->query->getInt('page',1),6
                );
               }
        }
        
        return $this->render('home/index.html.twig', [
            'categories' => $category,
            'articles' => $articles,
        ]);
        //return $this->render('home/index.html.twig');

        /* $categories = $categoryRepository->findAll();

        return $this->render('home/index.html.twig', [
            'articles' => $articles,
            'categories' => $categories,
        ]); */
    }

    /**
     * La route pour afficher les résultats des recherches par le formulaire de recherche dans la navbar
     */
    #[Route('/search', name: 'search')]
    public function handleSearch(Request $request, ArticleRepository $articleRepository, PaginatorInterface $paginatorInterface, CategoryRepository $categoryRepository)
    {
        $query = $request->query->get('query');
        if($query) {
            $articles = $paginatorInterface->paginate(
                $articleRepository->findArticlesByName($query),
                $request->query->getInt('page',1),6
            );
            
            $categories = $categoryRepository->findAll($query);
        }
        // dd($articles);

        return $this->render('home/result.html.twig', [
            'articles' => $articles,
            'categories' => $categories
        ]);
    }

    /**
     * Contrôleur de la page affichant les résultats des recherches faites par le formulaire de recherche dans la navbar
     */
    /* #[Route('/recherche/', name: 'search')]
    public function search(Request $request, PaginatorInterface $paginator, ManagerRegistry $doctrine): Response
    {

        // Récupération du numéro de la page demandée dans l'url (si il existe pas, 1 sera pris à la place)
        $requestedPage = $request->query->getInt('page', 1);

        // Si la page demandée est inférieur à 1, erreur 404
        if ($requestedPage < 1) {
            throw new NotFoundHttpException();
        }

        // On récupère la recherche de l'utilisateur depuis l'url ($_GET['q'])
        $search = $request->query->get('s', '');

        // Récupération du manager général des entités
        $em = $doctrine->getManager();

        // Création d'une requête permettant de récupérer les articles pour la page actuelle, dont le titre ou le contenu contient la recherche de l'utilisateur
        $query = $em
            ->createQuery('SELECT a FROM App\Entity\Article a WHERE a.title  LIKE :search OR a.description LIKE :search ORDER BY a.updatedAt DESC')
            ->setParameters([
                'search' => '%' . $search . '%',
            ]);

        // Récupération des articles
        $articles = $paginator->paginate(
            $query,
            $requestedPage,
            10
        );

        // Appel de la vue en lui envoyant les articles à afficher
        return $this->render('home/result.html.twig', [
            'articles' => $articles,
        ]);
    } */

}


    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }
}


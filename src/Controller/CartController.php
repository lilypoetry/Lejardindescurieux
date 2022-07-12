<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


class CartController extends AbstractController
{
    #[Route("/cart", name: "app_cart")]    
    public function index(SessionInterface $session, ArticleRepository $articleRepository): Response
    {
        $cart = $session->get('cart', []);

        $dataCart = [];
        $total = 0;

        // On boucle sur le panier en récupérant l'id et les quantités
        foreach($cart as $id => $quantite) {
            $article = $articleRepository->find($id);

            // on envoie les information dans le tableau
            $dataCart = [
                'article' => $article,
                'quantite' => $quantite
            ];
            $total += $article->getPrice() * $quantite;
        }
        return $this->render('cart/index.html.twig', compact('dataCart', 'total'));
    }

    #[Route("/add/{id}", name: "add_cart")] 
    public function add($id, SessionInterface $session, Article $article)
    {
         // On récupère le panier actuel
         $cart = $session->get('cart',[]);

         // On vérifie que l'id du produit existe
         $id = $article->getId();
 
         // On vérifie si l'id existe déjà dans le panier
         // Si l'id n'existe pas on incrémente 1
         if(!empty($cart[$id])){
             $cart[$id]++;
 
         // Sinon on initialise le panier à 1
         }else{
             $cart[$id] = 1;
         }
         // Message de succès
         $this->addFlash('success', 'Produit ajouté au panier');
 
         // On sauvegarde dans la session
         $session->set('cart', $cart);
 
         return $this->redirectToRoute('app_cart');
        // $session->set('panier', 3);
        // dd($session->get('panier'));
    }

    // public function add($id, Request $request)
    // {
    //     $session = $request->getSession();

    //     $panier = $session->get('panier', []);

    //     $panier[$id] = 1;

    //     $session->set('panier', $panier);
    //     dd($session->get('panier'));
    // }

}

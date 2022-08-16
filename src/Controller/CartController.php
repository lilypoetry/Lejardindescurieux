<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


class CartController extends AbstractController
{
    /**
     * Controller de la page de panier
     */
    #[Route('/cart', name: 'app_cart')]
    public function index(SessionInterface $session, ArticleRepository $articleRepository): Response
    {
        // On récupère le panier
        $cart = $session->get('cart',[]);

        // on construit les données
        $dataCart = [];
        $total = 0;
        $quantityCart = 0;

        // On boucle sur le panier en récupérant l'id et les quantités
        foreach ($cart as $id => $quantity){
            // On récupére le/les article(s) par leur id
            $article = $articleRepository->find($id);
            // on envoie les information dans le tableau
            $dataCart[] = [
                'article' => $article,
                'quantity' => $quantity,
            ];

            $quantityCart += 0 + $quantity;

            $total += $article->getPrice() * $quantity;

        }

        // On envoie les informations à la vue
        return $this->render('cart/index.html.twig', compact('dataCart', 'total', 'quantityCart'));

    }

    /**
     * Contrôleur de la page permettant l'ajout d'un article au panier
     */
    #[Route('/ajouter-au-panier/{id}', name: 'add_cart')]
    public function add(Article $article, SessionInterface $session)
    {

        // On récupère le panier actuel
        $cart = $session->get('cart',[]);

        // On vérifie que l'id du article existe
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
        $this->addFlash('success', 'L\'article a bien été ajouté au panier');

        // On sauvegarde dans la session
        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');

    }

    /**
     * Contrôleur de la page pour retirer un article du panier (-1 si plusieurs)
     */
    #[Route('/supprimer-du-panier/{id}', name: 'remove_cart')]
    public function remove(Article $article, SessionInterface $session)
    {

        // On récupère le panier actuel
        $cart = $session->get('cart',[]);

        // On vérifie que l'id du article existe
        $id = $article->getId();

        // On vérifie si l'id existe déjà dans le panier
        // Si l'id n'existe pas on incrémente 1
        if(!empty($cart[$id])) {

            // Si l'id est plus grand que 1
            if ($cart[$id] > 1) {
                // On retire 1
                $cart[$id]--;

                // Sinon on retire la ligne
            } else {
                unset($cart[$id]);
            }
        }else{
            $cart[$id] = 1;
        }
        // Message de succès
        $this->addFlash('success', 'L\'article a bien été supprimé du panier');

        // On sauvegarde dans la session
        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');

    }


    /**
     * Contrôleur de la page pour supprimer une ligne complète du panier
     */
    #[Route('/supprimer-element-panier/{id}', name: 'delete_cart')]
    public function delete(Article $article, SessionInterface $session)
    {

        // On récupère le panier actuel
        $cart = $session->get('cart',[]);

        // On vérifie que l'id du produit existe
        $id = $article->getId();

        // On vérifie si le panier n'est pas vide
        if(!empty($cart[$id])) {
            // on supprime le produit
            unset($cart[$id]);
        }

        // Message de succès
        $this->addFlash('success', 'L\'article a bien été supprimé du panier');

        // On sauvegarde dans la session
        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }

    /**
     * Contrôleur de la page de suppression du panier
     */
     #[Route('/vider-le-panier/', name: 'deleteAll_cart')]
        public function deleteAll(SessionInterface $session)
    {
        // On récupère le panier actuel
        $session->remove('cart',[]);

        // Message de succès
        $this->addFlash('success', 'Votre panier à été vider avec succès');

        return $this->redirectToRoute('app_cart');
    }
}

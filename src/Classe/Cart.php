<?php

namespace App\Classe;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{
    private $session;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    // Ajouter contenu panier
    public function add($id)
    {
        $cart = $this->session->get('cart', []);

        if(!empty($cart[$id])){
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $this->session->set('cart', $cart);
    }

    // Recuperer contenu panier
    public function get()
    {
        return $this->session->get('cart');
    }

    // supprimer tout le panier
    public function remove()
    {
        return $this->session->remove('cart');
    }

    // supprimer contenu panier
    public function delete($id)
    {
        $cart = $this->session->get('cart', []);
        
        unset($cart[$id]);

        return $this->session->set('cart', $cart);
    }

    // supprimer un element du panier
    public function decrease($id)
    {
        $cart = $this->session->get('cart', []);

        if($cart[$id] > 1){
            $cart[$id]--;
        } else {
            unset($cart[$id]);
        }

        $this->session->set('cart', $cart);
    }

    public function getFull()
    {
        $cartComplete = [];

        // Pour l'acces aux informations de chaque produit dans le panier
        if($this->get()){
            foreach ($this->get() as $id => $quantity){
                $productObject =$this->entityManager->getRepository(Product::class)->findOneBy(['id' => $id]);
                
                // Si un petit malin s'aventure à changer les url en mettant des nombres id bizarres
                if(!$productObject){
                    $this->delete($id);
                    continue;
                }

                $cartComplete[] = [
                    'product' => $productObject,
                    'quantity' => $quantity
                ];
            }
        }
        return $cartComplete;
    }
}
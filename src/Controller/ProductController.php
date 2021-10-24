<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Product;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/nos-produits", name="products")
     */
    public function index(Request $request): Response
    {
        // Récuperation du répository pour utiliser la fonction findAll()
        $products = $this->entityManager->getRepository(Product::class)->findAll();
        
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            //fonction pour la recherche
            $products = $this->entityManager->getRepository(Product::class)->findWithSearch($search);
            
        }

        //passage des éléments à twig pour l'affichage
        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/produit/{slug}", name="product")
     */
    public function show($slug): Response 
    {
        //Le slug en commentaire dit a symfony que la route peut varier dependament de l'article qu'on veut afficher
        $product = $this->entityManager->getRepository(Product::class)->findOneBy(['slug' => $slug]);
        
        if(!$product){
            return $this->redirectToRoute('products');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }
}

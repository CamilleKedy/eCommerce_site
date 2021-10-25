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
        
        // instanciation de la recherche du user
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            //fonction pour la recherche
            $products = $this->entityManager->getRepository(Product::class)->findWithSearch($search);
            
        } else {
            // Récuperation du répository pour utiliser la fonction findAll()
            // Si le formulaire n'est pas valide on montre tous nos produits
            $products = $this->entityManager->getRepository(Product::class)->findAll();
        
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
        $products = $this->entityManager->getRepository(Product::class)->findBy(['isBest' => 1]);

        if(!$product){
            return $this->redirectToRoute('products');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'products' => $products
        ]);
    }
}

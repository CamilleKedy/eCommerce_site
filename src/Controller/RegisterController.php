<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{

    private $entityManager;

    // injection de dépendance
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/inscription", name="register")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user); //injection de la classe RegisterType et passage des data à l'objet 

        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            
            $user = $form->getData();

            $password = $encoder->encodePassword($user, $user->getPassword());//Encodage du mot de passe de l'utilisateur
            //dd($password);
            $user->setPassword($password);//Réinjection du mot de passe encrypté dans l'objet $user

            
            $this->entityManager->persist($user); //figer la data
            $this->entityManager->flush(); //doctrine prend l'objet et l'enregistre en base de données

        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView()                  
        ]);
    }
}

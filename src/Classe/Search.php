<?php

namespace App\Classe;

use App\Entity\Category;

// La recherche du user est représentée sous forme d'objet 
// Cet objet sera utilisé pour créer le formulaire SearchType et le lier à la classe Search de symfony
class Search
{
    /**
     * @var string
     */
    public $string ='';

    /**
     * @var Category[]
     */
    public $categories = [];
}
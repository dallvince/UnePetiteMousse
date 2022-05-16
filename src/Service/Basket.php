<?php 

namespace App\Service;

use App\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Basket {

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function creation()
    {
        $basket = [];
        $basket["title"] = [];
        $basket["id"] = [];
        $basket["price"] = [];
        $basket["quantity"] = [];
        $basket["image"] = [];

        return $basket;
    }


    public function add($title, $id, $price, $quantity, $image)
    {

        $basket = $this->session->get("basket");

        if(empty($basket))
        {
            $basket = $this->creation();
            $this->session->set("basket", $basket);
        }

    // array_search() permettre de rechercher une valeur dans un tableau
    // Si elle existe, return la position
    // si non, return false

    // 2 arguments : la valeur et le tableau

    $position_search = array_search($id, $basket["id"]);

    if ($position_search !== false) 
    {
        $basket['quantity'][$position_search] += $quantity; 
    } 
    
    else 
    {
        $basket["title"][] = $title;
        $basket["id"][] = $id;
        $basket["price"][] = $price;
        $basket["quantity"][] = $quantity;
        $basket["image"][] = $image;
    }
    
    $this->session->set("basket", $basket);

    }

    public function montant() 
    {
        $montant = 0;

        $basket = $this->session->get("basket");

        $size = count($basket['id']);

        for($i = 0; $i < $size; $i++)
        {
            $montant += $basket['quantity'][$i] * $basket['price'][$i];
        }

        $montant = round($montant, 2);

        return $montant;
    }

    public function remove_all()
    {
        $basket = $this->creation();
        $this->session->set("basket", $basket);
    }

    public function remove($id)
    {
        $basket = $this->session->get("basket");

        $position_search = array_search($id, $basket["id"]);

        // array_splice() permet de supprimer un ou des éléments d'un tableau
        // 3 arguments
        // 1 - le tableau
        // 2 - la key
        // 3 - le nombre d'éléments à supprimer
        array_splice($basket['title'], $position_search, 1);
        array_splice($basket['id'], $position_search, 1);
        array_splice($basket['price'], $position_search, 1);
        array_splice($basket['quantity'], $position_search, 1);
        array_splice($basket['image'], $position_search, 1);

        $this->session->set("basket", $basket);
    }

    public function change($id, $what)
    {
        $basket = $this->session->get("basket");

        $position_search = array_search($id, $basket["id"]);

        if($what == 'plus')
        {
            $basket['quantity'][$position_search]++;
        }
        elseif($what == 'moins')
        {
            if($basket['quantity'][$position_search] == 1)
            {
                array_splice($basket['title'], $position_search, 1);
                array_splice($basket['id'], $position_search, 1);
                array_splice($basket['price'], $position_search, 1);
                array_splice($basket['quantity'], $position_search, 1);
                array_splice($basket['image'], $position_search, 1);

                $this->session->set("basket", $basket);
                return "delete";
            }
            else
            {
                $basket['quantity'][$position_search]--;
                $this->session->set("basket", $basket);
                return $basket['quantity'][$position_search];
            }
        }

        $this->session->set("basket", $basket);

        return $basket['quantity'][$position_search];
    }
}
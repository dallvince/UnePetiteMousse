<?php 

namespace App\Service;

use App\Entity\Commande;
use App\Entity\DetailsCommande;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Basket {

    private $session;
    private $repoProduct;
    private $manager;

    public function __construct(SessionInterface $session, ProductsRepository $repoProduct, EntityManagerInterface $manager)
    {
        $this->session = $session;
        $this->repoProduct = $repoProduct;
        $this->manager = $manager;
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
        // $product = $this->repoProduct->find($id);
        // $stockBdd = $product->getStocks()->getQuantity();
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
        $product = $this->repoProduct->find($id);
        $stockBdd = $product->getStocks()->getQuantity();
        $basket = $this->session->get("basket");

        $position_search = array_search($id, $basket["id"]);

        if($what == 'plus')
        {
            if($basket['quantity'][$position_search] < $stockBdd)
            {
                $basket['quantity'][$position_search]++;
                $this->session->set("basket", $basket);
                return $basket['quantity'][$position_search];
            }
            
        }
        elseif($what == 'moins')
        {
            if($basket['quantity'][$position_search] > 1)
            {
                $basket['quantity'][$position_search]--;
                $this->session->set("basket", $basket);
                return $basket['quantity'][$position_search];
            }
        }

    }

    public function verification() 
    {

        $basket = $this->session->get("basket");

        $size = count($basket['id']);

        for($i = 0; $i < $size; $i++)
        {
            $product = $this->repoProduct->find($basket['id'][$i]);
            $stockBdd = $product->getStocks()->getQuantity();

            if($stockBdd )
            {
                if($stockBdd < $basket['quantity'][$i])
                {
                    $basket['quantity'][$i] = $stockBdd;
                }

            }
            else
            {
                $basket['quantity'][$i] = 0;
            }
        }
        $this->session->set("basket", $basket);

    }

    public function paiement($user) 
    {
        $this->verification();
        $basket = $this->session->get("basket");

        $size = count($basket['id']);

        $access = false;

        for($i = 0; $i < $size; $i++)
        {
            if($basket['quantity'][$i])
            {
                $access = true;
            }
        }

        if($access)
        {

        $commande = new Commande();
        $commande->setUsers($user);

        $commande->setDateAt(new \DateTimeImmutable('now'));
        $commande->setMontant($this->montant());
        $commande->setEtat(0);

        $this->manager->persist($commande);
        $this->manager->flush();

        for($i = 0; $i < $size; $i++)
        {
            if($basket['quantity'][$i])
            {

                $product = $this->repoProduct->find($basket['id'][$i]);
                $stockBdd = $product->getStocks()->getQuantity();
    
                $detail = new DetailsCommande;
                $detail->setProduct($product);
                $detail->setCommande($commande);
                $detail->setQuantity($basket['quantity'][$i]);
                $detail->setPrice($basket['price'][$i]);

                $this->manager->persist($detail);
                $this->manager->flush();

                $newQuantity = $stockBdd - $basket['quantity'][$i];
                $product->getStocks()->setQuantity($newQuantity);

                $this->manager->persist($product);
                $this->manager->flush();

                $this->remove($basket['id'][$i]);
            }

        }

        }   
        
    }

}

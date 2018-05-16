<?php
namespace App\FlashSale\Repository;

use App\FlashSale\Entity\Offer;
use Cupcake\Database\Repository;

class OfferRepository extends  Repository {

    protected $entity = Offer::class;
    protected $table = "Offer";

    protected function paginationQuery(){
        return "SELECT o.id , o.price , p.price old_price
        FROM {$this->table} as o 
        LEFT JOIN Product as p ON p.id = o.product" ;
    }
}
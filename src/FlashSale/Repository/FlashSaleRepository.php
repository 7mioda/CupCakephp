<?php
namespace App\FlashSale\Repository;

use App\FlashSale\Entity\FlahSale;
use Cupcake\Database\Repository;

class FlashSaleRepository extends  Repository {


    protected $entity = FlahSale::class;

    protected $table = "FlashSale";

}
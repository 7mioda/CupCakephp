<?php
namespace App\Product\Repository;

use Cupcake\Database\Repository;

class ProductRepository extends  Repository {

    protected $entity = Product::class;
    protected $table = "Product";

    protected function paginationQuery(){
        return "SELECT p.id ,p.name,p.quantity, p.price , c.name as  category
        FROM {$this->table} as p 
        LEFT JOIN Category as c ON c.id = p.category" ;
    }
}
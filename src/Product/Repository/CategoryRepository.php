<?php
namespace App\Product\Repository;

use App\Product\Entity\Category;
use Cupcake\Database\Repository;

class CategoryRepository extends  Repository {

    protected $entity = Category::class;
    protected $table = "Category";

}
<?php
namespace App\FlashSale\Entity;

class FlahSale
{

    public $id;
    public $price;
    public $description;
    public $date;
    public $endDate;
    public $state;

    public function __construct()
    {
        if ($this->date){
            $this->date = new \DateTime($this->date);
        }
        if ($this->endDate){
            $this->endDate = new \DateTime($this->endDate);
        }
    }
}
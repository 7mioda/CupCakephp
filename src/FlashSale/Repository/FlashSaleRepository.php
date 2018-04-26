<?php
namespace App\FlashSale\Repository;

class FlashSaleRepository{

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll() : array
    {
       return $this->pdo
            ->query('SELECT * FROM FlashSale')
            ->fetchAll();
    }

    public function find(int $id)
    {
        $query = $this->pdo
            ->prepare('SELECT * from FlashSale WHERE id = ?');
        $query->execute([$id]);
        return $query->fetch();

    }

    public function update(int $id,array $params ): bool
    {
        $fieldQuery = join(',',array_map(function ($field){
            return"$field= :$field";
        },array_keys($params)));
        $params["id"] = $id;
        $statment = $this->pdo->prepare("UPDATE FlashSale SET $fieldQuery WHERE id=:id");
        return $statment->execute($params);
    }
    public function insert(array $params): bool {
        $fieldQuery = join(',',array_map(function ($field){
            return"$field= :$field";
        },array_keys($params)));
        $statment = $this->pdo->prepare("INSERT INTO FlashSale SET  $fieldQuery");
        return $statment->execute($params);
    }

    public function delete(int $id): bool {
        $statment = $this->pdo->prepare("DELETE FROM FlashSale WHERE  id = ?");
        return $statment->execute([$id]);
    }

}
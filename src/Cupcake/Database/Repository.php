<?php
namespace Cupcake\Database;

use App\FlashSale\Entity\FlahSale;
use Pagerfanta\Pagerfanta;

class Repository{

    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * Nom de la table en Base de Donnée
     *
     * @var string
     */
    protected $table;


    /**
     * Repository constructor.
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Récupperer tous les élements
     *
     * @return array
     *
     */
    public function findAll() : array
    {
        return $this->pdo
            ->query('SELECT * FROM FlashSale')
            ->fetchAll();
    }

    /**
     * Paginer des elements
     *
     * @param int $perPage
     * @param int $currentPage
     * @return Pagerfanta
     */
    public function findPaginated(int $perPage,int $currentPage): Pagerfanta
    {
        $query = new PaginatedQuery(
            $this->pdo,
            'SELECT * FROM FlashSale',
            'SELECT COUNT(id) FROM FlashSale',
            FlahSale::class
        );
        return(new Pagerfanta($query))
            ->setMaxPerPage($perPage)
            ->setCurrentPage($currentPage);
    }

    /**
     *
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id) : FlahSale
    {
        $query = $this->pdo
            ->prepare('SELECT * from FlashSale WHERE id = ?');
        $query->execute([$id]);
        $query->setFetchMode(\PDO::FETCH_CLASS,FlahSale::class);
        return $query->fetch();

    }

    /**
     *
     *
     * @param int $id
     * @param array $params
     * @return bool
     */
    public function update(int $id,array $params ): bool
    {
        $fieldQuery = join(',',array_map(function ($field){
            return"$field= :$field";
        },array_keys($params)));
        $params["id"] = $id;
        $statment = $this->pdo->prepare("UPDATE FlashSale SET $fieldQuery WHERE id=:id");
        return $statment->execute($params);
    }

    /**
     *
     *
     * @param array $params
     * @return bool
     */
    public function insert(array $params): bool {
        $fieldQuery = join(',',array_map(function ($field){
            return"$field= :$field";
        },array_keys($params)));
        $statment = $this->pdo->prepare("INSERT INTO FlashSale SET  $fieldQuery");
        return $statment->execute($params);
    }

    /**
     *
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool {
        $statment = $this->pdo->prepare("DELETE FROM FlashSale WHERE  id = ?");
        return $statment->execute([$id]);
    }

}
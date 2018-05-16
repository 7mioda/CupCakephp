<?php
namespace Cupcake\Database;

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
     * Entité a utiliser
     *
     * @var string
     */
    protected $entity;


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
            ->query("SELECT * FROM {$this->table}")
            ->fetchAll();
    }

    /**
     * Récupère une liste clef valeur des enregistrements
     */
    public function findList():array
    {
        $results = $this->pdo->query("SELECT id, name FROM {$this->table}")
            ->fetchAll(\PDO::FETCH_NUM);
        $list = [];
        foreach ($results as $result){
            $list[$result[0]] = $result[1];
        }
        return $list;
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
            $this->paginationQuery(),
            'SELECT COUNT(id) FROM '.$this->table,
            $this->entity
        );
        return(new Pagerfanta($query))
            ->setMaxPerPage($perPage)
            ->setCurrentPage($currentPage);
    }

    protected function paginationQuery(){
        return 'SELECT * FROM '.$this->table;
    }

    /**
     *Recuppere un element a partir de son ID
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id)
    {
        $query = $this->pdo
            ->prepare("SELECT * from {$this->table} WHERE id = ?");
        $query->execute([$id]);
        if($this->entity){
            $query->setFetchMode(\PDO::FETCH_CLASS,$this->entity);
        }
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
        $statment = $this->pdo->prepare("UPDATE {$this->table} SET $fieldQuery WHERE id=:id");
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
        $statment = $this->pdo->prepare("INSERT INTO {$this->table} SET  $fieldQuery");
        return $statment->execute($params);
    }

    /**
     *
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool {
        $statment = $this->pdo->prepare("DELETE FROM {$this->table} WHERE  id = ?");
        return $statment->execute([$id]);
    }

    /**
     * @return string
     */
    public function getEntity(): string
    {
        return $this->entity;
    }

    /**
     * @param string $entity
     */
    public function setEntity(string $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @return \PDO
     */
    public function getPdo(): \PDO
    {
        return $this->pdo;
    }

}
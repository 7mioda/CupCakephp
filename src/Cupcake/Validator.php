<?php
namespace Cupcake;

use Cupcake\Validator\ValidationError;

class Validator
{

    /**
     * @var array
     */
    private $params;

    /**
     * @var string[]
     */
    private $errors = [];

    public  function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * Vérifier que les champs sont present
     *
     * @param \string[] ...$keys
     * @return Validator
     */
    public function  required(string ...$keys) : self
    {
        foreach ($keys as $key ){
            $value = $this->getValue($key);
            if(is_null($value)|| empty($value)){
                $this->addError($key,'required');
            }
        }
        return $this;
    }


    /**
     * Verifie que le champ n'est pas vide
     *
     * @param \string[] ...$keys
     * @return $this
     */
    public function notEmpty(string ...$keys)
    {
        foreach ($keys as $key){
            $value = $this->getValue($key);
            if(is_null($value) || empty($value)){
                $this->addError($key,'empty');
            }
        }
        return $this;
    }

    /**
     * Vérifie si un champ a un length definit
     *
     * @param string $key
     * @param int|null $min
     * @param int|null|null $max
     * @return Validator
     */
    public function length(string $key,?int $min,?int $max = null): self
    {
        $value = $this->getValue($key);
        $lenght = mb_strlen($value);
        if(
            !is_null($min) &&
            !is_null($max) &&
            ($lenght < $min || $lenght > $max)
        ){
            $this->addError($key,'betweenLength',[$min,$max]);
            return $this;
        }
        if(
            !is_null($min) &&
            ($lenght < $min)
        ){
            $this->addError($key,'minLength',[$min]);
            return $this;
        }
        if(
            !is_null($max) &&
            ($lenght > $max)
        ){
            $this->addError($key,'maxLength',[$max]);

        }
        return $this;
    }

    public function dateTime(string $key,string $fomat="Y-m-d H:i:s"): self{
        $value = $this->getValue($key);
        $date = \DateTime::createFromFormat($fomat,$value);
        $errors = \DateTime::getLastErrors();
        if(($errors['error_count']>0) && ($errors['warning_count']>0) || $date === false){
            $this->addError($key,'datetime');
        }
        return $this;
    }
    /**
     * vérifie que l'element est un slug
     *
     * @param string $key
     * @return Validator
     */
    public function  slug(string $key): self
    {
        $value = $this->getValue($key);
        $pattern = '/^([a-z0-9]+-?)+$/';
        if((!is_null($value))&&(!preg_match($pattern,$value))){
            $this->addError($key,'slug');
        }
        return $this;
    }


    /**
     * Verifier le résultat de la validation
     *
     * @return bool
     */
    public function isValid():bool
    {
        return empty($this->errors);
    }
    /**
     *Récupère les erreurs
     *
     * @return ValidationError[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Ajoute une erreur
     *
     * @param string $key
     * @param string $rule
     * @param array $attributes
     */
    private function addError(string $key,string $rule, array $attributes = []): void
    {
        $this->errors[$key]= new ValidationError($key,$rule,$attributes);
    }

    /**
     * Récupère une valeur d'un clef
     *
     * @param string $key
     * @return mixed|null
     */
    private function getValue(string $key)
    {
        if(array_key_exists($key,$this->params)){
            return $this->params[$key];
        }
        return null;
    }

    public function exists(string $key,string $table, \PDO $pdo): self
    {
        $value = $this->getValue($key);
        $statment = $pdo->prepare("SELECT id FROM $table WHERE id = ?");
        $statment->execute([$value]);
        if($statment->fetchColumn() == false){
            $this->addError($key,'exists',[$table]);
        }
        return $this;
    }
}
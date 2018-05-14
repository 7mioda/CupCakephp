<?php
namespace Cupcake\Session;

interface SessionInterface{

    /**
     * Recupère une information en session
     *
     * @param String $key
     * @param mixed $default
     * @return mixed
     */
    public function get(String $key,$default = null);


    /**
     *
     * Ajouter une information en Session
     * @param string $key
     * @param $value
     */
    public function set(string $key,$value): void;

    /**
     * Supprime une clef en session
     * @param string $key
     */
    public function delete(string $key): void;
}
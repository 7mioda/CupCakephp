<?php
namespace Cupcake\Session;

class PHPSession implements SessionInterface{


    /**
     * Assure que la Session est démarrée
     */
    private function ensureStarted(){
        if(session_status()===PHP_SESSION_NONE){
            session_start();
        }
    }

    /**
     * Recupère une information en session
     *
     * @param String $key
     * @param mixed $default
     * @return mixed
     */
    public function get(String $key, $default = null)
    {
        $this->ensureStarted();
        if(array_key_exists($key,$_SESSION)){
            return $_SESSION[$key];
        }
        return $default;
    }

    /**
     *
     * Ajouter une information en Session
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value): void
    {
        $this->ensureStarted();
        $_SESSION[$key]=$value;
    }

    /**
     * Supprime une clef en session
     * @param string $key
     */
    public function delete(string $key): void
    {
        $this->ensureStarted();
        unset($_SESSION[$key]);
    }
}
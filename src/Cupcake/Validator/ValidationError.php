<?php
namespace Cupcake\Validator;

class ValidationError
{

    /**
     * @var string
     */
    private $key;
    /**
     * @var string
     */
    private $rule;

    private $messages = [
        'required' =>'le champ %s est requis',
        'empty' =>'le champ %s ne peut etre vide',
        'minLength' =>'le champ %s doit contenir plus de %d caractères',
        'maxLength' =>'le champ %s doit contenir moin de %d caractères',
        'betweenLength' =>'le champ %s doit contenir entre %d et %d caractères',
        'datetime' =>'le champ %s doit etre une date valide (%s)',
        'slug' =>'le champ %s n\'est pas un slug valide'
    ];
    /**
     * @var array
     */
    private $attributes;

    public function __construct(string $key, string $rule,array $attributes = [])
    {
        $this->key = $key;
        $this->rule = $rule;
        $this->attributes = $attributes;
    }

    public  function __toString()
    {
        $params = array_merge([$this->messages[$this->rule],$this->key],$this->attributes);
        return (string)call_user_func_array('sprintf',$params);
    }

}
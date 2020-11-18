<?php

/**
 * Verification des données
 */
class Validator{

    protected $dates;
    protected $errors = [];

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Retourne les erreurs s'il y en a
     *
     * @param array $data
     * @return array|bool
     */
    public function validates(array $data)
    {
        $this->errors = [];
        $this->data = $data;
        return $this->errors;
    }
    /**
     * Vérifie si les champs sont remplis
     *
     * @param string $field
     * @param string $method
     * @param [type] ...$parameters
     * @return void
     */
    public function validate(string $field, string $method, ...$parameters) : bool   {
        if (!isset($this->data[$field])) {
            $this->errors[$field] = "Le Champs $field n'est pas rempli";
            // 
            return false;
        } else {
            return call_user_func([$this, $method], $field, ...$parameters);
        }
    }
    /**
     *  Verifie que la donnée à bien la longueur minimale
     *
     * @param string $field
     * @param integer $length
     * @return boolean
     */
    public function minLength(string $field, int $length): bool {
        if(mb_strlen($this->data[$field]) <= $length){
            $length++;
            $this->errors[$field] = "Le champs $field doit avoir $length caractères minimun.";
            return false;
        }
        return true;
    }

    /**
     * Verifie qu'un chien est bien selectionné
     *
     * @param string $field
     * @return boolean
     */
    public function selectionChien(string $field ) : bool
    {
        if ($this->data[$field] == '-- Veuillez choisir un chien --'){
            $this->errors[$field] = "Aucun $field n'est selectionné";
            return false;
        }
        return true;
    }

    /**
     * Verifie que la date est au bon format
     *
     * @param string $field
     * @return boolean
     */
    public function dates(string $field): bool {
        if(\DateTime::createFromFormat('Y-m-d', $this->data[$field]) === false){
        $this->errors[$field] = "La date ne semble pas valide, renseignez une date de format AAAA-MM-JJ";
        return false;
        }
        return true;
    }

    /**
     * Verifie que l'heure est au bon format
     *
     * @param string $field
     * @return boolean
     */
    public function times(string $field): bool 
    {
        if (\DateTime::createFromFormat('H:i', $this->data[$field]) === false) {
            $this->errors[$field] = "Le temps indiqué ne semble pas valide, renseignez un temps de format HH:MM";
            return false;
        }
        return true;
    }

    /**
     * Verifie que l'heure de fin est bien après l'heure de debut
     *
     * @param string $startField
     * @param string $endField
     * @return void
     */
    public function beforeTime(string $startField, string $endField)
    {
        if ($this->times($startField) && $this->times($endField)) {
            $start = \DateTime::createFromFormat('H:i', $this->data[$startField]);
            $end = \DateTime::createFromFormat('H:i', $this->data[$endField]);
            if($start->getTimestamp() > $end->getTimestamp()) {
                $this->errors[$startField] = "L'heure de début doit être inférieur à l'heure d'arrivée";
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * Verifie que le telephone contient bien 10 chiffres
     *
     * @param string $field
     * @param integer $length
     * @return boolean
     */
    public function checkTel(string $field, int $length): bool
    {
        $this->data[$field] = filter_var($this->data[$field],FILTER_SANITIZE_NUMBER_INT);
        if (mb_strlen($this->data[$field]) != 10 ) {
            $this->errors[$field] = "Le champs $field doit contenir 10 chiffres.";
            return false;
        }
        return true;
    }

    /**
     * Verifie que le mail est au bon format
     *
     * @param string $field
     * @return boolean
     */
    public function isMail(string $field): bool
    {
        if (!(filter_var($this->data[$field], FILTER_VALIDATE_EMAIL))) {
            $this->errors[$field] = "Le champs $field doit être de la forme nom@domaine.com";
            return false;
        }
        return true;
        
    }

}
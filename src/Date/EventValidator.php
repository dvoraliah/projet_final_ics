<?php

/**
 * renvoie vers les fonction de validation de validator correspondantes
 */
class EventValidator extends Validator
{

    /**
     * valide les champs de l'ajout de rendez-vous
     *
     * @param array $data
     * @return array
     */
    public function validates(array $data) : array 
    {
        parent::validates($data);
        $this->validate('name', 'minLength', 2);
        $this->validate('dates', 'dates');
        $this->validate('start', 'times');
        $this->validate('end', 'times');
        $this->validate('start', 'beforeTime', 'end');
        $this->validate('chien', 'selectionChien');
        $this->validate('chien', 'minLength', 2);
        $this->validate('race', 'minLength', 2);
        return $this->errors;
    }

    /**
     * valides les champs de l'ajout client
     *
     * @param array $data
     * @return array
     */
    public function validatesClient(array $data) : array
    {
        parent::validates($data);
        $this->validate('nom', 'minLength', 2);
        $this->validate('prenom', 'minLength', 2);
        $this->validate('tel', 'checkTel', 10);
        $this->validate('mail', 'isMail');
        return $this->errors;
    }

    /**
     * Valide les champs de la recherche de client
     *
     * @param array $data
     * @return array
     */
    public function validatesChienClient(array $data) : array
    {
        parent::validates($data);
        $this->validate('nomClient', 'minLength', 2);
        return $this->errors;
    }

    /**
     * Valide les champs de l'ajout de chien
     *
     * @param array $data
     * @return array
     */
    public function validatesChien(array $data) : array
    {
        parent::validates($data);
        $this->validate('nomChien', 'minLength', 2);
        return $this->errors;
    }

    /**
     * valide les champs de l'edition
     *
     * @param array $data
     * @return array
     */
    public function validatesEdit(array $data) : array
    {
        parent::validates($data);
        $this->validate('dates', 'dates');
        $this->validate('start', 'times');
        $this->validate('end', 'times');
        $this->validate('start', 'beforeTime', 'end');
        return $this->errors;
    }

}
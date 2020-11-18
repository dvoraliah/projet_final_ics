<?php

/**
 * Undocumented function
 *
 * @param array $data
 * @return array|bool
 */
class EventValidator extends Validator
{

    public function validates(array $data) {
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

    public function validatesClient(array $data) {
        parent::validates($data);
        $this->validate('nom', 'minLength', 2);
        $this->validate('prenom', 'minLength', 2);
        $this->validate('tel', 'checkTel', 10);
        $this->validate('mail', 'isMail');
        return $this->errors;
    }

    public function validatesChienClient(array $data)
    {
        parent::validates($data);
        $this->validate('nomClient', 'minLength', 2);
        return $this->errors;
    }

    public function validatesChien(array $data)
    {
        parent::validates($data);
        $this->validate('nomChien', 'minLength', 2);
        return $this->errors;
    }

    public function validatesEdit(array $data)
    {
        parent::validates($data);
        $this->validate('dates', 'dates');
        $this->validate('start', 'times');
        $this->validate('end', 'times');
        $this->validate('start', 'beforeTime', 'end');
        return $this->errors;
    }

}
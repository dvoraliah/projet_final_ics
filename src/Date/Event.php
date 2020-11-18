<?php

/**
 * Getter et Setter correspond aux attributs et similaire à mes databases
 */
class Event {
    
    protected $id;
    protected $name;
    protected $description;
    protected $start;
    protected $end;
    protected $chien;
    protected $age;
    protected $race;
    protected $prenom;
    protected $adresse;
    protected $tel;
    protected $mail;
    protected $idClient;
    protected $idChien;

    /**
     * Get the value of id
     * @return integer 
     */ 
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Get the value of name
     * @return string
     */ 
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Get the value of description
     * @return string
     */ 
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * Get the value of start :
     * @return \DateTime
     */ 
    public function getStart() : \DateTime
    {
        return new \Datetime($this->start);
    }

    /**
     * Get the value of end
     * @return \DateTime
     */ 
    public function getEnd() : \DateTime
    {
        return new \Datetime($this->end);
    }

    

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */ 
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Set the value of start
     *
     * @return  self
     */ 
    public function setStart(string $start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Set the value of end
     *
     * @return  self
     */ 
    public function setEnd(string $end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of chien
     */ 
    public function getChien()
    {
        return $this->chien;
    }

    /**
     * Set the value of chien
     *
     * @return  self
     */ 
    public function setChien($chien)
    {
        $this->chien = $chien;

        return $this;
    }

    /**
     * Get the value of race
     */ 
    public function getRace()
    {
        return $this->race;
    }

    /**
     * Set the value of race
     *
     * @return  self
     */ 
    public function setRace($race)
    {
        $this->race = $race;

        return $this;
    }

    /**
     * Get the value of prenom
     */ 
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set the value of prenom
     *
     * @return  self
     */ 
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get the value of adresse
     */ 
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set the value of adresse
     *
     * @return  self
     */ 
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get the value of tel
     */ 
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set the value of tel
     *
     * @return  self
     */ 
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get the value of mail
     */ 
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set the value of mail
     *
     * @return  self
     */ 
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get the value of age
     */ 
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set the value of age
     *
     * @return  self
     */ 
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get the value of idClient
     */ 
    public function getIdClient()
    {
        return $this->idClient;
    }

    /**
     * Set the value of idClient
     *
     * @return  self
     */ 
    public function setIdClient($idClient)
    {
        $this->idClient = $idClient;

        return $this;
    }

    /**
     * Get the value of idChien
     */ 
    public function getIdChien()
    {
        return $this->idChien;
    }

    /**
     * Set the value of idChien
     *
     * @return  self
     */ 
    public function setIdChien($idChien)
    {
        $this->idChien = $idChien;

        return $this;
    }
}
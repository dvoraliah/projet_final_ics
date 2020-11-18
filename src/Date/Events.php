<?php

/**
 * Regroupe mes fonctions permettant les modifications d'attributs d'Event et les interactions avec ma bdd
 * 
 */
class Events {

    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    /**
     * Récupère les rendez-vous pris entre 2 dates dans ma database
     *
     * @param \DateTimeInterface $start
     * @param \DateTimeInterface $end
     * @return array
     */
    public function getEventsBetween (\DateTimeInterface $start, \DateTimeInterface $end): array {
        
        $sql = "SELECT * FROM rendez_vous WHERE start BETWEEN '{$start->format('Y-m-d 00:00:00')}' AND '{$end->format('Y-m-d 23:59:59')}' ORDER BY start ASC";
        $statement = $this->pdo->query($sql);
        $results = $statement->fetchAll();
        return $results;
    }

    /**
     * Récupère les rendez-vous pris entre 2 dates dans ma database indexé par jour
     *
     * @param \DateTime $start
     * @param \Datetime $end
     * @return array
     */
    public function
    getEventsBetweenByDay(\DateTimeInterface $start, \DateTimeInterface $end): array
    {
        $events = $this->getEventsBetween($start, $end);
        $days = [];
        foreach ($events as $event) {;
            $date = explode(' ', $event['start'])[0];
            if (!isset($days[$date])){
                $days[$date] = [$event];
            }
            else {
                $days[$date][] = $event;
            }
        }

        return $days;
    }

    /**
     * Recupere un evenement
     *
     * @param integer $id
     * @return array
     * @throws \Exception
     */
    public function find(int $id) : array
    {
        $result = $this->pdo->query("SELECT * FROM rendez_vous WHERE id = $id LIMIT 1")->fetch();
        if($result === false){
            throw new \Exception("Aucun résultat n'a été trouvé");
            
        }
        return $result;
    }

    /**
     * Recupere le nom d'un chien à partir de son id
     *
     * @param integer $idChien
     * @return array
     */
    public function findNomChien(int $idChien) : array
    {
        $result = $this->pdo->prepare("SELECT nom FROM chien WHERE id = ?");
        $result->execute([
            $idChien,
        ]);
        return $result->fetch();
        
    }

    /**
     * Recupere le nom d'un client à partir de son id
     *
     * @param integer $idClient
     * @return array
     */
    public function findNomClient(int $idClient) : array
    {
        $result = $this->pdo->prepare("SELECT nom FROM clients WHERE id = ?");
        $result->execute([
            $idClient,
        ]);
        return $result->fetch();
    }

    /**
     * Recupere toutes données contenues dans la table chien avec l'id du client
     *
     * @param integer $id
     * @return array
     */
    public function findChien(int $id): array
    {
        $result = $this->pdo->query("SELECT * FROM chien WHERE id_Client = $id");
        if ($result === false) {
            throw new \Exception("Aucun résultat n'a été trouvé");
        }
        return $result->fetchAll();
    }
    /**
     * Modifie les elements pour la rentrée en bdd d'un nouveau rendez-vous
     *
     * @param Event $event
     * @param array $data
     * @return void
     */
    public function hydrate(Event $event, array $data)
    {
        $event->setName($data['name']);
        $event->setDescription($data['description']);
        $event->setStart(\DateTime::createFromFormat('Y-m-d H:i', $data['dates'] . ' ' . $data['start'])->format('Y-m-d H:i:s'));
        $event->setEnd(\DateTime::createFromFormat('Y-m-d H:i', $data['dates'] . ' ' . $data['end'])->format('Y-m-d H:i:s'));
        $event->setChien($data['chien']);
        $event->setIdClient($data['idClient']);
        $event->setIdChien($data['idChien']);

        return $event;
    }


    /**
     * Modifie les elements pour la modification en bdd d'un rdv
     *
     * @param Event $event
     * @param array $data
     * @return void
     */
    public function hydrateEdit(Event $event, array $data)
    {
        $event->setDescription($data['description']);
        $event->setStart(\DateTime::createFromFormat('Y-m-d H:i', $data['dates'] . ' ' . $data['start'])->format('Y-m-d H:i:s'));
        $event->setEnd(\DateTime::createFromFormat('Y-m-d H:i', $data['dates'] . ' ' . $data['end'])->format('Y-m-d H:i:s'));

        return $event;
    }

    /**
     * Modifie les elements pour la rentrée en bdd d'un nouveau client
     *
     * @param Event $event
     * @param array $data
     * @return void
     */
    public function hydrateClient(Event $event, array $data)
    {
        $event->setName($data['nom']);
        $event->setPrenom($data['prenom']);
        $event->setAdresse($data['adresse']);
        $event->setTel($data['tel']);
        $event->setMail($data['mail']);

        return $event;
    }

    /**
     * Modifie les elements pour la recherche en bdd d'un client d'après son nom et son prenom
     *
     * @param Event $event
     * @param array $data
     * @return void
     */
    public function hydrateClientChien(Event $event, array $data)
    {
        $event->setName($data['nomClient']);
        $event->setPrenom($data['prenomClient']);
        return $event;
    }

    /**
     * Modifie les elements pour la rentrée en bdd d'un nouveau chien
     *
     * @param Event $event
     * @param array $data
     * @return void
     */
    public function hydrateChien(Event $event, array $data)
    {
        $event->setName($data['nomChien']);
        $event->setAge($data['age']);
        $event->setRace($data['race']);
        $event->setDescription($data['description']);
        $event->setIdClient($data['id']);
        return $event;
    }

    /**
     * Crée un nouveau rendez-vous en bdd dans rendez_vous
     *
     * @param Event $event
     * @return boolean
     */
    public function create(Event $event): bool
    {
        $statement = $this->pdo->prepare('INSERT INTO rendez_vous (start, end, id_client, id_chien, description) VALUES (?, ?, ?, ?, ?)');
        return $statement->execute([
            $event->getStart()->format('Y-m-d H:i:s'),
            $event->getEnd()->format('Y-m-d H:i:s'),
            $event->getIdClient(),
            $event->getIdChien(),
            $event->getDescription(),
        ]);
    }

    /**
     * Crée un nouveau client en bdd dans clients
     *
     * @param Event $event
     * @return boolean
     */
    public function createClient(Event $event): bool
    {
        $statement = $this->pdo->prepare('INSERT INTO clients (nom, prenom, adresse, tel, mail) VALUES (?, ?, ?, ?, ?)');
        return $statement->execute([
            $event->getName(),
            $event->getPrenom(),
            $event->getAdresse(),
            $event->getTel(),
            $event->getMail(),
        ]);
    }

    /**
     * Crée un nouveau chien en bdd dans chien
     *
     * @param Event $event
     * @return boolean
     */
    public function createChien(Event $event): bool
    {
        $statement = $this->pdo->prepare('INSERT INTO chien (nom, age, race, description, id_client) VALUES (?, ?, ?, ?, ?)');
        return $statement->execute([
            $event->getName(),
            $event->getAge(),
            $event->getRace(),
            $event->getDescription(),
            $event->getIdClient(),
        ]);
    }
    /**
     * Mets à jour un rendez en bdd dans rendez_vous
     *
     * @param Event $event
     * @return boolean
     */
    public function update(Event $event) : bool 
    {
        $statement = $this->pdo->prepare('UPDATE rendez_vous SET description = ?, start = ?, end= ? WHERE id = ?');
        return $statement->execute([
            $event->getDescription(),
            $event->getStart()->format('Y-m-d H:i:s'),
            $event->getEnd()->format('Y-m-d H:i:s'),
            $event->GetId()
        ]);
    }
/**
 * Supprime le rendezvous de la bdd dans rendez_vous
 *
 * @param Event $event
 * @return boolean
 */
    public function delete(Event $event) : bool
    {
        $statement = $this->pdo->prepare('DELETE rendez_vous FROM rendez_vous  WHERE id = ?');
        return $statement->execute([
            $event->GetId()
        ]);
    }

    /**
     * retourne le nom de ligne qui correspondent à la recherche d'un client dans clients
     *
     * @param Event $event
     * @return integer
     */
    public function search(Event $event) : int
    {
        if(!empty($event->getPrenom())){
            $statement = $this->pdo->prepare('SELECT * FROM clients WHERE nom LIKE ? AND prenom LIKE ?');
            $statement->execute([
                $event->GetName()."%",
                $event->getPrenom()."%",
            ]);
        }
        else
        {
            $statement = $this->pdo->prepare('SELECT * FROM clients WHERE nom LIKE ? ');
            $statement->execute([
                $event->GetName() . "%",
            ]);
        }

        return $statement->rowCount();
    }

    /**
     * Retourne les clients trouvés avec nom et prenom ou seulement le nom dans clients
     *
     * @param string $name
     * @param string $firstName
     * @return array
     */
    public function searchIdClient(string $name, string $firstName) : array
    {
        if (!empty($firstName)) {
            $statement = $this->pdo->prepare("SELECT * FROM clients WHERE nom LIKE :nom AND prenom LIKE :prenom ORDER BY nom ASC, prenom ASC");
            $statement->bindValue(":nom", $name . "%", PDO::PARAM_STR);
            $statement->bindValue(":prenom", $firstName . "%", PDO::PARAM_STR);
            $statement->execute();
            return $statement->fetchAll();
        }
        $statement = $this->pdo->prepare("SELECT * FROM clients WHERE nom LIKE :nom ORDER BY nom ASC, prenom ASC");
        $statement->bindValue(":nom", $name . "%", PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchAll();
    } 

    /**
     * Recherche le client d'après leur nom et prénom dans clients
     *
     * @param string $name
     * @param string $firstName
     * @return array
     */
    public function findIdClient(string $name, string $firstName) : array
    {
        $statement = $this->pdo->prepare("SELECT * FROM clients WHERE nom = ? AND prenom = ?");
        $statement->execute([
            $name,
            $firstName,
        ]);
        return $statement->fetch();
    }

    /**
     * recherche les données d'un chien d'après l'id du Client dans chien
     *
     * @param Event $event
     * @return integer
     */
    public function searchChien(Event $event) : int
    {
        $statement = $this->pdo->prepare('SELECT * FROM chien WHERE id_Client= ?');
        $statement->execute([
            $event->GetId(),
        ]);
        return $statement->rowCount();
    }

    /**
     * recherche l'id d'un chien d'après son nom et son id_client dans chien
     *
     * @param string $nomChien
     * @param string $id_Client
     * @return array
     */
    public function findIdChien(string $nomChien, string $id_Client) : array
    {
        
        $statement = $this->pdo->prepare('SELECT id FROM chien WHERE nom=? AND id_client= ?');
        $statement->execute([
            $nomChien,
            $id_Client
        ]);
        return $statement->fetch();

    }

    /**
     * recherche toutes les données d'un chien d'après son id_client dans chien
     *
     * @param Event $event
     * @return integer
     */
    public function showChien(Event $event) : int
    {
        $statement = $this->pdo->prepare('SELECT * FROM chien WHERE id_Client= ?');
        return $statement->execute([
            $event->GetId(),
        ]);
        
    }

    /**
     * Recherche toutes les données d'un chien d'après son id dans chien
     *
     * @param integer $idChien
     * @return array
     */
    public function showNameChien(int $idChien) : array
    {
        $statement = $this->pdo->prepare('SELECT * FROM chien WHERE id= ?');
        $statement->execute([
            $idChien,
        ]);
        return $statement->fetch();
    }

    /**
     * recherche la race d'un chien d'après son id dans chien
     *
     * @param integer $idChien
     * @return array
     */
    public function findRaceChien(int $idChien) : array
    {
        $statement = $this->pdo->prepare('SELECT race FROM chien WHERE id= ?');
        $statement->execute([
            $idChien,
        ]);
        return $statement->fetch();
    }
}
<?php

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

    public function findNomChien(int $idChien)
    {
        $result = $this->pdo->prepare("SELECT nom FROM chien WHERE id = ?");
        $result->execute([
            $idChien,
        ]);
        return $result->fetch();
        
    }

    public function findNomClient(int $idClient)
    {
        $result = $this->pdo->prepare("SELECT nom FROM clients WHERE id = ?");
        $result->execute([
            $idClient,
        ]);
        return $result->fetch();
    }

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

    public function hydrateEdit(Event $event, array $data)
    {
        $event->setDescription($data['description']);
        $event->setStart(\DateTime::createFromFormat('Y-m-d H:i', $data['dates'] . ' ' . $data['start'])->format('Y-m-d H:i:s'));
        $event->setEnd(\DateTime::createFromFormat('Y-m-d H:i', $data['dates'] . ' ' . $data['end'])->format('Y-m-d H:i:s'));

        return $event;
    }

    public function hydrateClient(Event $event, array $data)
    {
        $event->setName($data['nom']);
        $event->setPrenom($data['prenom']);
        $event->setAdresse($data['adresse']);
        $event->setTel($data['tel']);
        $event->setMail($data['mail']);

        return $event;
    }

    public function hydrateClientChien(Event $event, array $data)
    {
        $event->setName($data['nomClient']);
        $event->setPrenom($data['prenomClient']);
        return $event;
        // $event->setId($id);
    }

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
     * Crée un nouveau rendez-vous en bdd
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
     * Mets à jour un rendez en bdd
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
 * Supprime le rendezvous de la bdd
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

    public function search(Event $event)
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

    public function searchIdClient($name, $firstName)
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

    public function findIdClient($name, $firstName)
    {
        $statement = $this->pdo->prepare("SELECT * FROM clients WHERE nom = ? AND prenom = ?");
        $statement->execute([
            $name,
            $firstName,
        ]);
        return $statement->fetch();
    }

    public function searchChien(Event $event) : int
    {
        $statement = $this->pdo->prepare('SELECT * FROM chien WHERE id_Client= ?');
        $statement->execute([
            $event->GetId(),
        ]);
        return $statement->rowCount();
    }

    public function findIdChien($nomChien, $id_Client){
        
        $statement = $this->pdo->prepare('SELECT id FROM chien WHERE nom=? AND id_client= ?');
        $statement->execute([
            $nomChien,
            $id_Client
        ]);
        return $statement->fetch();

    }

    public function showChien(Event $event)
    {
        $statement = $this->pdo->prepare('SELECT * FROM chien WHERE id_Client= ?');
        return $statement->execute([
            $event->GetId(),
        ]);
        
    }

    public function showNameChien($idChien)
    {
        $statement = $this->pdo->prepare('SELECT * FROM chien WHERE id= ?');
        $statement->execute([
            $idChien,
        ]);
        return $statement->fetch();
    }


    public function findRaceChien($idChien)
    {
        $statement = $this->pdo->prepare('SELECT race FROM chien WHERE id= ?');
        $statement->execute([
            $idChien,
        ]);
        return $statement->fetch();
    }
}
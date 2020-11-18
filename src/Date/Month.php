<?php

class Month {

    public $day = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
    private $months = ['Janvier', 'Février','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','Décembre'];
    public $month;
    public $year;
    /**
     * Month constructor
     *
     * @param integer $month Le mois compris entre 1 et 12
     * @param integer $year L'année sur 4 chiffres
     * @throws \Exception
     */
    public function __construct(int $month = null, int $year = null)
    {
        if ($month === null || $month < 1 || $month > 12) {
            $month = intval(date('m'));
        }

        if ($year === null) {
            $year = intval(date('Y'));
        }



        $this->month = $month;

        $this->year = $year;
    }

    /**
     * Renvoie le premier jour du mois
     *
     * @return \DateTimeImmutable
     */
    public function getFisrtDay(): \DateTimeImmutable {
        return new \DateTimeImmutable("{$this->year}-{$this->month}-01");
    }
    /**
    * Retourne le mois en toute lettre
    *
    * @return string
    */ 
    public function toString(): string {
        return $this->months[$this->month - 1]. ' ' . $this->year;
    }

    /**
     * Renvoie le nombre de semaine du mois selectionné
     *
     * @return integer
     */
    public function getWeeks(): int {
        $start = $this->getFisrtDay();
        $end = $start->modify('+1 month -1 day');
        $startWeek = intval($start->format('W'));
        $endWeek = intval($end->format('W'));
        if($endWeek === 1)
        {
            $endWeek = intval($end->modify('-7 days')->format('W') + 1);
        }
        
        if($startWeek === 53)
        {
            $end = $start->modify('+1 month'); 
        }

        $weeks = intval($endWeek- $startWeek) + 1;

        if ($weeks < 0){
            $weeks = intval($end->format('W'));
        }
        return $weeks;
    }
    
    /**
     * Determine si le jour est dans le mois ou le mois précédent ou le mois suivant.
     *
     * @param \DateTimeImmutable $date
     * @return boolean
     */
    public function withinMonth(\DateTimeImmutable $date) : bool {
        return $this->getFisrtDay()->format('Y-m') === $date->format('Y-m');
    }

    /**
     * Renvoie le mois suivant
     *
     * @return Month
     */
    public function nextMonth(): Month 
    {
        $month = $this->month + 1;
        $year = $this->year;
        if ($month > 12) {
            $month = 1;
            $year+= 1;
        }
        return new Month($month, $year);
    }

    /**
     * Renvoie le mois précédent
     *
     * @return Month
     */
    public function previousMonth(): Month
    {
        $month = $this->month - 1;
        $year = $this->year;
        if ($month < 1) {
            $month = 12;
            $year -= 1;
        }
        return new Month($month, $year);
    }
}
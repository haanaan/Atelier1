<?php
namespace charlymatloc\core\application\entities;


class Reservation {
    private string $id;
    private string $datedebut;
    private string $datefin;
    private string $montanttotal;
    private string $statut;

    public function getId():string
    {
        return $this->id;
    }
    public function getDateDebut():string
    {
        return $this->datedebut;
    }
    public function getDateFin():string
    {
        return $this->datefin;
    }
    public function getMontantTotal():string
    {
        return $this->montanttotal;
    }
    public function getStatut():string
    {
        return $this->statut;
    }
}
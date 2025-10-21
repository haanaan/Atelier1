<?php
namespace charlymatloc\core\application\entities;


class Reservation {
    private string $id;
    private string $datedebut;
    private string $datefin;
    private string $montanttotal;
    private string $statut;
    private Utilisateurs $utilisateur;
    private array $outils;

    public function getUtilisateur():Utilisateurs
    {
        return $this->utilisateur;
    }
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
    public function getOutils(): array {
        return $this->outils;
    }
}
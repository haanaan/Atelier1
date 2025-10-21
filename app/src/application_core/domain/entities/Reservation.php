<?php
namespace charlymatloc\core\domain\entities;

class Reservation {
    private string $id;
    private string $datedebut;
    private string $datefin;
    private string $montanttotal;
    private string $statut;
    private Utilisateurs $utilisateur;
    private string $outils;

    public function __construct(
        string $id,
        string $datedebut,
        string $datefin,
        string $montanttotal,
        string $statut,
        Utilisateurs $utilisateur,
        string $outils
    ) {
        $this->id = $id;
        $this->datedebut = $datedebut;
        $this->datefin = $datefin;
        $this->montanttotal = $montanttotal;
        $this->statut = $statut;
        $this->utilisateur = $utilisateur;
        $this->outils = $outils;
    }

    public function getId(): string { return $this->id; }
    public function getDateDebut(): string { return $this->datedebut; }
    public function getDateFin(): string { return $this->datefin; }
    public function getMontantTotal(): string { return $this->montanttotal; }
    public function getStatut(): string { return $this->statut; }
    public function getUtilisateur(): Utilisateurs { return $this->utilisateur; }
    public function getOutil(): string { return $this->outils; }
}

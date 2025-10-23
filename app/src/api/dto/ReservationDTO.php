<?php
namespace charlymatloc\api\dto;

use charlymatloc\core\domain\entities\Reservation;

class ReservationDto
{
    public string $id;
    public string $datedebut;
    public string $datefin;
    public float $montanttotal;
    public string $statut;
    public string $utilisateur_nom;
    public array $outils;

    public function __construct(Reservation $reservation)
    {
        $this->id = $reservation->getId();
        $this->datedebut = $reservation->getDateDebut();
        $this->datefin = $reservation->getDateFin();
        $this->montanttotal = (float)$reservation->getMontantTotal();
        $this->statut = $reservation->getStatut();

        $utilisateur = $reservation->getUtilisateur();
        $this->utilisateur_nom = trim($utilisateur->getNom() . ' ' . $utilisateur->getPrenom());

        $this->outils = $reservation->getOutil();
    }
}

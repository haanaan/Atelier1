<?php
namespace charlymatloc\api\dto;

class ReservationDto
{
    public string $id;
    public string $datedebut;
    public string $datefin;
    public float $montanttotal;
    public string $statut;
    public string $utilisateur_id;

    public function __construct(
        string $id,
        string $datedebut,
        string $datefin,
        float $montanttotal,
        string $statut,
        string $utilisateur_id
    ) {
        $this->id = $id;
        $this->datedebut = $datedebut;
        $this->datefin = $datefin;
        $this->montanttotal = $montanttotal;
        $this->statut = $statut;
        $this->utilisateur_id = $utilisateur_id;
    }
}

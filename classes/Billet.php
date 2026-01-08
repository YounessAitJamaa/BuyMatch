<?php

class Billet
{
    private int $id;
    private string $numeroPlace;
    private string $qrcode;
    private MatchSportif $match;
    private Categorie $categorie;
    private Utilisateur $acheteur;
    private string $dateAchat;

    public function __construct(
        int $id, 
        string $numeroPlace, 
        string $qrcode, 
        MatchSportif $match, 
        Categorie $categorie, 
        Utilisateur $acheteur,
        string $dateAchat
    ) {
        $this->id = $id;
        $this->numeroPlace = $numeroPlace;
        $this->qrcode = $qrcode;
        $this->match = $match;
        $this->categorie = $categorie;
        $this->acheteur = $acheteur;
        $this->dateAchat = $dateAchat;
    }


    public function getId(): int {
        return $this->id;
    }

    public function getNumeroPlace(): string {
        return $this->numeroPlace;
    }

    public function getQrcode(): string {
        return $this->qrcode;
    }

    public function getMatch(): MatchSportif {
        return $this->match;
    }

    public function getCategorie(): Categorie {
        return $this->categorie;
    }

    public function getUtilisateur(): Utilisateur {
        return $this->acheteur;
    }

    public function getDateAchat(): string {
        return $this->dateAchat;
    }

}
?>
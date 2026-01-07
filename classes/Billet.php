<?php

    class Billet
    {
        private int $id;
        private string $numeroPlace;
        private string $qrcode;
        private MatchSportif $match;
        private Categorie $categorie;
        private Utilisateur $acheteur;

        public function __construct(
            int $id, 
            string $numeroPlace, 
            string $qrcode, 
            MatchSportif $match, 
            Categorie $categorie, 
            Utilisateur $acheteur
        ) {
            $this->id = $id;
            $this->numeroPlace = $numeroPlace;
            $this->qrcode = $qrcode;
            $this->match = $match;
            $this->categorie = $categorie;
            $this->acheteur = $acheteur;
        }
    }



?>
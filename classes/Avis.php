<?php

    class Avis 
    {
        private int $id;
        private int $note;
        private ?string $commentaire;
        private string $date;
        private MatchSportif $match;
        private Utilisateur $acheteur;

        public function __construct(
            int $id,
            int $note,
            ?string $commentaire,
            string $date,
            MatchSportif $match,
            Utilisateur $acheteur
        ) {
            $this->id = $id;
            $this->note = $note;
            $this->commentaire = $commentaire;
            $this->date = $date;
            $this->match = $match;
            $this->acheteur = $acheteur;
        }

        
    }



?>
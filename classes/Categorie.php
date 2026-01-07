<?php

    class Categorie
    {
        private int $id;
        private string $nom;
        private float $prix;
        private int $nbPlaces;
        private ?MatchSportif $match;

        public function __construct(
            int $id,
            string $nom,
            float $prix,
            int $nbPlaces,
            ?MatchSportif $match = null
        ) {
            $this->id = $id;
            $this->nom = $nom;
            $this->prix = $prix;
            $this->nbPlaces = $nbPlaces;
            $this->match = $match;
        }

        public function getId(): int {
            return $this->id;
        }
        public function getNom(): string {
            return $this->nom;
        }
        public function getPrix(): float {
            return $this->prix;
        }
        public function getNbPlaces(): int {
            return $this->nbPlaces;
        }
        public function getMatch(): MatchSportif {
            return $this->match;
        }
    }




?>
<?php

    class MatchSportif
    {
        private int $id;
        private Equipe $equipe1;
        private Equipe $equipe2;
        private string $dateHeure;
        private string $lieu;
        private int $duree;
        private Utilisateur $organisateur;
        private string $statut;
        private array $categories;

        public function __construct(
            int $id,
            Equipe $equipe1,
            Equipe $equipe2,
            string $dateHeure,
            string $lieu,
            int $duree,
            Utilisateur $organisateur,
            string $statut = 'en attente',
            array $categories = []
        ) 
        {
            $this->id = $id;
            $this->equipe1 = $equipe1;
            $this->equipe2 = $equipe2;
            $this->dateHeure = $dateHeure;
            $this->lieu = $lieu;
            $this->duree = $duree;
            $this->organisateur = $organisateur;
            $this->statut = $statut;
            $this->categories = $categories;
        }

        public function getId(): int { 
            return $this->id; 
        }

        public function getEquipe1(): Equipe { 
            return $this->equipe1; 
        }

        public function getEquipe2(): Equipe { 
            return $this->equipe2; 
        }

        public function getDateHeure(): string { 
            return $this->dateHeure; 
        }

        public function getLieu(): string { 
            return $this->lieu; 
        }

        public function getDuree(): int {
            return $this->duree;
        }

        public function getOrganisateur(): Utilisateur {
            return $this->organisateur;
        }

        public function getStatut(): string { 
            return $this->statut; 
        }

        public function getCategories():array {
            return $this->categories;
        }

        public function getTotalPlaces(): int {
            
            $total = 0;


            foreach($this->categories as $categorie) {
                $total += $categorie->getNbPlaces();
            }

            return $total;
    
        }

        public function setId($id) {
            $this->id = (int)$id;
        }

        public function setOrganisateur(Utilisateur $organisateur) {
            $this->organisateur = $organisateur;
        }

        // --- Details ---
        public function setDateHeure($date_heure) {
            $this->dateHeure = $date_heure;
        }

        public function setLieu($lieu) {
            $this->lieu = $lieu;
        }

        public function setDuree($duree) {
            $this->duree = (int)$duree;
        }

        public function setStatut($statut) {
            $this->statut = $statut;
        }


        public function setEquipe1($equipe) {
            $this->equipe1 = $equipe;
        }

        public function setEquipe2($equipe) {
            $this->equipe2 = $equipe;
        }

        public function setCategories($categories) {
            $this->categories = is_array($categories) ? $categories : [];
        }

    }



?>
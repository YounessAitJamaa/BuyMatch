<?php

    class Utilisateur 
    {
        protected int $id;
        protected string $nom;
        protected string $email;
        protected string $motDePasse;
        protected bool $actif;
        protected Role $role;   

        public function __construct(
            int $id,
            string $nom,
            string $email,
            string $motDePasse,
            bool $actif,
            Role $role

        ){
            $this->id = $id;
            $this->nom = $nom;
            $this->email = $email;
            $this->motDePasse = $motDePasse;
            $this->actif = $actif;
            $this->role = $role;
        }


        public function getId(): int {
            return $this->id;
        }

        public function getNom(): string {
            return $this->nom;
        }

        public function getEmail(): string {
            return $this->email;
        }

        public function getMotDePass(): string {
            return $this->motDePasse;
        }
        
        public function isActif(): bool {
            return $this->actif;
        }

        public function getRole(): Role {
            return $this->role;
        }

    }   





?>
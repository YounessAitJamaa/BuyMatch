<?php

    class User {
        protected int $id;
        protected string $nom;
        protected string $email;
        protected string $motDePasse;
        protected bool $actif;
        protected int $roleId;   

        public function __construct(
            int $id,
            string $nom,
            string $email,
            string $motDePasse,
            bool $actif,
            int $roleId

        ){
            $this->id = $id;
            $this->nom = $nom;
            $this->email = $email;
            $this->motDePasse = $motDePasse;
            $this->actif = $actif;
            $this->roleId = $roleId;
        }


    }





?>
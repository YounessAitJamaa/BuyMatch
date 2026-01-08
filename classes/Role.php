<?php

    class Role 
    {
        private int $id;
        private string $nom_role;


        public function __construct(int $id, string $nom_role) {
            $this->id = $id;
            $this->nom_role = $nom_role;
        }

        public function getId(): int {
            return $this->id;
        }
        
        public function getNomRole(): string {
            return $this->nom_role;
        }
    }


?>
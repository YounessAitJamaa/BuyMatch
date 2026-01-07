<?php

    require_once '../../config/Database.php';

    class OrganisateurRepository 
    {
        private PDO $db;

        public function __construct() 
        {
            $this->db = Database::getInstance();    
        }


        public function getTotalBillets(int $organisateurId): int 
        {
            $stmt = $this->db->prepare("SELECT COUNT(b.id) AS total_billets
                                        FROM billet b
                                        JOIN match_sportif m ON b.match_id = m.id
                                        WHERE m.organisateur_id = :id");
            $stmt->execute(['id' => $organisateurId]);
            return $stmt->fetchColumn();
        }

        public function getTotalRevenus(int $organisateurId): float
        {
            $stmt = $this->db->prepare("SELECT SUM(c.prix) AS total_revenus
                                        FROM billet b
                                        JOIN categorie c ON b.categorie_id = c.id
                                        JOIN match_sportif m ON b.match_id = m.id
                                        WHERE m.organisateur_id = :id; ");
            $stmt->execute(['id' => $organisateurId]);
            return (float) $stmt->fetchColumn();
        }

        public function countEvenetsByStatus(int $organisateurId, string $status): int 
        {
            $stmt = $this->db->prepare('SELECT COUNT(*)
                                        FROM match_sportif
                                        WHERE organisateur_id = :id
                                        AND statut = :statut');
            $stmt->execute([
                'id' => $organisateurId,
                'statut' => $status
            ]);

            return $stmt->fetchColumn();
        }
    }




?>
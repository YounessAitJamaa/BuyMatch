<?php


    require_once '../../config/Database.php';
    require_once '../../classes/Equipe.php';

    class EquipeRepository
    {
        private PDO $db;

        public function __construct()
        {
            $this->db = Database::getInstance();
        }

        public function findById(int $equipeId): ?Equipe
        {
            $stmt = $this->db->prepare('SELECT id, nom, logo FROM equipe WHERE id = :id');
            $stmt->execute(['id' => $equipeId]);
            $row =  $stmt->fetch(PDO::FETCH_ASSOC);

            if(!$row) {
                return null;
            }

            return new Equipe(
                $row['id'],
                $row['nom'],
                $row['logo']
            );
        }

        public function findOrCreateByName(string $nom, string $logo): int 
        {

            $stmt = $this->db->prepare("SELECT id FROM equipe WHERE nom = :nom");
            $stmt->execute(['nom' => $nom]);
            $row = $stmt->fetch();

            if ($row) {
                return (int)$row['id'];
            }

            $stmt = $this->db->prepare("INSERT INTO equipe (nom, logo) VALUES (:nom, :logo)");
            $stmt->execute([
                'nom' => $nom,
                'logo' => $logo
            ]);

            return (int)$this->db->lastInsertId();
        }

    }




?>
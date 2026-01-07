<?php 

    require_once '../../config/Database.php';   
    require_once '../../classes/Role.php';

    class RoleRepository
    {
        private PDO $db;

        public function __construct()
        {
            $this->db = Database::getInstance();
        }

        public function findById(int $id): ?Role {
            $stmt = $this->db->prepare("SELECT * FROM role WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $data = $stmt->fetch();

            if (!$data) {
                return null;
            }

            return new Role($data['id'], $data['nom_role']);
        }


    }



?>
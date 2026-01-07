<?php

    require_once '../../config/Database.php';
    require_once '../../classes/Utilisateur.php';
    require_once 'RoleRepository.php';

    class UtilisateurRepository
    {
        private PDO $db;
        private RoleRepository $roleRepo;

        public function __construct()
        {
            $this->db = Database::getInstance();
            $this->roleRepo = new RoleRepository();
        }

        public function findByEmail(string $email): ?Utilisateur {
            $stmt = $this->db->prepare("SELECT * FROM utilisateur WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $data = $stmt->fetch();

            if(!$data) {
                return null;
            }

            $role = $this->roleRepo->findById($data['role_id']);

            return new Utilisateur(
                $data['id'],
                $data['nom'],
                $data['email'],
                $data['mot_de_passe'],
                (bool)$data['actif'],
                $role
            );
        }


        public function create(
            string $nom, 
            string $email,
            string $motDePasse,
            int $roleId
        ): void {
            $stmt = $this->db->prepare("INSERT INTO utilisateur (nom, email, mot_de_passe, actif, role_id)
                                        VALUES (?,?,?,1,?)"
            );

            $stmt->execute([
                $nom, 
                $email, 
                password_hash($motDePasse, PASSWORD_DEFAULT), 
                $roleId
            ]);
        }

        public function findById(int $id): Utilisateur{
            $stmt = $this->db->prepare("SELECT * FROM utilisateur WHERE id = :id");
            $stmt->execute(['id' => $id]);

            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                throw new RuntimeException("Utilisateur not found (id = $id)");
            }

            $role = $this->roleRepo->findById($data['role_id']);

            return new Utilisateur(
                (int) $data['id'],
                $data['nom'],
                $data['email'],
                $data['mot_de_passe'],
                (bool) $data['actif'],
                $role
            );
        }




    }




?>
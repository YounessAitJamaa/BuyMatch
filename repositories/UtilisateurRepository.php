<?php

    require_once __DIR__ . '../../config/Database.php';
    require_once __DIR__ . '../../classes/Utilisateur.php';
    require_once __DIR__ . '/RoleRepository.php';

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
                $data['photo'],
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
                $data['photo'],
                $data['mot_de_passe'],
                (bool) $data['actif'],
                $role
            );
        }

        public function updateProfileUrl(int $id, string $nom, string $photo): bool 
        {
            $stmt = $this->db->prepare("UPDATE utilisateur SET nom = :nom, photo = :photo WHERE id = :id");
            return $stmt->execute([
                'nom' => $nom,
                'photo' => $photo,
                'id' => $id
            ]);
        }

        public function getUsers() {
            $stmt = $this->db->prepare('SELECT COUNT(*) FROM utilisateur');
            $stmt->execute();
            return $stmt->fetchColumn();
        }

        public function getTotalOrganisateurs(){
            $stmt = $this->db->prepare('SELECT COUNT(*) FROM utilisateur WHERE role_id = :id');
            $stmt->execute(['id' => 2]);
            return $stmt->fetchColumn();
        }

        public function getTotalMatches() {
            $stmt = $this->db->prepare('SELECT COUNT(*) FROM match_sportif');
            $stmt->execute();
            return $stmt->fetchColumn();
        }

        public function getTotalEnAttentMatches() {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM match_sportif WHERE statut = 'en_attente'");
            $stmt->execute();
            return $stmt->fetchColumn();
        }

        public function updateRole(int $userId, int $roleId) {
            $stmt = $this->db->prepare("UPDATE utilisateur SET role_id = :roleId WHERE id = :userId");
            $stmt->execute([
                'roleId' => $roleId,
                'userId' => $userId
            ]);
        }

        public function findAll(): array {
            $stmt = $this->db->prepare("
                SELECT u.*, r.id as role_id, r.nom_role 
                FROM utilisateur u
                JOIN role r ON u.role_id = r.id
                ORDER BY u.nom ASC
            ");
            $stmt->execute();

            $users = [];
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $role = new Role($row['role_id'], $row['nom_role']);

                $users[] = new Utilisateur(
                    (int)$row['id'],
                    $row['nom'],
                    $row['email'],
                    $row['photo'],
                    $row['mot_de_passe'],
                    (bool)$row['actif'],
                    $role
                );
            }

            return $users;
        }

        public function updateStatus(int $id, int $newActif) {
            $stmt = $this->db->prepare("UPDATE utilisateur SET actif = :actif WHERE id = :id");
            $stmt->execute([
                'actif' => $newActif,
                'id' => $id
            ]);
        }

    }




?>
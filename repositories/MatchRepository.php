<?php

    require_once '../../config/Database.php';
    require_once '../../classes/MatchSportif.php';
    require_once '../../repositories/EquipeRepository.php';
    require_once '../../repositories/UtilisateurRepository.php';
    require_once '../../repositories/CategorieRepository.php';
    
    class MatchRepository
    {
        private PDO $db;
        private EquipeRepository $equipeRepo;
        private UtilisateurRepository $utilisateurRepo;
        private CategorieRepository $categorieRepo;

        public function __construct() {
            $this->db = Database::getInstance();
            $this->equipeRepo = new EquipeRepository();
            $this->utilisateurRepo = new UtilisateurRepository();
            $this->categorieRepo = new CategorieRepository();
        }

        public function findByOrganisateur(int $organisateurId): array
        {
            $stmt = $this->db->prepare(
                "SELECT * FROM match_sportif 
                WHERE organisateur_id = :id
                ORDER BY date_heure DESC"
            );
            $stmt->execute(['id' => $organisateurId]);

            $matches = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $equipe1 = $this->equipeRepo->findById($row['equipe1_id']);
                $equipe2 = $this->equipeRepo->findById($row['equipe2_id']);
                $organisateur = $this->utilisateurRepo->findById($row['organisateur_id']);
                $categories = $this->categorieRepo->findByMatchId($row['id']);

                if(!$organisateur) {
                    continue;
                }

                $match = new MatchSportif(
                    $row['id'],
                    $equipe1,
                    $equipe2,
                    $row['date_heure'],
                    $row['lieu'],
                    $row['duree'],
                    $organisateur,
                    $row['statut'],
                    $categories
                );

                $matches[] = $match;
            }

            return $matches;
        }

        public function createFromForm(array $data): bool
        {
            try {
                
                $this->db->beginTransaction();

                // 1. Insertion du match sportif
                $stmt = $this->db->prepare("
                    INSERT INTO match_sportif (date_heure, lieu, duree, statut, organisateur_id, equipe1_id, equipe2_id)
                    VALUES (:date_heure, :lieu, :duree, 'en_attente', :organisateur_id, :e1, :e2)
                ");

                $stmt->execute([
                    'date_heure'      => $data['date_heure'],
                    'lieu'            => $data['lieu'],
                    'duree'           => $data['duree'],
                    'organisateur_id' => $data['organisateur_id'],
                    'e1'              => $data['equipe1_id'],
                    'e2'              => $data['equipe2_id']
                ]);

                $matchId = (int)$this->db->lastInsertId();

                foreach ($data['categories'] as $cat) {
                    $this->categorieRepo->create(
                        $cat['nom'],
                        $cat['prix'],
                        $cat['nb_places'],
                        $matchId    
                    );
                }

                $this->db->commit();
                return true;

            } catch (Exception $e) {
                $this->db->rollBack();
                die("Error SQL " . $e->getMessage());
            }
        }

        public function findById($id) {
            $stmt = $this->db->prepare("SELECT * FROM match_sportif WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) return null;

            $equipe1 = $this->equipeRepo->findById($row['equipe1_id']);
            $equipe2 = $this->equipeRepo->findById($row['equipe2_id']);
            $organisateur = $this->utilisateurRepo->findById($row['organisateur_id']);
            $categories = $this->categorieRepo->findByMatchId($row['id']);

            if (!$organisateur || !$equipe1 || !$equipe2) {
                return null;
            }

            return new MatchSportif(
                (int)$row['id'],
                $equipe1,
                $equipe2,
                $row['date_heure'],
                $row['lieu'],
                (int)$row['duree'],
                $organisateur,
                $row['statut'],
                $categories
            );
        }

        public function getMatches(): array {
            
            $sql = "SELECT m.*, 
                    e1.nom as e1_nom, e1.logo as e1_logo, 
                    e2.nom as e2_nom, e2.logo as e2_logo,
                    u.nom as u_nom, u.email as u_email, u.actif as u_actif
                    FROM match_sportif m
                    JOIN equipe e1 ON m.equipe1_id = e1.id
                    JOIN equipe e2 ON m.equipe2_id = e2.id
                    JOIN utilisateur u ON m.organisateur_id = u.id
                    ORDER BY m.date_heure DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            
            $matches = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                
                $equipe1 = new Equipe($row['equipe1_id'], $row['e1_nom'], $row['e1_logo']);
                $equipe2 = new Equipe($row['equipe2_id'], $row['e2_nom'], $row['e2_logo']);
                
                $role = new Role(2, 'Organisateur'); 
                $organisateur = new Utilisateur(
                    $row['organisateur_id'], $row['u_nom'], $row['u_email'], 
                    "", "", (bool)$row['u_actif'], $role
                );

                $matches[] = new MatchSportif(
                    (int)$row['id'],
                    $equipe1,
                    $equipe2,
                    $row['date_heure'],
                    $row['lieu'],
                    (int)$row['duree'],
                    $organisateur,
                    $row['statut']
                );
            }
            return $matches;
        }
        
        public function updateStatus(int $id, string $status): bool {
            $stmt = $this->db->prepare("UPDATE match_sportif SET statut = :status WHERE id = :id");
            return $stmt->execute([
                'status' => $status,
                'id' => $id
            ]);
        }

        public function findAllValide(): array {
            // Ajout de la virgule manquante après e2_logo
            $sql = "SELECT m.*, 
                    e1.nom as e1_nom, e1.logo as e1_logo, 
                    e2.nom as e2_nom, e2.logo as e2_logo,
                    u.nom as u_nom, u.email as u_email, u.actif as u_actif
                    FROM match_sportif m
                    JOIN utilisateur u ON m.organisateur_id = u.id
                    JOIN equipe e1 ON m.equipe1_id = e1.id
                    JOIN equipe e2 ON m.equipe2_id = e2.id
                    WHERE m.statut = 'valide' 
                    AND m.date_heure > NOW() 
                    ORDER BY m.date_heure ASC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            
            $matches = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                
                $equipe1 = new Equipe((int)$row['equipe1_id'], $row['e1_nom'], $row['e1_logo']);
                $equipe2 = new Equipe((int)$row['equipe2_id'], $row['e2_nom'], $row['e2_logo']);
                
                // Rôle statique pour l'organisateur (évite une requête SQL inutile)
                $role = new Role(2, 'Organisateur'); 

                $organisateur = new Utilisateur(
                    (int)$row['organisateur_id'], 
                    $row['u_nom'], 
                    $row['u_email'], 
                    "", // photo vide
                    "", // mot de passe vide (sécurité)
                    (bool)$row['u_actif'], 
                    $role
                );
                
                $matches[] = new MatchSportif(
                    (int)$row['id'],
                    $equipe1,
                    $equipe2,
                    $row['date_heure'],
                    $row['lieu'],
                    (int)$row['duree'],
                    $organisateur,
                    $row['statut']
                );
            }
            return $matches;
        }
    }



?>
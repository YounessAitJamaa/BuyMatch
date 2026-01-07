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
    }



?>
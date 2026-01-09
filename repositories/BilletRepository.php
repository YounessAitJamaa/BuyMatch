<?php

require_once '../../config/Database.php';
require_once '../../classes/Billet.php';
require_once 'UtilisateurRepository.php';
require_once 'CategorieRepository.php';

class BilletRepository {
    private PDO $db;
    private UtilisateurRepository $userRepo;
    private CategorieRepository $catRepo;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->userRepo = new UtilisateurRepository();
        $this->catRepo = new CategorieRepository();
    }

    public function findByMatch(int $matchId): array {
        
        $stmt = $this->db->prepare(
            "SELECT * FROM billet WHERE match_id = :match_id ORDER BY date_achat DESC"
        );
        $stmt->execute(['match_id' => $matchId]);

        $billets = [];
        
        $matchRepo = new MatchRepository();
        $matchObj = $matchRepo->findById($matchId);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $user = $this->userRepo->findById($row['acheteur_id']);
            $categorie = $this->catRepo->findById($row['categorie_id']);

            if ($user && $categorie && $matchObj) {
                $billets[] = new Billet(
                    (int)$row['id'],
                    $row['numero_place'] ?? 'N/A',
                    $row['qrcode'] ?? '',
                    $matchObj,
                    $categorie,
                    $user,
                    $row['date_achat']
                );
            }
        }

        return $billets;
    }

   
    public function getRevenueByMatch(int $matchId): float {
        $stmt = $this->db->prepare("
            SELECT SUM(c.prix) as total_revenue 
            FROM billet b
            JOIN categorie c ON b.categorie_id = c.id
            WHERE b.match_id = :match_id
        ");
        $stmt->execute(['match_id' => $matchId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return (float)($result['total_revenue'] ?? 0);
    }


    public function create(array $data) {
      
        $sql = "INSERT INTO billet (numero_place, qr_code, match_id, categorie_id, acheteur_id, date_achat) 
                VALUES (:numero_place, :qr_code, :match_id, :categorie_id, :acheteur_id, :date_achat)";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':numero_place'  => $data['numero_place'],
            ':qr_code'       => $data['qr_code'],
            ':match_id'      => $data['match_id'],
            ':categorie_id'  => $data['categorie_id'],
            ':acheteur_id'   => $data['acheteur_id'],
            ':date_achat'    => $data['date_achat'] 
        ]);
    }

    public function findByUser(int $userId): array {
        $sql = "SELECT b.*, 
                m.date_heure, m.lieu,
                e1.nom as e1_nom, e1.logo as e1_logo,
                e2.nom as e2_nom, e2.logo as e2_logo,
                c.nom as cat_nom
                FROM billet b
                JOIN match_sportif m ON b.match_id = m.id
                JOIN equipe e1 ON m.equipe1_id = e1.id
                JOIN equipe e2 ON m.equipe2_id = e2.id
                JOIN categorie c ON b.categorie_id = c.id
                WHERE b.acheteur_id = :userId
                ORDER BY m.date_heure ASC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
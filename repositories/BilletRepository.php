<?php

require_once '../../config/Database.php';
require_once '../../classes/Billet.php';

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
            $user = $this->userRepo->findById($row['utilisateur_id']);
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
}
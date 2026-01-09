<?php

    require_once __DIR__ . '../../config/Database.php';
    require_once __DIR__ . '../../classes/Categorie.php';



    class CategorieRepository
    {
        private PDO $db;

        public function __construct(){
            $this->db = Database::getInstance();
        }

        public function findByMatchId(int $matchId): array {
            $stmt = $this->db->prepare('SELECT * FROM categorie WHERE match_id = :id');
            $stmt->execute(['id' => $matchId]);

            $categories = [];

            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $categories[] = new Categorie(
                    $row['id'],
                    $row['nom'],
                    (float) $row['prix'],
                    (int) $row['nb_places'],
                    null
                );
            }
            
            return $categories;
        }

        public function create(string $nom, float $prix, int $nbPlaces, int $matchId): bool {
            $stmt = $this->db->prepare('INSERT INTO categorie (nom, prix, nb_places, match_id) VALUES (:nom, :prix, :nb, :match_id)');

            return $stmt->execute([
                'nom'       => $nom, 
                'prix'      => $prix,
                'nb'        => $nbPlaces,
                'match_id'   => $matchId
            ]);
        }

        public function findById(int $categoryId): ?Categorie {
            
            $stmt = $this->db->prepare("SELECT * FROM categorie WHERE id = :id");
            
            $stmt->execute(['id' => $categoryId]);
            
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                return null;
            }

            return new Categorie(
                (int)$row['id'],
                $row['nom'],
                (float)$row['prix'],
                (int)$row['nb_places'],
                null
            );
        }
        public function findAll(int $matchId): array {
                $sql = "SELECT * FROM categorie WHERE match_id = :matchId ORDER BY prix DESC ";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(['matchId' => $matchId]);

                $categories = [];
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $categories[] = new Categorie(
                        (int)$row['id'],
                        $row['nom'],
                        (float)$row['prix'],
                        (int)($row['nb_places'] ?? 100),
                        null
                    );
                }
                return $categories;
        }
    }


?>
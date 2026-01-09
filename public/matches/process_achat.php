<?php

    session_start();

    require_once '../../repositories/BilletRepository.php';
    require_once '../../repositories/MatchRepository.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Acheteur') {
        header('Location: ../auth/login.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $matchId = (int)$_POST['match_id'];
        $userId = (int)$_SESSION['user_id'];
        $categorieId = isset($_POST['categorie_id']) ? (int)$_POST['categorie_id'] : null; 
        $quantity = (int)$_POST['quantite'];

        if($quantity < 1 || $quantity > 4) $quantity = 1; 

        if(!$categorieId) {
            header('Location: acheter.php?id=' . $matchId . '&error=missing_category');
            exit();
        }
        
        try {

            $billetRepo = new BilletRepository();
            $dateAchat = date('Y-m-d H:i:s');
            
            for($i = 0; $i < $quantity; $i++) {
                $numeroPlace = "R" . rand(1, 20) . "-P" . rand(1, 100);
                $qrCode = bin2hex(random_bytes(10));

                $success = $billetRepo->create([
                    'numero_place' => $numeroPlace,
                    'qr_code'      => $qrCode,
                    'match_id'     => $matchId,
                    'categorie_id' => $categorieId,
                    'acheteur_id'  => $userId,
                    'date_achat'   => $dateAchat
                ]);
            }


            if ($success) {
                header('Location: ../matches/index.php?order=success');
                exit;
            } else {
                throw new Exception("Erreur lors de l'enregistrement du billet.");
            }

        } catch (Exception $e) {
            // header('Location: show.php?id=' . $matchId . '&error=purchase_failed');
            // exit;
            echo $e->getMessage();
        }
    } else {
        header('Location: index.php');
        exit;
    }
?>
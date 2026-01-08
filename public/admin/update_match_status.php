<?php

    session_start();
    require_once '../../repositories/MatchRepository.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Administrateur') {
        header('Location: ../login.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $matchId = isset($_POST['id']) ? (int)$_POST['id'] : null;
        $newStatus = $_POST['statut'];

        if ($matchId && in_array($newStatus, ['valide', 'refuse'])) {
            $matchRepo = new MatchRepository();
            $matchRepo->updateStatus($matchId, $newStatus);
        }
    }

    header('Location: matches.php');
    exit;
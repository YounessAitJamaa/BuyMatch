<?php
    session_start();
    require_once '../../repositories/UtilisateurRepository.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Administrateur') {
        exit('Unauthorized');
    }

    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    $UserRepo = new UtilisateurRepository();

    if ($id && $id !== $_SESSION['user_id']) {
        $user = $UserRepo->findById($id);
        if ($user) {
            $newStatus = $user->isActif() ? 0 : 1;
            $UserRepo->updateStatus($id, $newStatus);
        }
    }

    header('Location: gerer_users.php');
    exit;

?>
<?php
    session_start();

    require_once __DIR__ . '../../config/Database.php';
    require_once __DIR__ . '../../repositories/MatchRepository.php';

    $url = isset($_GET['url']) ? $_GET['url'] : 'home';

    if ($url === 'home') {
        $matchRepo = new MatchRepository();
        $matches = $matchRepo->findAllValide(); 
        include 'matches/index.php';
    } 
    elseif ($url === 'login') {
        include 'auth/login.php';
    } 
    else {
        http_response_code(404);
        include '404.php';
    }
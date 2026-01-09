<?php
    session_start();

    require_once '../../repositories/MatchRepository.php';
    require_once '../../repositories/BilletRepository.php';

    if(!isset($_SESSION['user_id'])) {
        header('Location: ../login.php');
        exit;
    }

    $matchId = isset($_GET['id']) ? (int)$_GET['id'] : null;
    
    if(!$matchId) {
        header('Location: mes_evenements.php');
        exit;
    }

    $matchRepo = new MatchRepository();
    $billetRepo = new BilletRepository();
    
    $match = $matchRepo->findById($matchId);
    
    if(!$match) {
        header('Location: mes_evenements.php');
        exit;
    }

    $billets = $billetRepo->findByMatch($matchId);
    $totalBilletVendus = count($billets);
    $totalPlaces = array_sum(array_map(function($cat) { return $cat->getNbPlaces(); }, $match->getCategories()));
    $tauxOccupation = ($totalPlaces > 0) ? ($totalBilletVendus / $totalPlaces) * 100 : 0;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $match->getEquipe1()->getNom() ?> vs <?= $match->getEquipe2()->getNom() ?> - BuyMatch</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-screen">

    <!-- Top Navigation -->
    <nav class="fixed top-0 w-full z-50 bg-slate-800/50 backdrop-blur border-b border-slate-700">
        <div class="px-6 py-4 flex justify-between items-center max-w-full">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-emerald-600 rounded-lg flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-black tracking-tight text-white">BuyMatch</h1>
                    <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold">Event Management</p>
                </div>
            </div>
            
            <div class="flex items-center gap-5">
                <button class="p-2 text-slate-400 hover:text-emerald-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </button>
                <div class="h-8 w-px bg-slate-700"></div>
                <div class="relative group">
                    <button class="flex items-center gap-3 cursor-pointer p-1.5 rounded-xl hover:bg-slate-700/50 transition-all">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold text-white">Alex Morgan</p>
                            <p class="text-[10px] text-slate-400 font-medium">Organisateur</p>
                        </div>
                        <div class="w-9 h-9 rounded-full border-2 border-slate-600 p-0.5 group-hover:border-emerald-500/50 transition-all">
                            <img src="/placeholder.svg?height=40&width=40" class="w-full h-full rounded-full object-cover" alt="Profile">
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex pt-20">
        <!-- Sidebar -->
        <aside class="w-72 min-h-screen sticky top-20 hidden lg:block border-r border-slate-700 bg-slate-800/30 backdrop-blur p-8 space-y-10">
            <div>
                <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-6">Navigation Principale</h3>
                <nav class="space-y-1">
                    <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-700/30 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h7" stroke-width="2" stroke-linecap="round"/></svg>
                        <span>Vue d'ensemble</span>
                    </a>
                    <a href="mes_evenements.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-700/30 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" stroke-width="2" stroke-linecap="round"/></svg>
                        <span>Mes Événements</span>
                    </a>
                    <a href="create_match.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-700/30 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke-width="2" stroke-linecap="round"/></svg>
                        <span>Créer Événement</span>
                    </a>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8 md:p-12 max-w-7xl mx-auto w-full">
            <a href="mes_evenements.php" class="text-slate-400 hover:text-emerald-500 font-bold flex items-center gap-2 mb-8 transition-colors">
                ← Retour aux événements
            </a>

            <!-- Match Header Card -->
            <div class="bg-slate-800/50 backdrop-blur rounded-2xl border border-slate-700/50 p-8 mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div class="flex-1">
                        <!-- Modernized team logo display with glassmorphism and enhanced styling -->
                        <div class="flex items-center justify-start gap-8 mb-8">
                            <div class="relative group">
                                <div class="absolute inset-0 bg-emerald-600/20 rounded-2xl blur-xl opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
                                <div class="relative w-24 h-24 rounded-2xl bg-gradient-to-br from-slate-700/80 to-slate-800/80 backdrop-blur border-2 border-emerald-500/30 group-hover:border-emerald-500/60 overflow-hidden flex items-center justify-center shadow-2xl shadow-emerald-500/10 group-hover:shadow-emerald-500/30 transition-all duration-300 transform group-hover:scale-105">
                                    <img src="../uploads/<?= $match->getEquipe1()->getLogo() ?>" alt="<?= $match->getEquipe1()->getNom() ?>" class="w-20 h-20 object-contain p-2">
                                </div>
                            </div>

                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 rounded-full bg-emerald-600/20 border-2 border-emerald-500/50 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
                                </div>
                                <span class="text-xs text-slate-400 font-bold mt-2">VS</span>
                            </div>

                            <div class="relative group">
                                <div class="absolute inset-0 bg-emerald-600/20 rounded-2xl blur-xl opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
                                <div class="relative w-24 h-24 rounded-2xl bg-gradient-to-br from-slate-700/80 to-slate-800/80 backdrop-blur border-2 border-emerald-500/30 group-hover:border-emerald-500/60 overflow-hidden flex items-center justify-center shadow-2xl shadow-emerald-500/10 group-hover:shadow-emerald-500/30 transition-all duration-300 transform group-hover:scale-105">
                                    
                                    <img src="../uploads/<?= $match->getEquipe2()->getLogo() ?>" alt="<?= $match->getEquipe2()->getNom() ?>" class="w-20 h-20 object-contain p-2">
                                </div>
                            </div>
                        </div>
                        
                        <h1 class="text-4xl font-black text-white tracking-tight mb-2">
                            <?= $match->getEquipe1()->getNom() ?> <span class="text-emerald-500">vs</span> <?= $match->getEquipe2()->getNom() ?>
                        </h1>
                        <div class="flex flex-wrap gap-4 text-slate-400 text-sm mt-4">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"/></svg>
                                <span><?= date('d/m/Y à H:i', strtotime($match->getDateHeure())) ?></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L9.9 13.95a.75.75 0 01-1.06-1.06l5.05-5.05a5.5 5.5 0 10-7.78 7.78c1.367 1.368 3.15 1.813 4.612 1.418l3.054 3.054a9 9 0 01-12.728-1.728 8.963 8.963 0 011.732-12.728z" clip-rule="evenodd"/></svg>
                                <span><?= $match->getLieu() ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-3">
                        <?php if($match->getStatut() === 'valide'): ?>
                            <span class="inline-flex px-4 py-2 rounded-lg text-sm font-black uppercase tracking-widest bg-emerald-600/20 text-emerald-400 border border-emerald-500/30 text-center">
                                Validé
                            </span>
                        <?php elseif($match->getStatut() === 'refuse'): ?>
                            <span class="inline-flex px-4 py-2 rounded-lg text-sm font-black uppercase tracking-widest bg-red-600/20 text-red-400 border border-red-500/30 text-center">
                                Refusé
                            </span>
                        <?php else: ?>
                            <span class="inline-flex px-4 py-2 rounded-lg text-sm font-black uppercase tracking-widest bg-amber-600/20 text-amber-400 border border-amber-500/30 text-center">
                                En attente
                            </span>
                        <?php endif; ?>
                        <button class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg transition-all text-sm shadow-lg shadow-emerald-500/20">
                            Modifier
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-slate-800/50 backdrop-blur rounded-xl border border-slate-700/50 p-6">
                    <p class="text-slate-400 text-xs font-black uppercase tracking-widest mb-2">Billets Vendus</p>
                    <p class="text-3xl font-black text-white"><?= $totalBilletVendus ?></p>
                    <p class="text-slate-500 text-xs mt-2">sur <?= $totalPlaces ?> places</p>
                </div>
                <div class="bg-slate-800/50 backdrop-blur rounded-xl border border-slate-700/50 p-6">
                    <p class="text-slate-400 text-xs font-black uppercase tracking-widest mb-2">Taux d'Occupation</p>
                    <p class="text-3xl font-black text-white"><?= round($tauxOccupation) ?>%</p>
                    <div class="w-full bg-slate-700/50 rounded-full h-2 mt-3">
                        <div class="bg-emerald-600 h-2 rounded-full transition-all" style="width: <?= $tauxOccupation ?>%"></div>
                    </div>
                </div>
                <div class="bg-slate-800/50 backdrop-blur rounded-xl border border-slate-700/50 p-6">
                    <p class="text-slate-400 text-xs font-black uppercase tracking-widest mb-2">Durée</p>
                    <p class="text-3xl font-black text-white"><?= $match->getDuree() ?></p>
                    <p class="text-slate-500 text-xs mt-2">minutes</p>
                </div>
            </div>

                        <!-- Recent Sales -->
            <div class="bg-slate-800/50 backdrop-blur rounded-xl border border-slate-700/50 overflow-hidden mb-8">
                <div class="px-8 py-5 border-b border-slate-700 flex justify-between items-center bg-slate-900/30">
                    <h3 class="text-sm font-black text-white uppercase tracking-widest">Dernières Ventes</h3>
                    <span class="text-xs font-bold text-emerald-500 bg-emerald-500/10 px-3 py-1 rounded-full border border-emerald-500/20">
                        Total: <?= count($billets) ?> billets
                    </span>
                </div>
                <div class="overflow-x-auto max-h-[370px] overflow-y-auto custom-scrollbar">
                    <table class="w-full text-left">
                        <thead class="bg-slate-900/50 border-b border-slate-700">
                            <tr>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Client</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Catégorie</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Date d'Achat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700 ">
                            <?php if(empty($billets)): ?>
                                <tr>
                                    <td colspan="3" class="px-8 py-10 text-center text-slate-500 text-sm italic">Aucun billet vendu pour le moment.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($billets as $billet): ?>
                                    <tr class="hover:bg-slate-700/20 transition-colors">
                                        <td class="px-8 py-4">
                                            <p class="font-bold text-white"><?= htmlspecialchars($billet->getUtilisateur()->getNom()) ?></p>
                                            <p class="text-[10px] text-slate-500 font-mono"><?= htmlspecialchars($billet->getUtilisateur()->getEmail()) ?></p>
                                        </td>
                                        <td class="px-8 py-4 text-center">
                                            <span class="px-2 py-1 bg-slate-900/50 border border-slate-600 rounded text-[10px] text-slate-300 font-bold">
                                                <?= htmlspecialchars($billet->getCategorie()->getNom()) ?>
                                            </span>
                                        </td>
                                        <td class="px-8 py-4 text-right">
                                            <p class="text-xs text-slate-400 font-medium"><?= date('d/m/Y H:i', strtotime($billet->getDateAchat())) ?></p>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Ticket Categories -->
            <div class="bg-slate-800/50 backdrop-blur rounded-xl border border-slate-700/50 overflow-hidden">
                <div class="p-6 border-b border-slate-700">
                    <h2 class="text-lg font-black text-white">Catégories de Billets</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-6">
                    <?php foreach($match->getCategories() as $cat): ?>
                        <div class="bg-slate-700/30 rounded-lg p-4 border border-slate-600">
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-2"><?= $cat->getNom() ?></p>
                            <p class="text-2xl font-black text-emerald-400 mb-3"><?= $cat->getPrix() ?>€</p>
                            <p class="text-slate-500 text-xs"><?= $cat->getNbPlaces() ?> places disponibles</p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>

</body>
</html>






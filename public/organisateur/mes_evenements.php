<?php

    session_start();

    require_once '../../repositories/UtilisateurRepository.php';
    require_once '../../repositories/OrganisateurRepository.php';
    require_once '../../repositories/MatchRepository.php';

    if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Organisateur') {
        header('Location: ../login.php');
        exit;
    }

    $dashboardRepo = new OrganisateurRepository();
    $matchRepo = new MatchRepository();
    $organisateurId = $_SESSION['user_id'];

    $totalBillets = $dashboardRepo->getTotalBillets($organisateurId);
    $totalRevenus = $dashboardRepo->getTotalRevenus($organisateurId);
    $eventsActif = $dashboardRepo->countEvenetsByStatus($organisateurId, 'valide');
    $eventsAttente = $dashboardRepo->countEvenetsByStatus($organisateurId, 'en_attente');
    $AllvalideTickets = $dashboardRepo->getAllTicketsValide($organisateurId);

    $objectif = ($AllvalideTickets > 0) ? ($totalBillets / $AllvalideTickets) * 100 : 0;
    $displayPercentage = min(round($objectif), 100);

    $matches =  $matchRepo->findByOrganisateur($organisateurId);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes événements - BuyMatch</title>
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
                <!-- Profile Dropdown -->
                <div class="relative group">
                    <button class="flex items-center gap-3 cursor-pointer p-1.5 rounded-xl hover:bg-slate-700/50 transition-all">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold text-white"><?= $_SESSION['user_name'] ?></p>
                            <p class="text-[10px] text-slate-400 font-medium">Organisateur</p>
                        </div>
                        <div class="w-9 h-9 rounded-full border-2 border-slate-600 p-0.5 group-hover:border-emerald-500/50 transition-all">
                            <img src="/placeholder.svg?height=40&width=40" class="w-full h-full rounded-full object-cover" alt="Profile">
                        </div>
                    </button>
                    
                    <div class="absolute right-0 mt-2 w-56 bg-slate-800/80 backdrop-blur rounded-2xl shadow-2xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform translate-y-2 group-hover:translate-y-0 border border-slate-700">
                        <div class="px-4 py-3 border-b border-slate-700 mb-2">
                            <p class="text-xs font-black uppercase text-slate-400 tracking-widest">Compte</p>
                        </div>
                        <a href="profile.php" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-slate-300 hover:text-emerald-500 hover:bg-slate-700/50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2"/></svg>
                            Mon Profil
                        </a>
                        <div class="h-px bg-slate-700 my-2"></div>
                        <a href="../logout.php" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-red-500 hover:bg-red-500/10 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-width="2"/></svg>
                            Déconnexion
                        </a>
                    </div>
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
                    <a href="mes_evenements.php" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-emerald-600/20 text-emerald-500 font-bold transition-all border border-emerald-500/30 hover:shadow-lg hover:shadow-emerald-500/10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" stroke-width="2" stroke-linecap="round"/></svg>
                        <span>Mes Événements</span>
                    </a>
                    <a href="create_match.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-700/30 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke-width="2" stroke-linecap="round"/></svg>
                        <span>Créer Événement</span>
                    </a>
                </nav>
            </div>

            <div class="pt-6 border-t border-slate-700">
                <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-6">Compte</h3>
                <nav class="space-y-1">
                    <a href="profile.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-700/30 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round"/></svg>
                        <span>Profil Utilisateur</span>
                    </a>
                    <a href="../logout.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-700/30 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-width="2" stroke-linecap="round"/></svg>
                        <span>Se déconnecter</span>
                    </a>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8 md:p-12 max-w-7xl mx-auto w-full">
            <!-- Updated header with modern styling and spacing -->
            <header class="mb-12 flex justify-between items-end gap-6">
                <div>
                    <h2 class="text-4xl font-black text-white tracking-tight">Mes événements</h2>
                    <p class="text-slate-400 font-medium mt-2">Tous les matchs que vous avez organisés</p>
                </div>
                <a href="create-event.html">
                    <button class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-lg transition-all text-sm shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/40">+ Créer un match</button>
                </a>
            </header>

            <!-- Updated table with dark theme, glassmorphism, and modern styling -->
            <div class="bg-slate-800/50 backdrop-blur rounded-xl border border-slate-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-900/50 border-b border-slate-700">
                            <tr>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Match</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Date</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Lieu</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Statut</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            <?php if($matches): ?>
                                <?php foreach($matches as $match): ?>
                                    <tr class="hover:bg-slate-700/20 transition-colors group">
                                        <td class="px-8 py-5 font-bold text-white">
                                            <?= $match->getEquipe1()->getNom() ?> <span class="text-slate-400 font-normal">vs</span> <?= $match->getEquipe2()->getNom() ?>
                                        </td>
                                        <td class="px-8 py-5 text-slate-400"><?= date('d/m/Y , H:i', strtotime($match->getDateHeure())) ?></td>
                                        <td class="px-8 py-5 text-slate-400"><?= $match->getLieu() ?></td>
                                        <td class="px-8 py-5 text-center">
                                            <?php if($match->getStatut() === 'valide'): ?>
                                                <span class="inline-flex px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-widest bg-emerald-600/20 text-emerald-400 border border-emerald-500/30">
                                                    Validé
                                                </span>
                                            <?php elseif($match->getStatut() === 'refuse'): ?>
                                                <span class="inline-flex px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-widest bg-red-50 text-red-600 border border-red-100">
                                                    Refusé
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-widest bg-amber-50 text-amber-600 border border-amber-100">
                                                    En attente                                                  
                                                </span>                                        
                                            <?php endif; ?> 
                                        </td>
                                        <td class="px-8 py-5 text-right space-x-3">
                                            <a href="match_details.php?id=<?= $match->getId() ?>" class="text-emerald-500 hover:text-emerald-400 font-bold text-sm transition-colors">Voir détails</a>
                                            <a href="#" class="text-emerald-500 hover:text-emerald-400 font-bold text-sm transition-colors">Modifier</a>
                                            <a href="#" class="text-red-500 hover:text-red-400 font-bold text-sm transition-colors">Annuler</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else:  ?>
                                <div class="bg-slate-800/50 backdrop-blur rounded-xl border border-slate-700 p-12 text-center">
                                    <svg class="w-16 h-16 text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                    <p class="text-slate-400 font-medium text-lg">Aucun événement créé pour le moment</p>
                                    <p class="text-slate-500 text-sm mt-2">Commencez par créer votre premier match.</p>
                                </div>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Empty state message for no events -->
            <!-- Uncomment to show when no events exist -->
            <!-- <div class="bg-slate-800/50 backdrop-blur rounded-xl border border-slate-700 p-12 text-center">
                <svg class="w-16 h-16 text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <p class="text-slate-400 font-medium text-lg">Aucun événement créé pour le moment</p>
                <p class="text-slate-500 text-sm mt-2">Commencez par créer votre premier match.</p>
            </div> -->
        </main>
    </div>

</body>
</html>
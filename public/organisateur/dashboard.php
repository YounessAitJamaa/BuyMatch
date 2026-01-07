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

    $matches =  $matchRepo->findByOrganisateur($organisateurId);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BuyMatch - Tableau de bord</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @theme {
            --color-primary: #10b981; /* Emerald 500 */
            --color-primary-dark: #059669; /* Emerald 600 */
            --color-secondary: #0f172a; /* Slate 900 */
            --color-accent: #334155; /* Slate 700 */
        }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-900 antialiased">

    <!-- Header / Navbar -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-40">
        <div class="max-w-full px-8 py-4 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center shadow-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-tight text-secondary">BuyMatch</h1>
                    <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold">Event Management</p>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <button class="p-2 text-slate-400 hover:text-primary transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </button>
                <div class="h-8 w-px bg-slate-200"></div>   
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-secondary leading-none"><?= htmlspecialchars($_SESSION['user_name']) ?></p>
                        <p class="text-[10px] text-slate-400 font-medium">Organisateur</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden">
                        <img src="/placeholder.svg?height=40&width=40" alt="User avatar" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-72 bg-secondary min-h-screen fixed left-0 top-20 text-white">
            <div class="p-8 space-y-10">
                <div>
                    <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-6">Navigation principale</h3>
                    <nav class="space-y-1">
                        <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-primary/10 text-primary font-bold transition-all border border-primary/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                            <span>Vue d'ensemble</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span>Mes Événements</span>
                        </a>
                        <a href="create_match.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span>Créer Événement</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span>Rapports Financiers</span>
                        </a>
                    </nav>
                </div>

                <div>
                    <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-6">Compte</h3>
                    <nav class="space-y-1">
                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Profil Utilisateur</span>
                        </a>
                        <a href="../logout.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Se déconnecter</span>
                        </a>
                    </nav>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-72 p-10 max-w-7xl mx-auto">
            <header class="mb-12 flex justify-between items-end">
                <div>
                    <h2 class="text-3xl font-black text-secondary tracking-tight">Tableau de bord</h2>
                    <p class="text-slate-500 font-medium mt-1">Gérez vos événements et surveillez la performance commerciale.</p>
                </div>
                <div class="flex gap-3">
                    <a href="create_match.php">
                        <button class="px-5 py-2.5 bg-primary text-white font-bold rounded-lg hover:bg-primary-dark transition-all text-sm shadow-md shadow-primary/20 cursor-pointer">+ Nouvel Événement</button>
                    </a>
                </div>
            </header>

            <!-- Key Metrics (Simplified Stats Grid) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">Revenus Totaux</p>
                    <h3 class="text-3xl font-black text-secondary leading-none">€<?= number_format($totalRevenus, 2) ?></h3>
                    <div class="mt-4 flex items-center gap-1.5 text-xs font-bold text-primary">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M7 14l5-5 5 5H7z"/></svg>
                        <span>12.5% vs mois dernier</span>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">Billets Vendus</p>
                    <h3 class="text-3xl font-black text-secondary leading-none"><?= $totalBillets ?></h3>
                    <div class="mt-4 flex items-center gap-1.5 text-xs font-bold text-slate-500">
                        <span class="w-1.5 h-1.5 bg-slate-300 rounded-full"></span>
                        <span>85% de l'objectif atteint</span>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">Événements Actifs</p>
                    <h3 class="text-3xl font-black text-secondary leading-none"><?= $eventsActif ?></h3>
                    <div class="mt-4 flex items-center gap-1.5 text-xs font-bold text-amber-500">
                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                        <span><?= $eventsAttente ?> en attente de validation</span>
                    </div>
                </div>
            </div>

            <!-- Simplified Events Data (Replaced Graphics with Tables) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Events Table -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-slate-200 flex justify-between items-center">
                            <h3 class="text-lg font-black text-secondary">Aperçu des Événements</h3>
                            <a href="#" class="text-xs font-bold text-primary hover:underline">Voir tout →</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-slate-50 border-b border-slate-200">
                                    <tr>
                                        <th class="px-6 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Événement / Date</th>
                                        <th class="px-6 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                                        <th class="px-6 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Duree</th>
                                    </tr>
                                </thead>
                                <?php foreach($matches as $match): ?>
                                    <tbody class="divide-y divide-slate-100">
                                        <tr class="hover:bg-slate-50 transition-colors group">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex -space-x-2">
                                                        <img src="../uploads/<?= $match->getEquipe1()->getLogo() ?>" class="w-10 h-10 object-contain bg-white rounded-full border border-slate-200 shadow-sm">
                                                        <img src="../uploads/<?= $match->getEquipe2()->getLogo() ?>" class="w-10 h-10 object-contain bg-white rounded-full border border-slate-200 shadow-sm">
                                                    </div>
                                                    <div>
                                                        <p class="font-bold text-secondary group-hover:text-primary transition-colors">
                                                            <?= $match->getEquipe1()->getNom() ?> vs <?= $match->getEquipe2()->getNom() ?>
                                                        </p>
                                                        <p class="text-xs text-slate-400"><?= date('d/m/Y H:i', strtotime($match->getDateHeure())) ?> • <?= $match->getLieu() ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <?php if ($match->getStatut() === 'valide'): ?>
                                                    <span class="inline-flex px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100">
                                                        Validé
                                                    </span>
                                                <?php elseif ($match->getStatut() === 'refuse'): ?>
                                                    <span class="inline-flex px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-widest bg-red-50 text-red-600 border border-red-100">
                                                        Refusé
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-widest bg-amber-50 text-amber-600 border border-amber-100">
                                                        En attente
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <p class="font-black text-secondary"><?= $match->getDuree() ?> min</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Activity / Notification Panel -->
                <div class="space-y-6">
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                        <h3 class="text-lg font-black text-secondary mb-6">Activités Récentes</h3>
                        <div class="space-y-6">
                            <div class="flex gap-4">
                                <div class="w-2 h-2 rounded-full bg-primary mt-1.5 shrink-0"></div>
                                <div>
                                    <p class="text-sm font-bold text-secondary">Nouveau billet vendu</p>
                                    <p class="text-xs text-slate-400 mt-1">PSG vs Real Madrid • il y a 5 min</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="w-2 h-2 rounded-full bg-slate-200 mt-1.5 shrink-0"></div>
                                <div>
                                    <p class="text-sm font-bold text-secondary leading-snug">Événement "Liverpool vs City" mis à jour</p>
                                    <p class="text-xs text-slate-400 mt-1">il y a 2 heures</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="w-2 h-2 rounded-full bg-amber-500 mt-1.5 shrink-0"></div>
                                <div>
                                    <p class="text-sm font-bold text-secondary leading-snug">Alerte de stock : PSG vs Real</p>
                                    <p class="text-xs text-slate-400 mt-1">Moins de 10% restants • il y a 3 heures</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="w-2 h-2 rounded-full bg-primary mt-1.5 shrink-0"></div>
                                <div>
                                    <p class="text-sm font-bold text-secondary">Rapport hebdomadaire prêt</p>
                                    <p class="text-xs text-slate-400 mt-1">Généré automatiquement • il y a 1 jour</p>
                                </div>
                            </div>
                        </div>
                        <button class="w-full mt-8 py-2.5 border border-slate-200 rounded-lg text-xs font-bold text-slate-400 hover:bg-slate-50 hover:text-secondary transition-all">Afficher tout l'historique</button>
                    </div>
                </div>
            </div>
        </main>
    </div>

</body>
</html>
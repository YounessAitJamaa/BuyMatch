<?php
    session_start();

    require_once '../../repositories/UtilisateurRepository.php';
    require_once '../../repositories/OrganisateurRepository.php';
    require_once '../../repositories/MatchRepository.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Administrateur') {
        header('Location: ../login.php');
        exit;
    }

    $userRepo = new UtilisateurRepository();
    $matchRepo = new MatchRepository();

    $totalUsers = $userRepo->getUsers();
    $totalOrganisateurs = $userRepo->getTotalOrganisateurs();
    $totalMatches = $userRepo->getTotalMatches();
    $totalEnAttentMatches = $userRepo->getTotalEnAttentMatches();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - BuyMatch</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-screen">

    <!-- Updated navigation to match dashboard design with logo and profile dropdown -->
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
                    <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold">Admin Panel</p>
                </div>
            </div>
            
            <div class="flex items-center gap-5">
                <button class="p-2 text-slate-400 hover:text-emerald-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </button>
                <div class="h-8 w-px bg-slate-700"></div>
                <!-- Profile dropdown with admin indicator -->
                <div class="relative group">
                    <button class="flex items-center gap-3 cursor-pointer p-1.5 rounded-xl hover:bg-slate-700/50 transition-all">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold text-white">Admin User</p>
                            <p class="text-[10px] text-slate-400 font-medium">Administrateur</p>
                        </div>
                        <div class="w-9 h-9 rounded-full border-2 border-slate-600 p-0.5 group-hover:border-emerald-500/50 transition-all">
                            <img src="../../includes/assests/default_user.jpg" class="w-full h-full rounded-full object-cover" alt="Admin Profile">
                        </div>
                    </button>
                    
                    <div class="absolute right-0 mt-2 w-56 bg-slate-800/80 backdrop-blur rounded-2xl shadow-2xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform translate-y-2 group-hover:translate-y-0 border border-slate-700">
                        <div class="px-4 py-3 border-b border-slate-700 mb-2">
                            <p class="text-xs font-black uppercase text-slate-400 tracking-widest">Admin</p>
                        </div>
                        <div class="h-px bg-slate-700 my-2"></div>
                        <a href="../auth/logout.php" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-red-500 hover:bg-red-500/10 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-width="2"/></svg>
                            Déconnexion
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex pt-20">
        <!-- Added sidebar navigation matching dashboard design -->
        <aside class="w-72 min-h-screen sticky top-20 hidden lg:block border-r border-slate-700 bg-slate-800/30 backdrop-blur p-8 space-y-10">
            <div>
                <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-6">Administration</h3>
                <nav class="space-y-1">
                    <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-emerald-600/20 text-emerald-500 font-bold transition-all border border-emerald-500/30 hover:shadow-lg hover:shadow-emerald-500/10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h7" stroke-width="2" stroke-linecap="round"/></svg>
                        <span>Tableau de Bord</span>
                    </a>
                    <a href="gerer_users.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-700/30 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 12H9m4 0a4 4 0 100-8 4 4 0 000 8z" stroke-width="2" stroke-linecap="round"/></svg>
                        <span>Gérer Utilisateurs</span>
                    </a>
                    <a href="matches.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-700/30 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" stroke-width="2" stroke-linecap="round"/></svg>
                        <span>Valider Matchs</span>
                    </a>
                </nav>
            </div>

            <div class="pt-6 border-t border-slate-700">
                <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-6">Compte</h3>
                <nav class="space-y-1">
                    <a href="../auth/logout.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-700/30 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-width="2" stroke-linecap="round"/></svg>
                        <span>Se déconnecter</span>
                    </a>
                </nav>
            </div>
        </aside>

        <!-- Modernized main content area with updated stats cards and action buttons -->
        <main class="flex-1 p-8 md:p-12 max-w-7xl mx-auto w-full">
            <header class="mb-12">
                <h2 class="text-4xl font-black text-white tracking-tight">Tableau de Bord Admin</h2>
                <p class="text-slate-400 font-medium mt-2">Gérez les utilisateurs, validez les matchs et supervisez la plateforme</p>
            </header>

            <!-- Updated stats cards with modern glassmorphism styling -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">

                <div class="bg-slate-800/50 backdrop-blur rounded-xl p-6 border border-slate-700/50 hover:border-emerald-500/30 transition-all hover:shadow-lg hover:shadow-emerald-500/10">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Utilisateurs Total</p>
                            <p class="text-3xl font-black mt-3 text-white"><?= htmlspecialchars($totalUsers) ?></p>
                        </div>
                        <div class="w-12 h-12 bg-emerald-500/10 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m4 0a4 4 0 100-8 4 4 0 000 8z" /></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-800/50 backdrop-blur rounded-xl p-6 border border-slate-700/50 hover:border-blue-500/30 transition-all hover:shadow-lg hover:shadow-blue-500/10">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Organisateurs</p>
                            <p class="text-3xl font-black mt-3 text-white"><?= htmlspecialchars($totalOrganisateurs) ?></p>
                        </div>
                        <div class="w-12 h-12 bg-blue-500/10 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-800/50 backdrop-blur rounded-xl p-6 border border-slate-700/50 hover:border-slate-600/30 transition-all hover:shadow-lg hover:shadow-slate-500/10">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Matchs</p>
                            <p class="text-3xl font-black mt-3 text-white"><?= htmlspecialchars($totalMatches) ?></p>
                        </div>
                        <div class="w-12 h-12 bg-slate-600/10 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-800/50 backdrop-blur rounded-xl p-6 border border-yellow-500/40 hover:border-yellow-500/60 transition-all hover:shadow-lg hover:shadow-yellow-500/10">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-400 text-xs font-bold uppercase tracking-widest">Matchs en Attente</p>
                            <p class="text-3xl font-black mt-3 text-yellow-400"><?= htmlspecialchars($totalEnAttentMatches) ?></p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-500/10 rounded-lg flex items-center justify-center animate-pulse">
                            <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Updated admin action cards with modern styling and better spacing -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <a href="gerer_users.php"
                   class="group bg-slate-800/50 backdrop-blur hover:bg-slate-700/50 transition-all rounded-xl p-8 border border-slate-700/50 hover:border-emerald-500/30 hover:shadow-lg hover:shadow-emerald-500/10">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h2 class="text-xl font-black mb-2 text-white group-hover:text-emerald-400 transition-colors">Gérer les Utilisateurs</h2>
                            <p class="text-slate-400 text-sm">
                                Activez, désactivez ou passez en revue les comptes utilisateurs
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-emerald-500/10 rounded-lg flex items-center justify-center group-hover:bg-emerald-500/20 transition-all">
                            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m4 0a4 4 0 100-8 4 4 0 000 8z" /></svg>
                        </div>
                    </div>
                    <div class="inline-flex items-center gap-2 text-emerald-400 text-sm font-bold group-hover:gap-3 transition-all">
                        Accéder <span>→</span>
                    </div>
                </a>

                <a href="matches.php"
                   class="group bg-slate-800/50 backdrop-blur hover:bg-slate-700/50 transition-all rounded-xl p-8 border border-slate-700/50 hover:border-emerald-500/30 hover:shadow-lg hover:shadow-emerald-500/10">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h2 class="text-xl font-black mb-2 text-white group-hover:text-emerald-400 transition-colors">Valider les Matchs</h2>
                            <p class="text-slate-400 text-sm">
                                Approuvez ou refusez les matchs créés par les organisateurs
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-emerald-500/10 rounded-lg flex items-center justify-center group-hover:bg-emerald-500/20 transition-all">
                            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                        </div>
                    </div>
                    <div class="inline-flex items-center gap-2 text-emerald-400 text-sm font-bold group-hover:gap-3 transition-all">
                        Accéder <span>→</span>
                    </div>
                </a>

            </div>

        </main>

    </div>

</body>
</html>

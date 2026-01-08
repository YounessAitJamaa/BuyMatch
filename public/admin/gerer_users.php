<?php
    session_start();

    require_once '../../repositories/UtilisateurRepository.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Administrateur') {
        header('Location: ../login.php');
        exit;
    }

    $userRepo = new UtilisateurRepository();

    $users = $userRepo->findAll();


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les utilisateurs - Admin</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-screen">

    <!-- Fixed Top Navigation with glassmorphism -->
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
                <!-- Profile dropdown -->
                <div class="relative group">
                    <button class="flex items-center gap-3 cursor-pointer p-1.5 rounded-xl hover:bg-slate-700/50 transition-all">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold text-white">Admin User</p>
                            <p class="text-[10px] text-slate-400 font-medium">Administrateur</p>
                        </div>
                        <div class="w-9 h-9 rounded-full border-2 border-slate-600 p-0.5 group-hover:border-emerald-500/50 transition-all">
                            <img src="/placeholder.svg?height=40&width=40" class="w-full h-full rounded-full object-cover" alt="Admin Profile">
                        </div>
                    </button>
                    
                    <div class="absolute right-0 mt-2 w-56 bg-slate-800/80 backdrop-blur rounded-2xl shadow-2xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform translate-y-2 group-hover:translate-y-0 border border-slate-700">
                        <div class="px-4 py-3 border-b border-slate-700 mb-2">
                            <p class="text-xs font-black uppercase text-slate-400 tracking-widest">Admin</p>
                        </div>
                        <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-slate-300 hover:text-emerald-500 hover:bg-slate-700/50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2"/></svg>
                            Mon Profil
                        </a>
                        <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-slate-300 hover:text-emerald-500 hover:bg-slate-700/50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31 2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" stroke-width="2"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2"/></svg>
                            Paramètres
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
        <!-- Sidebar Navigation -->
        <aside class="w-72 min-h-screen sticky top-20 hidden lg:block border-r border-slate-700 bg-slate-800/30 backdrop-blur p-8 space-y-10">
            <div>
                <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-6">Administration</h3>
                <nav class="space-y-1">
                    <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-700/30 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h7" stroke-width="2" stroke-linecap="round"/></svg>
                        <span>Tableau de Bord</span>
                    </a>
                    <a href="gerer_users.php" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-emerald-600/20 text-emerald-500 font-bold transition-all border border-emerald-500/30 hover:shadow-lg hover:shadow-emerald-500/10">
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
                    <a href="../logout.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-700/30 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-width="2" stroke-linecap="round"/></svg>
                        <span>Se déconnecter</span>
                    </a>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8 md:p-12 max-w-7xl mx-auto w-full">
            <header class="mb-12">
                <h2 class="text-4xl font-black text-white tracking-tight">Gestion des Utilisateurs</h2>
                <p class="text-slate-400 font-medium mt-2">Activez, désactivez ou passez en revue les comptes utilisateurs de la plateforme</p>
            </header>

            <!-- Users Table -->
            <div class="bg-slate-800/50 backdrop-blur rounded-xl border border-slate-700/50 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-900/50 border-b border-slate-700">
                            <tr>
                                <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Nom</th>
                                <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Email</th>
                                <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Rôle</th>
                                <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Statut</th>
                                <th class="px-8 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-700">
                            <?php foreach ($users as $user): ?>
                                <tr class="hover:bg-slate-700/30 transition-colors group">
                                    <td class="px-8 py-5 font-bold text-white group-hover:text-emerald-500 transition-colors">
                                        <?= htmlspecialchars($user->getNom()) ?>
                                    </td>

                                    <td class="px-8 py-5 text-slate-400">
                                        <?= htmlspecialchars($user->getEmail()) ?>
                                    </td>

                                    <td class="px-8 py-5">
                                        <span class="inline-flex px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest
                                            <?php if ($user->getRole()->getNomRole() === 'Administrateur'): ?>
                                                bg-red-500/20 text-red-400 border border-red-500/30
                                            <?php elseif ($user->getRole()->getNomRole() === 'Organisateur'): ?>
                                                bg-emerald-500/20 text-emerald-400 border border-emerald-500/30
                                            <?php else: ?>
                                                bg-blue-500/20 text-blue-400 border border-blue-500/30
                                            <?php endif; ?>
                                        ">
                                            <?= $user->getRole()->getNomRole() ?>
                                        </span>
                                    </td>

                                    <td class="px-8 py-5">
                                        <?php if ($user->isActif()): ?>
                                            <div class="flex items-center gap-2">
                                                <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                                <span class="text-emerald-400 font-bold text-sm">Actif</span>
                                            </div>
                                        <?php else: ?>
                                            <div class="flex items-center gap-2">
                                                <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                                <span class="text-red-400 font-bold text-sm">Désactivé</span>
                                            </div>
                                        <?php endif; ?>
                                    </td>

                                    <td class="px-8 py-5 text-center flex items-center justify-center gap-2">
                                        <?php if ($user->getId() !== $_SESSION['user_id']): ?>
                                            <a href="edit_role.php?id=<?= $user->getId() ?>" 
                                                class="inline-flex px-4 py-2 rounded-lg font-bold text-xs bg-slate-700 text-slate-300 hover:bg-slate-600 border border-slate-600 transition-all">
                                                    Rôle
                                            </a>
                                            <a href="toggle_user.php?id=<?= $user->getId() ?>"
                                               class="inline-flex px-4 py-2 rounded-lg font-bold text-xs transition-all
                                               <?= $user->isActif()
                                                    ? 'bg-red-600/20 text-red-400 hover:bg-red-600/40 border border-red-500/30'
                                                    : 'bg-emerald-600/20 text-emerald-400 hover:bg-emerald-600/40 border border-emerald-500/30'
                                               ?>">
                                                <?= $user->isActif() ? 'Désactiver' : 'Activer' ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-slate-500 text-xs italic font-bold">Vous</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

</body>
</html>

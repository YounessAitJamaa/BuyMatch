<?php

    session_start();

    require_once '../../repositories/UtilisateurRepository.php';
    require_once '../../repositories/OrganisateurRepository.php';

    if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Organisateur') {
        header('Location: ../login.php');
        exit;
    }
    
    $organisateurId = $_SESSION['user_id'];
    $userRepo = new UtilisateurRepository();

    $user = $userRepo->findById($organisateurId);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - BuyMatch</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-screen p-6 md:p-12">

    <div class="max-w-4xl mx-auto">
        <a href="dashboard.php" class="text-slate-400 hover:text-emerald-500 font-bold flex items-center gap-2 mb-8 transition-colors">
            ← Retour au tableau de bord
        </a>

        <div class="bg-slate-800/50 backdrop-blur rounded-2xl shadow-2xl border border-slate-700/50 overflow-hidden">
            <!-- header with gradient background -->
            <div class="bg-gradient-to-r from-slate-900 to-slate-800 p-8 text-white border-b border-slate-700">
                <h1 class="text-3xl font-black tracking-tight">Mon Profil</h1>
                <p class="text-slate-400 mt-2 text-sm">Gérez vos informations personnelles et paramètres de compte.</p>
            </div>

            <div class="p-8 space-y-10">
                <!-- user profile header section -->
                <section class="flex flex-col md:flex-row items-start md:items-center gap-8">
                    <!-- Profile Picture -->
                    <div class="shrink-0 h-32 w-32 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl flex items-center justify-center text-white font-black text-4xl overflow-hidden border-4 border-slate-700">
                        <img 
                            src="../../includes/assests/<?= htmlspecialchars($user->getPhoto()) ?>"
                            class="w-full h-full object-cover"
                            alt="Profile photo"
                        >
                    </div>

                    <!-- User Info -->
                    <div class="flex-1 min-w-0">
                        <h2 class="text-3xl font-black text-slate-100 truncate"><?= htmlspecialchars($user->getNom()) ?></h2>
                        <p class="text-slate-400 text-sm mt-1 truncate"><?= htmlspecialchars($user->getEmail()) ?></p>
                        
                        <!-- Role Badge -->
                        <div class="mt-4 flex items-center gap-3">
                            <span class="inline-block px-4 py-2 bg-emerald-500/20 border border-emerald-500/50 rounded-lg text-emerald-400 text-xs font-bold uppercase tracking-wider">
                                <?= htmlspecialchars($user->getRole()->getNomRole()) ?>
                            </span>
                            <span class="inline-block px-4 py-2 bg-slate-700/50 border border-slate-600 rounded-lg text-slate-300 text-xs font-bold uppercase tracking-wider">
                                <?= $user->isActif() ? '✓ Actif' : '⊘ Désactivé' ?>
                            </span>
                        </div>
                    </div>
                </section>

                <!-- Divider -->
                <hr class="border-slate-700">

                <!-- account information grid -->
                <section>
                    <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6">Informations du Compte</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="block text-xs font-bold text-slate-400 uppercase">Statut du Compte</label>
                            <div class="p-4 bg-slate-700/30 border border-slate-600 rounded-xl">
                                <p class="text-slate-100 font-semibold">
                                    <?= $user->isActif() ? 'Actif' : 'Désactivé' ?>
                                </p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-xs font-bold text-slate-400 uppercase">Rôle</label>
                            <div class="p-4 bg-slate-700/30 border border-slate-600 rounded-xl">
                                <p class="text-slate-100 font-semibold">
                                    <?= htmlspecialchars($user->getRole()->getNomRole()) ?>
                                </p>
                            </div>
                        </div>

                        <div class="space-y-3 md:col-span-2">
                            <label class="block text-xs font-bold text-slate-400 uppercase">Email de Profil</label>
                            <div class="p-4 bg-slate-700/30 border border-slate-600 rounded-xl">
                                <p class="text-slate-400 text-sm truncate font-mono">
                                    <?= htmlspecialchars($user->getEmail()) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- action buttons -->
                <div class="pt-6 flex justify-end gap-4">
                    <a href="dashboard.php" class="px-6 py-3 bg-slate-700 hover:bg-slate-600 text-slate-100 font-bold rounded-lg transition-all">
                        Annuler
                    </a>
                    <a href="edit_profile.php" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-lg shadow-lg shadow-emerald-500/20 transition-all hover:shadow-emerald-500/40">
                        ✎ Modifier le Profil
                    </a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
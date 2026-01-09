<?php
session_start();

require_once '../../repositories/BilletRepository.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Acheteur') {
    header('Location: ../auth/login.php');
    exit;
}

$billetRepo = new BilletRepository();
$billets = $billetRepo->findByUser($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Dashboard - BuyMatch</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        @keyframes gradient {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.95); opacity: 1; }
            100% { transform: scale(1.3); opacity: 0; }
        }
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        .animate-gradient {
            background-size: 200% 200%;
            animation: gradient 8s ease infinite;
        }
        .glass-effect {
            background: rgba(30, 41, 59, 0.4);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(148, 163, 184, 0.1);
        }
        .pulse-ring {
            animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        .shimmer {
            position: relative;
            overflow: hidden;
        }
        .shimmer::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            animation: shimmer 3s infinite;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 min-h-screen overflow-y-auto">
    
    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/3 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl animate-float" style="animation-delay: 4s;"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-6 py-12">
        <!-- Header Section -->
        <div class="mb-12">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="relative">
                            <div class="absolute inset-0 bg-emerald-500/30 rounded-2xl blur-xl"></div>
                            <div class="relative w-16 h-16 bg-gradient-to-br from-emerald-500/20 to-teal-500/20 rounded-2xl flex items-center justify-center border border-emerald-500/30">
                                <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" stroke-linecap="round" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h1 class="text-5xl font-black text-white mb-1">Mes Billets</h1>
                            <p class="text-slate-400 text-sm font-medium">Retrouvez toutes vos réservations et vos accès QR Code</p>
                        </div>
                    </div>
                </div>
                
                <a href="../matchs/index.php" class="relative group overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 to-teal-500 transition-transform duration-300 group-hover:scale-105 rounded-2xl"></div>
                    <div class="relative py-3 px-6 bg-gradient-to-r from-emerald-600 to-teal-600 group-hover:from-emerald-500 group-hover:to-teal-500 rounded-2xl shadow-lg shadow-emerald-500/30 group-hover:shadow-emerald-500/50 transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="text-white font-black uppercase tracking-wider text-sm">Acheter des places</span>
                    </div>
                </a>
            </div>

            <!-- Stats Bar -->
            <?php if (!empty($billets)): ?>
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="glass-effect rounded-2xl p-6 border border-slate-700/50 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 rounded-full blur-2xl"></div>
                    <div class="relative flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500/20 to-teal-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" stroke-linecap="round" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-slate-500 text-xs font-bold uppercase">Total Billets</p>
                            <p class="text-white font-black text-2xl"><?= count($billets) ?></p>
                        </div>
                    </div>
                </div>

                <div class="glass-effect rounded-2xl p-6 border border-slate-700/50 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-purple-500/5 rounded-full blur-2xl"></div>
                    <div class="relative flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500/20 to-pink-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-slate-500 text-xs font-bold uppercase">Matchs Réservés</p>
                            <p class="text-white font-black text-2xl"><?= count(array_unique(array_column($billets, 'match_id'))) ?></p>
                        </div>
                    </div>
                </div>

                <div class="glass-effect rounded-2xl p-6 border border-slate-700/50 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/5 rounded-full blur-2xl"></div>
                    <div class="relative flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500/20 to-cyan-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-slate-500 text-xs font-bold uppercase">Profil</p>
                            <p class="text-white font-black text-sm"><?= htmlspecialchars($_SESSION['user_name']) ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Tickets Grid -->
        <?php if (empty($billets)): ?>
            <div class="glass-effect rounded-3xl border-2 border-dashed border-slate-700/50 p-20 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 via-teal-500 to-emerald-500 animate-gradient"></div>
                
                <div class="relative max-w-md mx-auto">
                    <div class="mb-8 relative inline-block">
                        <div class="absolute inset-0 bg-slate-700/20 rounded-full blur-2xl pulse-ring"></div>
                        <div class="relative w-24 h-24 mx-auto bg-gradient-to-br from-slate-800/50 to-slate-700/50 rounded-full flex items-center justify-center border border-slate-700/50">
                            <svg class="w-12 h-12 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" stroke-linecap="round" />
                            </svg>
                        </div>
                    </div>
                    
                    <h3 class="text-2xl font-black text-white mb-3">Aucun billet pour le moment</h3>
                    <p class="text-slate-500 mb-8">Découvrez nos matchs à venir et réservez vos places dès maintenant !</p>
                    
                    <a href="../matchs/index.php" class="relative inline-block group overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 to-teal-500 transition-transform duration-300 group-hover:scale-105 rounded-2xl"></div>
                        <div class="relative py-4 px-8 bg-gradient-to-r from-emerald-600 to-teal-600 group-hover:from-emerald-500 group-hover:to-teal-500 rounded-2xl shadow-lg shadow-emerald-500/30 group-hover:shadow-emerald-500/50 transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <span class="text-white font-black uppercase tracking-wider">Voir les matchs</span>
                        </div>
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <?php foreach ($billets as $b): ?>
                    <div class="glass-effect rounded-3xl overflow-hidden border border-slate-700/50 hover:border-emerald-500/30 transition-all duration-300 group relative">
                        <!-- Gradient border effect on hover -->
                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/0 via-teal-500/0 to-blue-500/0 group-hover:from-emerald-500/10 group-hover:via-teal-500/10 group-hover:to-blue-500/10 rounded-3xl transition-all duration-300"></div>
                        
                        <div class="relative flex flex-col sm:flex-row">
                            <!-- Left Side - Match Info -->
                            <div class="flex-1 p-8 border-b sm:border-b-0 sm:border-r border-slate-700/50">
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                                    <span class="px-3 py-1 bg-gradient-to-r from-emerald-500/20 to-teal-500/20 text-emerald-400 text-[10px] font-black uppercase rounded-full border border-emerald-500/30 tracking-widest">
                                        <?= htmlspecialchars($b['cat_nom']) ?>
                                    </span>
                                </div>
                                
                                <h3 class="text-xl font-black text-white mb-3 group-hover:text-emerald-400 transition-colors">
                                    <?= htmlspecialchars($b['e1_nom']) ?> <span class="text-emerald-500">vs</span> <?= htmlspecialchars($b['e2_nom']) ?>
                                </h3>
                                
                                <div class="flex items-center gap-2 text-slate-400 text-sm mb-6">
                                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <span class="font-medium"><?= htmlspecialchars($b['lieu']) ?></span>
                                </div>
                                
                                <div class="flex items-center gap-6">
                                    <div class="flex items-center gap-2 text-sm">
                                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500/20 to-cyan-500/20 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-slate-500 text-[10px] font-bold uppercase">Date</p>
                                            <p class="text-white font-bold"><?= date('d/m/Y', strtotime($b['date_heure'])) ?></p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-2 text-sm">
                                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-orange-500/20 to-red-500/20 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-slate-500 text-[10px] font-bold uppercase">Heure</p>
                                            <p class="text-white font-bold"><?= date('H:i', strtotime($b['date_heure'])) ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 pt-4 border-t border-slate-800">
                                    <a href="export_pdf.php?id=<?= $b['id'] ?>" 
                                    class="inline-flex items-center gap-2 text-xs font-bold text-emerald-500 hover:text-emerald-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="2"/></svg>
                                        TÉLÉCHARGER LE PDF
                                    </a>
                                </div>
                            </div>

                            <!-- Right Side - QR Code -->
                            <div class="p-8 bg-gradient-to-br from-white to-slate-50 flex flex-col items-center justify-center w-full sm:w-56 relative overflow-hidden shimmer">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 rounded-full blur-3xl"></div>
                                
                                <div class="relative mb-4 p-3 bg-white rounded-2xl shadow-lg">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=<?= urlencode($b['qr_code']) ?>" 
                                         alt="QR Code" 
                                         class="w-full h-auto">
                                </div>
                                
                                <div class="text-center">
                                    <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">Place N°</p>
                                    <div class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-xl shadow-lg">
                                        <p class="font-black text-2xl text-white"><?= $b['numero_place'] ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Footer Info -->
        <div class="mt-12 text-center">
            <div class="inline-flex items-center gap-2 glass-effect px-6 py-3 rounded-full border border-slate-700/50">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                <span class="text-slate-400 text-xs">Présentez votre QR code à l'entrée du stade</span>
            </div>
        </div>
    </div>

</body>
</html>
<?php

    require_once '../../repositories/MatchRepository.php';
    $matchRepo = new MatchRepository();
    $matches = $matchRepo->findAllValide();
    
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matchs Validés - BuyMatch</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        @keyframes gradient {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
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
        .card-hover {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-8px) scale(1.02);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 min-h-screen overflow-x-hidden">
    
    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-72 h-72 bg-emerald-500/10 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl animate-float" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-1/2 w-64 h-64 bg-purple-500/10 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
    </div>

    <!-- Modern Navigation Bar -->
    <nav class="fixed top-0 w-full z-50 glass-effect border-b border-slate-700/50">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-xl blur opacity-75"></div>
                        <div class="relative w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-2xl">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-xl font-black tracking-tight bg-gradient-to-r from-white to-emerald-400 bg-clip-text text-transparent">
                            BuyMatch
                        </h1>
                        <p class="text-[10px] uppercase tracking-widest text-emerald-400 font-bold">Pro Platform</p>
                    </div>  
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="relative pt-28 px-6 pb-16">
        <div class="max-w-7xl mx-auto">
            
            <!-- Hero Header -->
            <header class="mb-16 text-center">
                <div class="inline-flex items-center gap-3 px-5 py-2 rounded-full glass-effect mb-6">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                    <span class="text-emerald-400 font-bold text-sm uppercase tracking-wider">Live Matches</span>
                </div>
                
                <h2 class="text-6xl md:text-7xl font-black text-white tracking-tight mb-4 leading-tight">
                    Matchs <span class="bg-gradient-to-r from-emerald-400 via-teal-400 to-blue-400 bg-clip-text text-transparent animate-gradient">Validés</span>
                </h2>
                <p class="text-slate-400 text-lg font-medium max-w-2xl mx-auto">
                    Découvrez les meilleurs matchs validés et réservez votre place pour vivre des moments inoubliables
                </p>
                
                <!-- Stats Bar -->
                <div class="flex justify-center gap-8 mt-10">
                    <div class="text-center">
                        <p class="text-3xl font-black text-white"><?= count($matches) ?></p>
                        <p class="text-emerald-400 text-sm font-bold uppercase tracking-wider">Matchs</p>
                    </div>
                    <div class="w-px bg-slate-700"></div>
                    <div class="text-center">
                        <p class="text-3xl font-black text-white">48h</p>
                        <p class="text-emerald-400 text-sm font-bold uppercase tracking-wider">Disponible</p>
                    </div>
                    <div class="w-px bg-slate-700"></div>
                    <div class="text-center">
                        <p class="text-3xl font-black text-white">100%</p>
                        <p class="text-emerald-400 text-sm font-bold uppercase tracking-wider">Validé</p>
                    </div>
                </div>
            </header>

            <!-- Matches Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($matches as $match): ?>
                    <div class="card-hover glass-effect rounded-2xl overflow-hidden shadow-2xl group relative">
                        <!-- Gradient Border Effect -->
                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/20 via-teal-500/20 to-blue-500/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-2xl"></div>
                        
                        <div class="relative p-8">
                            <!-- Match Header -->
                            <div class="flex justify-between items-start mb-8">
                                <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-500/20 border border-emerald-500/30">
                                    <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></div>
                                    <span class="text-emerald-400 text-xs font-bold uppercase">Validé</span>
                                </div>
                                
                                <button class="w-10 h-10 rounded-full glass-effect flex items-center justify-center hover:bg-slate-700/50 transition-colors">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Team Matchup -->
                            <div class="flex justify-between items-center mb-8 relative">
                                <div class="text-center w-28">
                                    <div class="relative mb-3 group-hover:scale-110 transition-transform duration-300">
                                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/30 to-transparent rounded-2xl blur-xl"></div>
                                        <img src="../uploads/<?= $match->getEquipe1()->getLogo() ?>" 
                                             alt="<?= htmlspecialchars($match->getEquipe1()->getNom()) ?>"
                                             class="relative w-20 h-20 mx-auto object-contain drop-shadow-2xl">
                                    </div>
                                    <p class="text-white font-bold text-sm leading-tight">
                                        <?= htmlspecialchars($match->getEquipe1()->getNom()) ?>
                                    </p>
                                </div>
                                
                                <div class="flex flex-col items-center gap-2">
                                    <div class="px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-500/20 to-teal-500/20 border border-emerald-500/30">
                                        <span class="text-emerald-400 font-black text-xl tracking-wider">VS</span>
                                    </div>
                                    <div class="w-12 h-0.5 bg-gradient-to-r from-transparent via-emerald-500/50 to-transparent"></div>
                                </div>
                                
                                <div class="text-center w-28">
                                    <div class="relative mb-3 group-hover:scale-110 transition-transform duration-300">
                                        <div class="absolute inset-0 bg-gradient-to-br from-teal-500/30 to-transparent rounded-2xl blur-xl"></div>
                                        <img src="../uploads/<?= $match->getEquipe2()->getLogo() ?>" 
                                             alt="<?= htmlspecialchars($match->getEquipe2()->getNom()) ?>"
                                             class="relative w-20 h-20 mx-auto object-contain drop-shadow-2xl">
                                    </div>
                                    <p class="text-white font-bold text-sm leading-tight">
                                        <?= htmlspecialchars($match->getEquipe2()->getNom()) ?>
                                    </p>
                                </div>
                            </div>

                            <!-- Match Details -->
                            <div class="space-y-4 mb-8 p-5 rounded-xl glass-effect">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-emerald-500/20 to-teal-500/20 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                            <line x1="8" y1="2" x2="8" y2="6"></line>
                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-slate-500 text-xs font-bold uppercase">Date</p>
                                        <p class="text-white font-bold"><?= date('d M Y', strtotime($match->getDateHeure())) ?></p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500/20 to-purple-500/20 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-slate-500 text-xs font-bold uppercase">Heure</p>
                                        <p class="text-white font-bold"><?= date('H:i', strtotime($match->getDateHeure())) ?></p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500/20 to-pink-500/20 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-slate-500 text-xs font-bold uppercase">Lieu</p>
                                        <p class="text-white font-bold truncate"><?= htmlspecialchars($match->getLieu()) ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <a href="show.php?id=<?= $match->getId() ?>" 
                               class="block w-full group relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 to-teal-500 transition-transform duration-300 group-hover:scale-105"></div>
                                <div class="relative py-4 px-6 flex items-center justify-center gap-3 bg-gradient-to-r from-emerald-600 to-teal-600 group-hover:from-emerald-500 group-hover:to-teal-500 rounded-xl shadow-lg shadow-emerald-500/30 group-hover:shadow-emerald-500/50 transition-all">
                                    <span class="text-white font-black text-sm uppercase tracking-wider">Voir les détails</span>
                                    <svg class="w-5 h-5 text-white group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                        <path d="M13 7l5 5m0 0l-5 5m5-5H6" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Empty State (if no matches) -->
            <?php if (empty($matches)): ?>
            <div class="text-center py-20">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full glass-effect mb-6">
                    <svg class="w-12 h-12 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M12 2a10 10 0 0 0 0 20"></path>
                        <path d="M12 2a10 10 0 0 1 0 20"></path>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12" y2="16"></line>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-white mb-3">Aucun match validé</h3>
                <p class="text-slate-400 mb-8">Les matchs validés apparaîtront ici</p>
                <button class="px-8 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-bold shadow-lg hover:scale-105 transition-transform">
                    Rafraîchir
                </button>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="relative border-t border-slate-800 mt-20">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <div class="flex justify-between items-center">
                <p class="text-slate-500 text-sm">© 2025 BuyMatch. Tous droits réservés.</p>
                <div class="flex gap-4">
                    <a href="#" class="text-slate-500 hover:text-emerald-400 transition-colors">CGU</a>
                    <a href="#" class="text-slate-500 hover:text-emerald-400 transition-colors">Confidentialité</a>
                    <a href="#" class="text-slate-500 hover:text-emerald-400 transition-colors">Support</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
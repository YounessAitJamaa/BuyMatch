<?php

    session_start();
    require_once '../../repositories/MatchRepository.php';

    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    $matchRepo = new MatchRepository();

    $match = $matchRepo->findById($id);

    if (!$match || $match->getStatut() !== 'valide') {
        header('Location: index.php');
        exit;
    }

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $match->getEquipe1()->getNom() ?> vs <?= $match->getEquipe2()->getNom() ?> - BuyMatch</title>
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
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(16, 185, 129, 0.3); }
            50% { box-shadow: 0 0 40px rgba(16, 185, 129, 0.6); }
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
        .pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
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
                
                <a href="index.php" class="flex items-center gap-2 px-5 py-2.5 rounded-lg glass-effect text-slate-300 hover:text-white hover:bg-slate-700/50 font-semibold transition-all group">
                    <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Retour
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="relative pt-28 px-6 pb-16">
        <div class="max-w-6xl mx-auto">
            
            <!-- Match Header Card -->
            <div class="glass-effect rounded-3xl overflow-hidden shadow-2xl mb-8 relative">
                <!-- Gradient Border Effect -->
                <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/20 via-teal-500/20 to-blue-500/20 opacity-0 hover:opacity-100 transition-opacity duration-500 rounded-3xl"></div>
                
                <div class="relative">
                    <!-- Status Badge -->
                    <div class="absolute top-8 right-8 z-10">
                        <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-500/20 border border-emerald-500/30 backdrop-blur">
                            <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                            <span class="text-emerald-400 text-xs font-bold uppercase tracking-wider">Match Validé</span>
                        </div>
                    </div>

                    <!-- Teams Display -->
                    <div class="p-12 md:p-16">
                        <div class="flex flex-col md:flex-row items-center justify-between gap-12">
                            
                            <!-- Team 1 -->
                            <div class="flex-1 text-center group">
                                <div class="relative mb-6 inline-block">
                                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/30 to-transparent rounded-3xl blur-2xl group-hover:scale-110 transition-transform"></div>
                                    <div class="relative w-36 h-36 mx-auto glass-effect rounded-3xl flex items-center justify-center p-6 shadow-2xl border border-slate-700/50 group-hover:border-emerald-500/50 transition-all">
                                        <img src="../uploads/<?= $match->getEquipe1()->getLogo() ?>" 
                                             alt="<?= htmlspecialchars($match->getEquipe1()->getNom()) ?>" 
                                             class="max-w-full h-auto drop-shadow-2xl">
                                    </div>
                                </div>
                                <h2 class="text-3xl font-black text-white uppercase tracking-tight leading-tight">
                                    <?= htmlspecialchars($match->getEquipe1()->getNom()) ?>
                                </h2>
                            </div>

                            <!-- VS Section -->
                            <div class="flex-none text-center">
                                <div class="mb-6">
                                    <div class="inline-flex items-center gap-3 px-5 py-2 rounded-full glass-effect border border-emerald-500/30">
                                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                        <span class="text-emerald-400 text-xs font-bold uppercase tracking-wider">Officiel</span>
                                    </div>
                                </div>
                                
                                <div class="relative">
                                    <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/20 to-teal-500/20 blur-xl"></div>
                                    <div class="relative px-8 py-4 rounded-2xl bg-gradient-to-r from-emerald-500/20 to-teal-500/20 border border-emerald-500/30 mb-6">
                                        <span class="text-6xl md:text-7xl font-black bg-gradient-to-r from-emerald-400 via-teal-400 to-blue-400 bg-clip-text text-transparent">VS</span>
                                    </div>
                                </div>
                                
                                <div class="space-y-2">
                                    <div class="flex items-center justify-center gap-2 text-slate-300">
                                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                            <line x1="8" y1="2" x2="8" y2="6"></line>
                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                        </svg>
                                        <span class="text-sm font-bold"><?= date('d M Y', strtotime($match->getDateHeure())) ?></span>
                                    </div>
                                    <div class="flex items-center justify-center gap-2 text-slate-300">
                                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                        <span class="text-sm font-bold"><?= date('H:i', strtotime($match->getDateHeure())) ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Team 2 -->
                            <div class="flex-1 text-center group">
                                <div class="relative mb-6 inline-block">
                                    <div class="absolute inset-0 bg-gradient-to-br from-teal-500/30 to-transparent rounded-3xl blur-2xl group-hover:scale-110 transition-transform"></div>
                                    <div class="relative w-36 h-36 mx-auto glass-effect rounded-3xl flex items-center justify-center p-6 shadow-2xl border border-slate-700/50 group-hover:border-teal-500/50 transition-all">
                                        <img src="../uploads/<?= $match->getEquipe2()->getLogo() ?>" 
                                             alt="<?= htmlspecialchars($match->getEquipe2()->getNom()) ?>" 
                                             class="max-w-full h-auto drop-shadow-2xl">
                                    </div>
                                </div>
                                <h2 class="text-3xl font-black text-white uppercase tracking-tight leading-tight">
                                    <?= htmlspecialchars($match->getEquipe2()->getNom()) ?>
                                </h2>
                            </div>
                        </div>
                    </div>

                    <!-- Match Info Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 border-t border-slate-700/50">
                        <div class="p-8 text-center border-b md:border-b-0 md:border-r border-slate-700/50 glass-effect hover:bg-slate-800/30 transition-colors">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500/20 to-pink-500/20 mb-3">
                                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                            </div>
                            <p class="text-[10px] uppercase tracking-[0.2em] text-slate-500 font-black mb-2">Lieu</p>
                            <p class="font-bold text-white text-lg"><?= htmlspecialchars($match->getLieu()) ?></p>
                        </div>
                        
                        <div class="p-8 text-center border-b md:border-b-0 md:border-r border-slate-700/50 glass-effect hover:bg-slate-800/30 transition-colors">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500/20 to-cyan-500/20 mb-3">
                                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                            </div>
                            <p class="text-[10px] uppercase tracking-[0.2em] text-slate-500 font-black mb-2">Durée</p>
                            <p class="font-bold text-white text-lg"><?= $match->getDuree() ?> Minutes</p>
                        </div>
                        
                        <div class="p-8 text-center glass-effect hover:bg-slate-800/30 transition-colors">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500/20 to-teal-500/20 mb-3">
                                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                            <p class="text-[10px] uppercase tracking-[0.2em] text-slate-500 font-black mb-2">Organisateur</p>
                            <p class="font-bold text-emerald-400 text-lg"><?= htmlspecialchars($match->getOrganisateur()->getNom()) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="relative overflow-hidden rounded-3xl">
                <!-- Animated gradient background -->
                <div class="absolute inset-0 bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-600 animate-gradient"></div>
                
                <div class="relative p-12 flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="text-center md:text-left">
                        <h3 class="text-4xl md:text-5xl font-black text-white leading-tight mb-3">
                            Prêt à vivre<br>l'expérience ?
                        </h3>
                        <p class="text-emerald-100 text-lg font-medium">
                            Réservez votre place en quelques clics et vivez des moments inoubliables
                        </p>
                    </div>
                    
                    <a href="acheter.php?id=<?= $match->getId() ?>" 
                       class="group relative overflow-hidden flex-shrink-0">
                        <div class="absolute inset-0 bg-white rounded-2xl blur opacity-50 group-hover:opacity-75 transition-opacity"></div>
                        <div class="relative bg-white text-emerald-600 px-12 py-6 rounded-2xl font-black text-xl shadow-2xl group-hover:bg-slate-900 group-hover:text-white transition-all flex items-center gap-3 pulse-glow">
                            <span class="uppercase tracking-wider">Acheter mon billet</span>
                            <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path d="M13 7l5 5m0 0l-5 5m5-5H6" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Additional Info (Optional) -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="glass-effect rounded-2xl p-8 border border-slate-700/50">
                    <h4 class="text-xl font-black text-white mb-4 flex items-center gap-3">
                        <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        Informations importantes
                    </h4>
                    <ul class="space-y-3 text-slate-300">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <span>Les billets sont valables jusqu'à 30 minutes avant le match</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <span>Présentation du billet électronique obligatoire à l'entrée</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <span>Arrivez 15 minutes avant le début du match</span>
                        </li>
                    </ul>
                </div>

            </div>
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
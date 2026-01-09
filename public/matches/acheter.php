<?php

    session_start();

    require_once '../../repositories/MatchRepository.php';
    require_once '../../repositories/CategorieRepository.php'; 

    $matchId = isset($_GET['id']) ? (int)$_GET['id'] : null;
    if (!$matchId) {
        header('Location: index.php');
        exit;
    }

    $matchRepo = new MatchRepository();
    $match = $matchRepo->findById($matchId);    
    $catRepo = new CategorieRepository();
    $categories = $catRepo->findAll($matchId); 

    if (!$match || $match->getStatut() !== 'valide') {
        header('Location: index.php?error=match_not_found');
        exit;
    }

    if (!isset($_SESSION['user_id'])) {
        
        $_SESSION['redirect_after_auth'] = "matchs/acheter.php?id=" . $matchId;
        header('Location: ../auth/login.php?reason=auth_required');
        exit;

    }

    if ($_SESSION['role'] !== 'Acheteur' && $_SESSION['role'] !== 'Administrateur') {
        header('Location: ../index.php?error=unauthorized_role');
        exit;
    }
    
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finaliser l'achat - BuyMatch</title>
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
    </style>
</head>
<body class="bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 min-h-screen py-12 px-6 overflow-y-auto">
    
    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/3 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl animate-float" style="animation-delay: 4s;"></div>
    </div>

    <div class="relative max-w-2xl w-full mx-auto my-8">
        <!-- Back Button -->
        <a href="show.php?id=<?= $match->getId() ?>" class="inline-flex items-center gap-2 text-slate-400 hover:text-white transition-colors mb-6 group">
            <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Retour au match
        </a>

        <!-- Main Card -->
        <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden relative">
            <!-- Gradient Border Effect -->
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/20 via-teal-500/20 to-blue-500/20 rounded-3xl blur opacity-50"></div>
            
            <div class="relative p-8 md:p-12">
                <!-- Header with Icon -->
                <div class="flex items-center gap-4 mb-8">
                    <div class="relative">
                        <div class="absolute inset-0 bg-emerald-500/30 rounded-2xl blur-xl"></div>
                        <div class="relative w-16 h-16 bg-gradient-to-br from-emerald-500/20 to-teal-500/20 rounded-2xl flex items-center justify-center border border-emerald-500/30">
                            <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-3xl font-black text-white">Confirmation d'achat</h2>
                        <p class="text-slate-400 text-sm font-medium">Étape finale pour votre billet</p>
                    </div>
                </div>

                <!-- Match Summary Card -->
                <div class="mb-8 glass-effect rounded-2xl p-8 border border-slate-700/50 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 rounded-full blur-2xl"></div>
                    
                    <div class="relative">
                        <div class="flex items-center gap-2 mb-6">
                            <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                            <p class="text-[10px] uppercase tracking-widest text-emerald-400 font-black">Récapitulatif du match</p>
                        </div>

                        <!-- Teams Display -->
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex-1 text-center">
                                <div class="w-20 h-20 mx-auto mb-3 glass-effect rounded-2xl flex items-center justify-center p-3 border border-slate-700/50">
                                    <img src="../uploads/<?= $match->getEquipe1()->getLogo() ?>" 
                                         alt="<?= htmlspecialchars($match->getEquipe1()->getNom()) ?>"
                                         class="max-w-full h-auto">
                                </div>
                                <span class="font-bold text-white text-sm"><?= htmlspecialchars($match->getEquipe1()->getNom()) ?></span>
                            </div>

                            <div class="px-6">
                                <div class="px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-500/20 to-teal-500/20 border border-emerald-500/30">
                                    <span class="text-emerald-400 font-black text-xl">VS</span>
                                </div>
                            </div>

                            <div class="flex-1 text-center">
                                <div class="w-20 h-20 mx-auto mb-3 glass-effect rounded-2xl flex items-center justify-center p-3 border border-slate-700/50">
                                    <img src="../uploads/<?= $match->getEquipe2()->getLogo() ?>" 
                                         alt="<?= htmlspecialchars($match->getEquipe2()->getNom()) ?>"
                                         class="max-w-full h-auto">
                                </div>
                                <span class="font-bold text-white text-sm"><?= htmlspecialchars($match->getEquipe2()->getNom()) ?></span>
                            </div>
                        </div>

                        <!-- Match Details -->
                        <div class="pt-6 border-t border-slate-700/50 grid grid-cols-2 gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500/20 to-pink-500/20 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-slate-500 text-xs font-bold uppercase">Lieu</p>
                                    <p class="text-white font-bold text-sm"><?= htmlspecialchars($match->getLieu()) ?></p>
                                </div>
                            </div>

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
                                    <p class="text-white font-bold text-sm"><?= date('d/m/Y', strtotime($match->getDateHeure())) ?></p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500/20 to-cyan-500/20 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-slate-500 text-xs font-bold uppercase">Heure</p>
                                    <p class="text-white font-bold text-sm"><?= date('H:i', strtotime($match->getDateHeure())) ?></p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-orange-500/20 to-red-500/20 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-slate-500 text-xs font-bold uppercase">Durée</p>
                                    <p class="text-white font-bold text-sm"><?= $match->getDuree() ?> min</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Purchase Form -->
                <form action="process_achat.php" method="POST" class="space-y-6">
                    <input type="hidden" name="match_id" value="<?= $match->getId() ?>">

                    <!-- Welcome Message -->
                    <div class="p-6 glass-effect border border-emerald-500/30 rounded-2xl relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 via-teal-500 to-emerald-500 animate-gradient"></div>
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <div class="absolute inset-0 bg-emerald-500/20 rounded-full blur-xl pulse-ring"></div>
                                <div class="relative w-12 h-12 bg-gradient-to-br from-emerald-500/20 to-teal-500/20 rounded-full flex items-center justify-center border border-emerald-500/30">
                                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="text-emerald-400 font-bold text-lg">
                                    Bonjour, <?= htmlspecialchars($_SESSION['user_name']) ?> !
                                </p>
                                <p class="text-slate-400 text-sm">Vous êtes sur le point de réserver votre place</p>
                            </div>
                        </div>
                    </div>

                    <!-- Seat Selection -->
                    <div class="glass-effect rounded-2xl p-6 border border-slate-700/50">
                        <h3 class="text-white font-black text-lg mb-4 flex items-center gap-3">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" stroke-linecap="round" />
                            </svg>
                            Choisir ma catégorie
                        </h3>
                        
                        <div class="grid grid-cols-1 gap-3">
                            <?php foreach($categories as $cat): ?>
                                <label class="relative flex items-center p-4 cursor-pointer rounded-xl border border-slate-700 hover:border-emerald-500/50 hover:bg-emerald-500/5 transition-all group">
                                    <input type="radio" name="categorie_id" value="<?= $cat->getId() ?>" class="peer hidden" required>
                                    
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-600 peer-checked:border-emerald-500 peer-checked:bg-emerald-500 flex items-center justify-center transition-all">
                                        <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                    </div>

                                    <div class="ml-4 flex-1">
                                        <p class="text-sm font-bold text-white"><?= htmlspecialchars($cat->getNom()) ?></p>
                                        <p class="text-[10px] text-slate-500 uppercase tracking-widest">Placement Libre</p>
                                    </div>

                                    <div class="text-emerald-400 font-black text-lg">
                                        <?= number_format($cat->getPrix(), 2) ?>€
                                    </div>

                                    <div class="absolute inset-0 rounded-xl border-2 border-emerald-500 opacity-0 peer-checked:opacity-100 pointer-events-none"></div>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Ticket Quantity -->
                    <div class="glass-effect rounded-2xl p-6 border border-slate-700/50">
                        <h3 class="text-white font-black text-lg mb-4 flex items-center gap-3">
                            <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Nombre de billets
                        </h3>
                        <div class="space-y-3">
                            <select name="quantite" class="w-full glass-effect border border-slate-700 rounded-xl px-4 py-3 text-white font-bold focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/50 outline-none transition-all cursor-pointer hover:border-emerald-500/50">
                                <?php for($i=1; $i<=4; $i++): ?>
                                    <option value="<?= $i ?>"><?= $i ?> <?= $i > 1 ? 'billets' : 'billet' ?></option>
                                <?php endfor; ?>
                            </select>
                            <p class="text-xs text-slate-500 italic flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                Maximum 4 billets par commande
                            </p>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="relative w-full group overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 to-teal-500 transition-transform duration-300 group-hover:scale-105 rounded-2xl"></div>
                        <div class="relative py-5 px-6 bg-gradient-to-r from-emerald-600 to-teal-600 group-hover:from-emerald-500 group-hover:to-teal-500 rounded-2xl shadow-lg shadow-emerald-500/30 group-hover:shadow-emerald-500/50 transition-all flex items-center justify-center gap-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <span class="text-white font-black text-lg uppercase tracking-wider">Confirmer et Payer</span>
                        </div>
                    </button>

                    <!-- Security Note -->
                    <div class="flex items-center justify-center gap-2 text-slate-500 text-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <rect x="5" y="11" width="14" height="10" rx="2"></rect>
                            <path d="M12 17a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"></path>
                            <path d="M8 11V7a4 4 0 0 1 8 0v4"></path>
                        </svg>
                        <span>Paiement sécurisé SSL</span>
                    </div>
                </form>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="mt-6 text-center">
            <p class="text-slate-500 text-xs">
                En confirmant, vous acceptez nos 
                <a href="#" class="text-emerald-400 hover:text-emerald-300 transition-colors">conditions générales</a>
            </p>
        </div>
    </div>

</body>
</html>
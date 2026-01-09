<?php
    http_response_code(404);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page introuvable - BuyMatch</title>
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
        @keyframes glitch {
            0%, 100% { transform: translate(0); }
            20% { transform: translate(-2px, 2px); }
            40% { transform: translate(-2px, -2px); }
            60% { transform: translate(2px, 2px); }
            80% { transform: translate(2px, -2px); }
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
        .animate-glitch {
            animation: glitch 0.3s ease-in-out infinite;
        }
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        .delay-1 { animation-delay: 0.1s; opacity: 0; }
        .delay-2 { animation-delay: 0.2s; opacity: 0; }
        .delay-3 { animation-delay: 0.3s; opacity: 0; }
        .delay-4 { animation-delay: 0.4s; opacity: 0; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 min-h-screen overflow-hidden flex items-center justify-center">
    
    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/3 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl animate-float" style="animation-delay: 4s;"></div>
        <div class="absolute top-1/3 right-1/4 w-80 h-80 bg-red-500/10 rounded-full blur-3xl animate-float" style="animation-delay: 1s;"></div>
    </div>

    <div class="relative max-w-4xl mx-auto px-6 py-12 text-center">
        
        <!-- 404 Icon -->
        <div class="mb-8 relative inline-block fade-in-up">
            <div class="absolute inset-0 bg-red-500/20 rounded-full blur-3xl pulse-ring"></div>
            <div class="relative w-32 h-32 mx-auto bg-gradient-to-br from-red-500/20 to-rose-500/20 rounded-full flex items-center justify-center border border-red-500/30">
                <svg class="w-16 h-16 text-red-400 animate-glitch" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>

        <!-- 404 Number -->
        <div class="mb-6 fade-in-up delay-1">
            <h1 class="text-9xl md:text-[12rem] font-black text-transparent bg-clip-text bg-gradient-to-r from-red-500 via-rose-500 to-pink-500 leading-none animate-gradient">
                404
            </h1>
        </div>

        <!-- Error Message -->
        <div class="mb-8 fade-in-up delay-2">
            <h2 class="text-3xl md:text-5xl font-black text-white mb-4">
                Page introuvable
            </h2>
            <p class="text-slate-400 text-lg max-w-2xl mx-auto">
                Oups ! Il semblerait que cette page ait été expulsée du stade. 
                La page que vous recherchez n'existe pas ou a été déplacée.
            </p>
        </div>

        <!-- Suggestions Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-12 max-w-3xl mx-auto fade-in-up delay-3">
            <a href="/BuyMatch/public/matches/index.php" class="glass-effect rounded-2xl p-6 border border-slate-700/50 hover:border-emerald-500/30 transition-all duration-300 group">
                <div class="w-12 h-12 mx-auto mb-4 rounded-xl bg-gradient-to-br from-emerald-500/20 to-teal-500/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <p class="text-white font-bold text-sm group-hover:text-emerald-400 transition-colors">Accueil</p>
            </a>

            <a href="/BuyMatch/public/acheteur/dashboard.php" class="glass-effect rounded-2xl p-6 border border-slate-700/50 hover:border-blue-500/30 transition-all duration-300 group">
                <div class="w-12 h-12 mx-auto mb-4 rounded-xl bg-gradient-to-br from-blue-500/20 to-cyan-500/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <p class="text-white font-bold text-sm group-hover:text-blue-400 transition-colors">Mon Compte</p>
            </a>
        </div>

        <!-- Main Action Button -->
        <div class="fade-in-up delay-4">
            <a href="javascript:history.back()" class="relative inline-block group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 to-teal-500 transition-transform duration-300 group-hover:scale-105 rounded-2xl"></div>
                <div class="relative py-4 px-8 bg-gradient-to-r from-emerald-600 to-teal-600 group-hover:from-emerald-500 group-hover:to-teal-500 rounded-2xl shadow-lg shadow-emerald-500/30 group-hover:shadow-emerald-500/50 transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="text-white font-black uppercase tracking-wider">Retour</span>
                </div>
            </a>
        </div>

        <!-- Footer Info -->
        <div class="mt-12 fade-in-up delay-4">
            <div class="inline-flex items-center gap-2 glass-effect px-6 py-3 rounded-full border border-slate-700/50">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                <span class="text-slate-400 text-xs">Code erreur: 404 - Ressource non trouvée</span>
            </div>
        </div>

    </div>

</body>
</html>
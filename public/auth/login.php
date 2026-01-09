<?php

    session_start();

    require_once __DIR__ . '../../../config/Database.php';
    require_once __DIR__ . '../../../classes/Role.php';
    require_once __DIR__ . '../../../classes/Utilisateur.php';
    require_once __DIR__ . '../../../repositories/RoleRepository.php';
    require_once __DIR__ . '../../../repositories/UtilisateurRepository.php';
    require_once __DIR__ . '../../../services/AuthService.php';

    $error = null;

    if(isset($_POST['submit'])) {
        $email = trim($_POST['email']);
        $motDePasse = $_POST['password'];

        try {
            $authService = new AuthService();
            $user = $authService->login($email, $motDePasse);

            $_SESSION['user_id'] = $user->getId();
            $_SESSION['user_name'] = $user->getNom();
            $_SESSION['role'] = $user->getRole()->getNomRole();

            switch ($_SESSION['role']) {
                case 'Administrateur': 
                    header('Location: ../admin/dashboard.php');
                    break;
                case 'Organisateur': 
                    header('Location: ../organisateur/dashboard.php');
                    break;
                case 'Acheteur':
                    header('Location: ../acheteur/dashboard.php');
            }
            exit;

        } catch(Exception $e) {
            $error = $e->getMessage();
        }
    }

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BuyMatch</title>
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
    </style>
</head>
<body class="bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 min-h-screen flex items-center justify-center overflow-hidden">
    
    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/3 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl animate-float" style="animation-delay: 4s;"></div>
    </div>

    <div class="relative w-full max-w-md px-6">
        <!-- Logo/Brand -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-3 mb-4">
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-xl blur opacity-75"></div>
                    <div class="relative w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-2xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
                <h1 class="text-3xl font-black tracking-tight bg-gradient-to-r from-white to-emerald-400 bg-clip-text text-transparent">
                    BuyMatch
                </h1>
            </div>
            <p class="text-slate-400 text-sm">Connectez-vous à votre espace</p>
        </div>

        <!-- Card Container -->
        <div class="glass-effect rounded-2xl shadow-2xl p-8 relative">
            <!-- Gradient Border Effect -->
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/20 via-teal-500/20 to-blue-500/20 rounded-2xl blur opacity-50"></div>
            
            <div class="relative">
                <!-- Header -->
                <div class="mb-8">
                    <h2 class="text-3xl font-black text-white mb-2">Bon retour !</h2>
                    <p class="text-slate-400">Connectez-vous pour continuer</p>
                </div>

                <!-- Error Message -->
                <?php if ($error): ?>
                    <div class="mb-6 p-4 bg-red-500/10 border border-red-500/30 rounded-xl backdrop-blur">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12" y2="16"></line>
                            </svg>
                            <p class="text-red-300 text-sm"><?= htmlspecialchars($error) ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Form -->
                <form method="POST" class="space-y-5">
                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-bold text-slate-300 mb-2">
                            Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                    <path d="M3 7l9 6 9-6"></path>
                                </svg>
                            </div>
                            <input 
                                type="email" 
                                id="email"
                                name="email" 
                                required
                                class="w-full pl-12 pr-4 py-3 glass-effect border border-slate-700/50 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 transition"
                                placeholder="votre@email.com"
                            >
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-bold text-slate-300 mb-2">
                            Mot de passe
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <rect x="5" y="11" width="14" height="10" rx="2"></rect>
                                    <path d="M12 17a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"></path>
                                    <path d="M8 11V7a4 4 0 0 1 8 0v4"></path>
                                </svg>
                            </div>
                            <input 
                                type="password" 
                                id="password"
                                name="password" 
                                required
                                class="w-full pl-12 pr-4 py-3 glass-effect border border-slate-700/50 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 transition"
                                placeholder="••••••••"
                            >
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between pt-2">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-600 bg-slate-800/50 text-emerald-500 focus:ring-emerald-500/50 focus:ring-offset-0">
                            <span class="text-sm text-slate-400 group-hover:text-slate-300 transition">Se souvenir</span>
                        </label>
                        <a href="#" class="text-sm text-emerald-400 hover:text-emerald-300 font-semibold transition">
                            Mot de passe oublié ?
                        </a>
                    </div>

                    <!-- Login Button -->
                    <button 
                        type="submit" 
                        name="submit"
                        class="relative w-full group overflow-hidden mt-8"
                    >
                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 to-teal-500 transition-transform duration-300 group-hover:scale-105 rounded-xl"></div>
                        <div class="relative py-3.5 px-6 bg-gradient-to-r from-emerald-600 to-teal-600 group-hover:from-emerald-500 group-hover:to-teal-500 rounded-xl shadow-lg shadow-emerald-500/30 group-hover:shadow-emerald-500/50 transition-all">
                            <span class="text-white font-black text-sm uppercase tracking-wider">Se connecter</span>
                        </div>
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-700/50"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-slate-800/50 text-slate-500 font-medium">Nouveau sur BuyMatch ?</span>
                    </div>
                </div>

                <!-- Sign Up Link -->
                <a href="signup.php" class="block w-full text-center py-3.5 px-6 glass-effect border border-emerald-500/30 rounded-xl text-emerald-400 font-bold hover:bg-emerald-500/10 hover:border-emerald-500/50 transition-all">
                    Créer un compte
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-slate-500 text-xs">
                © 2025 BuyMatch. Tous droits réservés.
            </p>
        </div>
    </div>

</body>
</html>
<?php

    session_start();

    require_once '../config/Database.php';
    require_once '../classes/Role.php';
    require_once '../classes/Utilisateur.php';
    require_once '../repositories/RoleRepository.php';
    require_once '../repositories/UtilisateurRepository.php';
    require_once '../services/AuthService.php';

    $error = null;

    if(isset($_POST['submit'])) {
        $email = trim($_POST['email']);
        $motDePasse = $_POST['password'];

        try {
            $authService = new AuthService();
            $user = $authService->login($email, $motDePasse);

            $_SESSION['user_id'] = $user->getId();
            $_SESSION['role'] = $user->getRole()->getNomRole();

            switch ($_SESSION['role']) {
                case 'Administrateur': 
                    header('Location: ./admin/dashboard.php');
                    break;
                case 'Organisateur': 
                    header('Location: ./organisateur/dashboard.php');
                    break;
                case 'Acheteur':
                    header('Location: ./acheteur/dashboard.php');
            }
            exit;

        } catch(Exception $e) {
            $error = $e->getMessage();
        }
    }

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-50 to-slate-100 min-h-screen flex items-center justify-center">
    
    <div class="w-full max-w-md">
        <!-- Card Container -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-slate-900">Welcome Back</h1>
                <p class="text-slate-600 mt-2">Sign in to your account</p>
            </div>

            <!-- Error Message -->
            <?php if ($error): ?>
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-red-700 text-sm"><?= htmlspecialchars($error) ?></p>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form method="POST" class="space-y-5">
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                        Email Address
                    </label>
                    <input 
                        type="email" 
                        id="email"
                        name="email" 
                        required
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="you@example.com"
                    >
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-2">
                        Password
                    </label>
                    <input 
                        type="password" 
                        id="password"
                        name="password" 
                        required
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="••••••••"
                    >
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="remember" class="rounded border-slate-300">
                        <span class="text-sm text-slate-600">Remember me</span>
                    </label>
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                        Forgot password?
                    </a>
                </div>

                <!-- Login Button -->
                <button 
                    type="submit" 
                    name="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition duration-200 mt-6"
                >
                    Sign In
                </button>
            </form>


            <!-- Sign Up Link -->
            <p class="text-center text-slate-600 mt-6">
                Don't have an account? 
                <a href="signup.php" class="text-blue-600 hover:text-blue-700 font-medium">
                    Sign up
                </a>
            </p>
        </div>
    </div>

</body>
</html>


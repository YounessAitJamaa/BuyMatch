<?php

    session_start();

    require_once '../config/Database.php';
    require_once '../classes/Role.php';
    require_once '../classes/Utilisateur.php';
    require_once '../repositories/RoleRepository.php';
    require_once '../repositories/UtilisateurRepository.php';
    require_once '../services/AuthService.php';

    $error = null;
    $success = null;

    if(isset($_POST['submit'])) {

        $nom = trim($_POST['nom']);
        $email = trim($_POST['email']);
        $motDePasse = $_POST['password'];

        try {

            $authServise = new AuthService();
            $authServise->singup($nom, $email, $motDePasse, 1);

            $success = "Account created successfully. You can now login.";
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
    <title>Sign Up</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-50 to-slate-100 min-h-screen flex items-center justify-center">
    
    <div class="w-full max-w-md">
        <!-- Card Container -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-slate-900">Create Account</h1>
                <p class="text-slate-600 mt-2">Join us today to get started</p>
            </div>

            <!-- Error Message -->
            <?php if ($error): ?>
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-red-700 text-sm"><?= htmlspecialchars($error) ?></p>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form method="POST" class="space-y-5">
                <!-- Full Name Field -->
                <div>
                    <label for="fullname" class="block text-sm font-medium text-slate-700 mb-2">
                        Full Name
                    </label>
                    <input 
                        type="text" 
                        id="fullname"
                        name="nom" 
                        required
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="John Doe"
                    >
                </div>

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
                    <p class="text-xs text-slate-600 mt-1">At least 8 characters</p>
                </div>


                <!-- Sign Up Button -->
                <button 
                    type="submit" 
                    name="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition duration-200 mt-6"
                >
                    Create Account
                </button>
            </form>


            <!-- Login Link -->
            <p class="text-center text-slate-600 mt-6">
                Already have an account? 
                <a href="login.php" class="text-blue-600 hover:text-blue-700 font-medium">
                    Sign in
                </a>
            </p>
        </div>
    </div>

</body>
</html>


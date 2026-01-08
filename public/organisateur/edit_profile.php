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

    if(isset($_POST['submit'])) 
    {
        $nom = trim($_POST['nom']);
        $photo = trim($_POST['photo']);

        if($userRepo->updateProfileUrl($organisateurId, $nom, $photo)) {

            $_SESSION['user_name'] = $nom;
            header('Location: profile.php?success=Profil Mis a jour');
        }else {
            header('Location: edit_profile.php?error=Error lors de la mise a jour');
        }
        exit;

    }

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier mon profil - BuyMatch</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-screen p-6 md:p-12">

<!-- Navigation Bar -->
<div class="fixed top-0 left-0 right-0 bg-slate-900/80 backdrop-blur-md border-b border-slate-700/30 z-50">
    <div class="max-w-7xl mx-auto px-6 md:px-12 py-4 flex items-center justify-between">
        <h1 class="text-xl font-black text-white">BuyMatch</h1>
        <div class="flex items-center gap-4">
            <a href="profile.php" class="text-slate-400 hover:text-emerald-500 transition font-semibold">Profil</a>
        </div>
    </div>
</div>

<div class="pt-24 max-w-3xl mx-auto">

    <!-- Back Link -->
    <a href="profile.php"
       class="text-slate-400 hover:text-emerald-500 font-bold flex items-center gap-2 mb-8 transition">
        ← Retour au profil
    </a>

    <?php if(isset($_GET['error'])): ?>
        <div class="p-4 bg-red-500/10 border border-red-500/30 rounded-xl text-red-400 text-sm font-medium"><?= $error ?></div>
    <?php endif; ?>

    <!-- Updated card styling to match profile design with enhanced glassmorphism -->
    <div class="bg-slate-800/40 backdrop-blur-xl rounded-2xl shadow-2xl border border-slate-700/50 overflow-hidden">

        <!-- Header -->
        <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-800 p-8 border-b border-slate-700/50">
            <h1 class="text-4xl font-black text-white">Modifier le profil</h1>
            <p class="text-slate-400 text-sm mt-2">
                Mettez à jour vos informations personnelles
            </p>
        </div>

        <!-- Form -->
        <form method="POST" class="p-8 space-y-8">

            <!-- Profile photo section -->
            <div class="flex flex-col md:flex-row items-start md:items-center gap-8">
                <div class="relative group">
                    <!-- Added id="profileImage" for JavaScript real-time updates -->
                    <img
                        id="profileImage"
                        src="<?= (strpos($user->getPhoto(), 'http') === 0) ? htmlspecialchars($user->getPhoto()) : '../../includes/assests/' . htmlspecialchars($user->getPhoto()) ?>"
                        class="w-40 h-40 rounded-2xl object-cover border-4 border-emerald-500/30 shadow-lg"
                        alt="Photo de profil"
                        onerror="this.src='../../includes/assests/default-avatar.jpg'"
                    >
                    <div class="absolute inset-0 rounded-2xl bg-emerald-500/10 opacity-0 group-hover:opacity-100 transition"></div>
                </div>

                <div class="flex-1 space-y-3">
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">
                        URL de la photo
                    </label>
                    <!-- Added id="photoInput" and oninput event listener for real-time image preview -->
                    <input
                        id="photoInput"
                        type="url"
                        name="photo"
                        value="<?= htmlspecialchars($user->getPhoto()) ?>"
                        class="w-full bg-slate-900/50 border border-slate-600/50 rounded-xl px-4 py-3
                               text-slate-100 placeholder-slate-500 focus:ring-2 focus:ring-emerald-500 
                               focus:border-emerald-500 outline-none transition"
                        placeholder="https://example.com/avatar.jpg"
                        oninput="updateImagePreview()"
                    >
                    <p class="text-xs text-slate-400 mt-2">
                        L'image se mettra à jour automatiquement
                    </p>
                </div>
            </div>
            
            <!-- Divider -->
            <div class="border-t border-slate-700/50"></div>

            <!-- Name Field -->
            <div class="space-y-3">
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">
                    Nom complet
                </label>
                <input
                    type="text"
                    name="nom"
                    value="<?= htmlspecialchars($user->getNom()) ?>"
                    required
                    class="w-full bg-slate-900/50 border border-slate-600/50 rounded-xl px-4 py-3
                           text-slate-100 placeholder-slate-500 focus:ring-2 focus:ring-emerald-500 
                           focus:border-emerald-500 outline-none transition"
                >
            </div>

            <!-- Email Field (readonly) -->
            <div class="space-y-3">
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">
                    Email (non modifiable)
                </label>
                <input
                    type="email"
                    value="<?= htmlspecialchars($user->getEmail()) ?>"
                    disabled
                    class="w-full bg-slate-800/30 border border-slate-700/50 rounded-xl px-4 py-3
                           text-slate-400 cursor-not-allowed"
                >
            </div>

            <!-- Enhanced action buttons with improved styling and spacing -->
            <div class="pt-8 flex justify-end gap-4 border-t border-slate-700/50">
                <a href="profile.php"
                   class="px-6 py-3 bg-slate-700/50 hover:bg-slate-700 text-slate-100 border border-slate-600/50
                          font-bold rounded-xl transition shadow-lg hover:shadow-xl">
                    Annuler
                </a>

                <button type="submit"
                        name="submit"
                        class="px-8 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 
                               hover:to-emerald-800 text-white font-black rounded-xl shadow-lg 
                               shadow-emerald-500/30 hover:shadow-emerald-500/50 transition">
                    💾 Enregistrer
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    function updateImagePreview() {
        const photoInput = document.getElementById('photoInput');
        const profileImage = document.getElementById('profileImage');
        
        if (photoInput.value) {
            profileImage.src = photoInput.value;
            profileImage.onerror = function() {
                this.src = '../../includes/assets/default_user.jpg';
            };
        }
    }
</script>

</body>
</html>

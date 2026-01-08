<?php
    session_start();
    require_once '../../repositories/UtilisateurRepository.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Administrateur') {
        header('Location: ../login.php');
        exit;
    }

    $userRepo = new UtilisateurRepository();

    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

    $user = $userRepo->findById($id);

    if (!$user || $id === $_SESSION['user_id']) {
        header('Location: index.php');
        exit;
    }

    if (isset($_POST['submit'])) {
        $newRoleId = (int)$_POST['role_id'];
        $userRepo->updateRole($id, $newRoleId);
        header('Location: gerer_users.php?success=role_updated');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-900 flex items-center justify-center min-h-screen">
    <div class="bg-slate-800 p-8 rounded-2xl border border-slate-700 w-full max-w-md">
        <h2 class="text-white text-xl font-black mb-4">Modifier le rôle</h2>
        <p class="text-slate-400 text-sm mb-6">Changer le rôle de : <span class="text-emerald-500 font-bold"><?= htmlspecialchars($user->getNom()) ?></span></p>
        
        <form method="POST">
            <select name="role_id" class="w-full bg-slate-900 text-white p-3 rounded-lg border border-slate-700 mb-6 outline-none focus:border-emerald-500">
                <option value="1" <?= $user->getRole()->getId() == 1 ? 'selected' : '' ?>>Administrateur</option>
                <option value="2" <?= $user->getRole()->getId() == 2 ? 'selected' : '' ?>>Organisateur</option>
                <option value="3" <?= $user->getRole()->getId() == 3 ? 'selected' : '' ?>>Utilisateur</option>
            </select>
            
            <div class="flex gap-3">
                <a href="gerer_users.php" class="flex-1 text-center py-3 text-slate-400 hover:text-white transition-colors">Annuler</a>
                <button type="submit" name="submit" class="flex-1 bg-emerald-600 py-3 rounded-lg text-white font-bold hover:bg-emerald-700 transition-all">Enregistrer</button>
            </div>
        </form>
    </div>
</body>
</html>
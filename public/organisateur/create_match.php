<?php
    
    
    session_start();

    require_once '../../repositories/EquipeRepository.php';
    require_once '../../repositories/MatchRepository.php';

    if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Organisateur') {
        header('Location: ../login.php');
        exit;
    }

    $error = '';
    $success = '';

    if(isset($_GET['error'])) {
        $error = $_GET['error'];
    }

    if(isset($_GET['success'])) {
        $error = $_GET['success'];
    }

    if (isset($_POST['submit'])) {
        try {

            $equipeRepo = new EquipeRepository();
            $matchRepo = new MatchRepository();

            $logo1 = 'default.png';
            $logo2 = 'default.png';
 
            if(isset($_FILES['equipe1_logo']) && $_FILES['equipe1_logo']['error'] === 0){
                $logo1 = $_FILES['equipe1_logo']['name'];
                move_uploaded_file($_FILES['equipe1_logo']['tmp_name'], '../uploads/' . $logo1); 
            }

            if(isset($_FILES['equipe2_logo']) && $_FILES['equipe2_logo']['error'] === 0){
                $logo2 = $_FILES['equipe2_logo']['name'];
                move_uploaded_file($_FILES['equipe2_logo']['tmp_name'], '../uploads/' . $logo2); 
            }   

            $idEquipe1 = $equipeRepo->findOrCreateByName($_POST['equipe1_nom'], $logo1);
            $idEquipe2 = $equipeRepo->findOrCreateByName($_POST['equipe2_nom'], $logo2);

            $categories = [];
            foreach($_POST['cat_nom'] as $key => $nom) {
                if(!empty($nom)) {
                    $categories[] = [
                        'nom' => $nom,
                        'prix' => (float)$_POST['cat_prix'][$key],
                        'nb_places' => (int)$_POST['cat_places'][$key]
                    ];
                }
            }

            $res = $matchRepo->createFromForm([
                'date_heure' => $_POST['date'] . ' ' . $_POST['heure'],
                'lieu' => $_POST['lieu'],
                'duree' => $_POST['duree'],
                'organisateur_id' => $_SESSION['user_id'],
                'equipe1_id' => $idEquipe1,
                'equipe2_id' => $idEquipe2,
                'categories' => $categories
            ]);

            if($res) {
                $success = "Match cree avec succes et en attente de validation !";
            }
        
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Événement - BuyMatch</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-screen p-6 md:p-12">

    <div class="max-w-4xl mx-auto">
        <a href="dashboard.php" class="text-slate-400 hover:text-emerald-500 font-bold flex items-center gap-2 mb-8 transition-colors">
            ← Retour au tableau de bord
        </a>

        <div class="bg-slate-800/50 backdrop-blur rounded-2xl shadow-2xl border border-slate-700/50 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-900 to-slate-800 p-8 text-white border-b border-slate-700">
                <h1 class="text-3xl font-black tracking-tight">Nouvel Événement Sportif</h1>
                <p class="text-slate-400 mt-2 text-sm">Remplissez les détails pour soumettre votre match à la validation.</p>
            </div>

            <form method="POST" enctype="multipart/form-data" class="p-8 space-y-10">
                <?php if($error): ?>
                    <div class="p-4 bg-red-500/10 border border-red-500/30 rounded-xl text-red-400 text-sm font-medium"><?= $error ?></div>
                <?php endif; ?>
                <?php if($success): ?>
                    <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-xl text-emerald-400 text-sm font-medium"><?= $success ?></div>
                <?php endif; ?>

                <section>
                    <h2 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6">Affiche du Match</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <!-- Team 1 Logo Section -->
                        <div class="space-y-4">
                            <label class="block text-xs font-bold text-slate-300 uppercase">Équipe Domicile</label>
                            <input type="text" name="equipe1_nom" required placeholder="Nom de l'équipe" 
                                class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2 text-slate-100 placeholder-slate-500 outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                            
                            <div class="flex items-end gap-4">
                                <!-- Added image preview container for team 1 -->
                                <div class="shrink-0 h-24 w-24 bg-slate-700/30 rounded-xl flex items-center justify-center text-slate-500 border border-dashed border-slate-600 overflow-hidden" id="preview1">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                                <label class="cursor-pointer bg-emerald-600 hover:bg-emerald-700 border border-emerald-500 px-4 py-2 rounded-lg text-xs font-bold text-white transition-all hover:shadow-lg hover:shadow-emerald-500/20">
                                    Sélectionner
                                    <input type="file" name="equipe1_logo" class="hidden" accept="image/*" onchange="previewImage(event, 'preview1')">
                                </label>
                            </div>
                        </div>

                        <!-- Team 2 Logo Section -->
                        <div class="space-y-4">
                            <label class="block text-xs font-bold text-slate-300 uppercase">Équipe Extérieur</label>
                            <input type="text" name="equipe2_nom" required placeholder="Nom de l'équipe" 
                                class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2 text-slate-100 placeholder-slate-500 outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                            
                            <div class="flex items-end gap-4">
                                <!-- Added image preview container for team 2 -->
                                <div class="shrink-0 h-24 w-24 bg-slate-700/30 rounded-xl flex items-center justify-center text-slate-500 border border-dashed border-slate-600 overflow-hidden" id="preview2">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                                <label class="cursor-pointer bg-emerald-600 hover:bg-emerald-700 border border-emerald-500 px-4 py-2 rounded-lg text-xs font-bold text-white transition-all hover:shadow-lg hover:shadow-emerald-500/20">
                                    Sélectionner
                                    <input type="file" name="equipe2_logo" class="hidden" accept="image/*" onchange="previewImage(event, 'preview2')">
                                </label>
                            </div>
                        </div>

                    </div>
                </section>

                <section>
                    <h2 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6">Logistique & Lieu</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-300 mb-2 uppercase">Stade / Lieu</label>
                            <input type="text" name="lieu" required placeholder="ex: Parc des Princes" class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-slate-100 placeholder-slate-500 outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-300 mb-2 uppercase">Durée (min)</label>
                            <input type="number" name="duree" value="90" required class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-slate-100 outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-300 mb-2 uppercase">Date</label>
                            <input type="date" name="date" required class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-slate-100 outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-300 mb-2 uppercase">Heure</label>
                            <input type="time" name="heure" required class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-slate-100 outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                        </div>
                    </div>
                </section>

                <section class="bg-slate-700/30 p-6 rounded-xl border border-slate-600">
                    <h2 class="text-sm font-black text-slate-100 uppercase tracking-widest mb-6">Configuration des Billets (Max 3)</h2>
                    <div class="space-y-4">
                        <?php for($i=1; $i<=3; $i++): ?>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pb-4 border-b border-slate-600 last:border-0">
                            <input type="text" name="cat_nom[]" placeholder="Nom (ex: VIP)" class="bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2 text-sm text-slate-100 placeholder-slate-500 outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                            <input type="number" name="cat_prix[]" step="0.01" placeholder="Prix (€)" class="bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2 text-sm text-slate-100 placeholder-slate-500 outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                            <input type="number" name="cat_places[]" placeholder="Places" class="bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2 text-sm text-slate-100 placeholder-slate-500 outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                        </div>
                        <?php endfor; ?>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-4 font-bold">⚠️ Le total des places ne doit pas dépasser 2000.</p>
                </section>

                <div class="pt-6 flex justify-end gap-4">
                    <button type="reset" class="px-8 py-3 bg-slate-700 hover:bg-slate-600 text-slate-100 font-bold rounded-lg transition-all">
                        Réinitialiser
                    </button>
                    <button type="submit" name="submit" class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-lg shadow-lg shadow-emerald-500/20 transition-all hover:shadow-emerald-500/40">
                        Soumettre la demande
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Added JavaScript for image preview functionality -->
    <script>
        function previewImage(event, previewId) {
            const file = event.target.files[0];
            const previewElement = document.getElementById(previewId);
            
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewElement.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover rounded-lg">`;
                };
                
                reader.readAsDataURL(file);
            }
        }
    </script>

</body>
</html>

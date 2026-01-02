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

        } catch(PDOException $e) {
            $error = $e->getMessage();
        }
    }

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>

<h2>Login</h2>

<?php if ($error): ?>
    <p style="color:red"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST">
    <label>Email</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit" name="submit">Login</button>
</form>

</body>
</html>

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
        } catch(PDOException $e) {
            $error = $e->getMessage();
        }
    }   


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup</title>
</head>
<body>

<h2>Signup</h2>

<?php if ($error): ?>
    <p style="color:red"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if ($success): ?>
    <p style="color:green"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<form method="POST">
    <label>Name</label><br>
    <input type="text" name="nom" required><br><br>

    <label>Email</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit" name="submit">Sign Up</button>
</form>

<p>Already have an account? <a href="login.php">Login here</a></p>

</body>
</html>

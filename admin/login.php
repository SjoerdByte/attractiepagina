<?php
session_start();
require_once 'backend/config.php';
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <title>Attractiepagina / Admin</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@400;600;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo $base_url; ?>/css/normalize.css" />
    <link rel="stylesheet" href="<?php echo $base_url; ?>/css/main.css" />
    <link rel="icon" href="<?php echo $base_url; ?>/favicon.ico" type="image/x-icon" />
</head>

<body>
<?php require_once '../header.php'; ?>
<div class="centerLogin">
    <div class="container">
        <?php
        if(isset($_GET['msg']))
        {
            echo "<div class='msg'>" . $_GET['msg'] . "
        </div>
        "; } ?>

        <form action="backend/loginController.php" method="POST">
            <div class="form-group">
                <label for="username">Gebruikersnaam:</label>
                <input type="text" name="username" id="username" placeholder="user1 t/m 3" />
            </div>
            <div class="form-group">
                <label for="password">Wachtwoord:</label>
                <input type="password" name="password" id="password" placeholder="pass1 t/m 3" />
            </div>

            <input class="loginButton" type="submit" value="Login" />
        </form>
    </div>
</div>
</body>
</html>

<?php
session_start();
//checks if the user is logged in or not
if (isset($_SESSION['loggedInUser'])) {
    $login = true;
} else {
    $login = false;
}

/** @var mysqli $db */
require_once "includes/database.php";
//this part is where the login happens
if (isset($_POST['submit'])) {
    $email = mysqli_escape_string($db, $_POST['email']);
    $password = $_POST['password'];
    //error handling
    $errors = [];
    if ($email == '') {
        $errors['email'] = 'Voer een gebruikersnaam in';
    }
    if ($password == '') {
        $errors['password'] = 'Voer een wachtwoord in';
    }

    if (empty($errors)) {
        //Get record from DB based on first name
        $query = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($db, $query);
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {
                $login = true;

                $_SESSION['loggedInUser'] = [
                    'email' => $user['email'],
                    'id' => $user['id']
                ];
            } else {
                //error incorrect login information
                $errors['loginFailed'] = 'De combinatie van email en wachtwoord is bij ons niet bekend';
            }
        } else {
            //error incorrect login information
            $errors['loginFailed'] = 'De combinatie van email en wachtwoord is bij ons niet bekend';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <title>Login</title>
</head>
<nav>
    <div class="navigation">
        <button class="button"><a class="link" href="index.php">Home</a></button>
        <button class="button"><a class="link" href="over.php">Over</a></button>
        <button class="button"><a class="link" href="services.php">Services</a></button>
        <button class="button"><a class="link" href="reservation.php">Afspraak maken</a></button>
        <button class="button"><a class="link" href="contact.php">Contact</a></button>
        <button class="buttonactive"><a class="link" href="login.php">Login</a></button>
    </div>
</nav>
<body>
<section>
    <h2>Inloggen</h2>
    <?php if ($login) { ?>
        <p>Je bent ingelogd!</p>
        <p><a href="logout.php">Uitloggen</a> / <a href="index.php">Naar home page</a></p>
    <?php } else { ?>
        <form action="" method="post">
            <div>
                <label for="email">Email</label>
                <input id="email" type="text" name="email" value="<?= $email ?? 'jondoe@example.com' ?>"/>
                <span class="errors"><?= $errors['email'] ?? '' ?></span>
            </div>
            <div>
                <label for="password">Wachtwoord</label>
                <input id="password" type="password" name="password"/>
                <span class="errors"><?= $errors['password'] ?? '' ?></span>
            </div>
            <div>
                <p class="errors"><?= $errors['loginFailed'] ?? '' ?></p>
                <input type="submit" name="submit" value="Login"/>
            </div>
        </form>
    <?php } ?>
    <a href="register.php">Registreren</a>
</section>
</body>
</html>

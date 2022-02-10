<?php

if (isset($_POST['submit'])) {
    require_once "includes/database.php";

    /** @var mysqli $db */

    $email = mysqli_escape_string($db, $_POST['email']);
    $password = $_POST['password'];

    $errors = [];
    if ($email == '') {
        $errors['email'] = 'Voer een gebruikersnaam in';
    }
    if ($password == '') {
        $errors['password'] = 'Voer een wachtwoord in';
    }
    //if there are no errors parse the email and password
    if (empty($errors)) {
        //hash the password to prevent rainbow table attacks
        $password = password_hash($password, PASSWORD_DEFAULT);
        $userID = rand(1, 999999999);
        $query = "INSERT INTO users (email, password, id) VALUES ('$email', '$password','$userID')";

        $result = mysqli_query($db, $query)
        or die('Db Error: ' . mysqli_error($db) . ' with query: ' . $query);
        //if everything goes wel go back to the login page
        if ($result) {
            header('Location: login.php');
            exit;
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
    <title>Registreren</title>
</head>
<nav>
    <div class="navigation">
        <button class="buttonactive"><a class="link" href="index.php">Home</a></button>
        <button class="button"><a class="link" href="over.php">Over</a></button>
        <button class="button"><a class="link" href="services.php">Services</a></button>
        <button class="button"><a class="link" href="reservation.php">Afspraak maken</a></button>
        <button class="button"><a class="link" href="contact.php">Contact</a></button>
        <button class="button"><a class="link" href="login.php">Login</a></button>
    </div>
</nav>
<body>
<h2>Nieuwe gebruiker registeren</h2>
<form action="" method="post">
    <div class="data-field">
        <label for="email">Email</label>
        <input id="email" type="text" name="email" value="<?= $email ?? '' ?>"/>
        <span class="errors"><?= $errors['email'] ?? '' ?></span>
    </div>
    <div class="data-field">
        <label for="password">Wachtwoord</label>
        <input id="password" type="password" name="password" value="<?= $password ?? '' ?>"/>
        <span class="errors"><?= $errors['password'] ?? '' ?></span>
    </div>
    <div class="data-submit">
        <input type="submit" name="submit" value="Registreren"/>
    </div>
</form>

</body>
</html>

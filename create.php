<?php
/** @var mysqli $db */

//Check if Post isset, else do nothing
session_start();

//May I even visit this page?
if (!isset($_SESSION['loggedInUser'])) {
    header("Location: login.php");
    exit;
}


//Get email from session
$email = $_SESSION['loggedInUser']['email'];
if (isset($_POST['submit'])) {
    //Require database in this file & image helpers
    require_once "includes/database.php";
    require_once "includes/image-helpers.php";

    //Postback with the data showed to the user, first retrieve data from 'Super global'
    $description = mysqli_escape_string($db, $_POST['description']);
    $platform = mysqli_escape_string($db, $_POST['platform']);
    $fulldesc = mysqli_escape_string($db, $_POST['fulldesc']);
    $image = mysqli_escape_string($db, $_POST['image']);
    $date = mysqli_escape_string($db, $_POST['date']);
    $reservationId = mysqli_escape_string($db, rand());
    $userid = mysqli_escape_string($db, $email);

    //Require the form validation handling
    require_once "includes/form_validation.php";

    //Special check for add form only
    if ($_FILES['image']['error'] == 4) {
        $errors['image'] = 'Image cannot be empty';
    }

    if (empty($errors)) {
        //Store image & retrieve name for database saving
        $image = addImageFile($_FILES['image']);

        //Save the record to the database
        $query = "INSERT INTO reservations (id,description,platform,fulldesc,media,date,email)
                  VALUES ('$reservationId', '$description', '$platform', '$fulldesc', '$image','$date','$userid')";
        $result = mysqli_query($db, $query) or die('Error: ' . mysqli_error($db) . ' with query ' . $query);

        //If everything works it will redirect to the read page, else it will throw an error
        if ($result) {
            header('Location: reservation.php');
            exit;
        } else {
            $errors['db'] = 'Something went wrong in your database query: ' . mysqli_error($db);
        }

        //Close connection
        mysqli_close($db);
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <title>Reservation Create</title>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>
<body>
<nav>
    <div class="navigation">
        <button class="button"><a class="link" href="index.php">Home</a></button>
        <button class="button"><a class="link" href="over.php">Over</a></button>
        <button class="button"><a class="link" href="services.php">Services</a></button>
        <button class="buttonactive"><a class="link" href="reservation.php">Afspraak maken</a></button>
        <button class="button"><a class="link" href="contact.php">Contact</a></button>
        <button class="button"><a class="link" href="login.php">Login</a></button>
    </div>
</nav>
<main>
    <section>
        <h1>Maak hier je reservering.</h1>
        <?php if (isset($errors['db'])) { ?>
            <div><span class="errors"><?= $errors['db']; ?></span></div>
        <?php } ?>

        <!-- enctype="multipart/form-data" no characters will be converted.
        we'll be using hmtlentities to prevent XSS attacks
        -->
        <form action="" method="post" enctype="multipart/form-data">
            <div class="data-field">
                <label for="description">Korte beschrijving probleem</label>
                <input id="description" type="text" name="description"
                       value="<?= isset($description) ? htmlentities($description) : 'Vul hier een korte beschrijving in' ?>"/>
                <span class="errors"><?= $errors['description'] ?? '' ?></span>
            </div>
            <!-- This was originally meant to be a dropdown menu. I will properly deal with that later,
            this will need to suffice for now. -->
            <div class="data-field">
                <label for="platform">Platform</label>
                <input id="platform" type="text" name="platform" list="platform-name">
                <datalist id="platform-name">
                    <option value="<?= isset($platform) ? htmlentities($platform) : 'Computer' ?>"/>
                    <option value="<?= isset($platform) ? htmlentities($platform) : 'Laptop' ?>"/>
                    <option value="<?= isset($platform) ? htmlentities($platform) : 'iMac' ?>"/>
                    <option value="<?= isset($platform) ? htmlentities($platform) : 'MacBook' ?>"/>
                </datalist>
                <span class="errors"><?= isset($errors['platform']) ? $errors['platform'] : '' ?></span>
            </div>
            <br>
            <div class="data-field">
                <label for="fulldesc">Volledige beschrijving probleem</label>
                <br>
                <textarea id="fulldesc" name="fulldesc" rows="5" cols="60">
            <?= isset($fulldesc) ? htmlentities($fulldesc) : 'Vul hier een beknopte beschrijving van uw probleem in.' ?>
        </textarea>
                <span class="errors"><?= isset($errors['fulldesc']) ? $errors['fulldesc'] : '' ?></span>
            </div>
            <br>
            <div class="data-field">
                <label for="date">Datum</label>
                <input id="date" type="date" name="date" value="<?= isset($date) ? htmlentities($date) : '' ?>"/>
                <span class="errors"><?= $errors['date'] ?? '' ?></span>
            </div>
            <div class="data-field">
                <label for="image">Foto van probleem</label>
                <input type="file" name="image" id="image"/>
                <span class="errors"><?= isset($errors['image']) ? $errors['image'] : '' ?></span>
            </div>
            <div class="data-submit">
                <input type="submit" name="submit" value="Save"/>
            </div>
        </form>
        <div>
            <a href="index.php">Go back to the list</a>
        </div>
    </section>
</main>
</body>
</html>

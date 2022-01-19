<?php
/** @var mysqli $db */

//Check if Post isset, else do nothing
// redirect when uri does not contain a id
if (!isset($_GET['id']) || $_GET['id'] == '') {
    // redirect to index.php
    header('Location: index.php');
    exit;
}

//Require database in this file
require_once "includes/database.php";

//Retrieve the GET parameter from the 'Super global'
$reservationId = mysqli_escape_string($db, $_GET['id']);

//Get the record from the database result
$query = "SELECT * FROM reservations WHERE id = '$reservationId'";
$result = mysqli_query($db, $query)
or die ('Error: ' . $query);

if (mysqli_num_rows($result) != 1) {
    // redirect when db returns no result
    header('Location: index.php');
    exit;
}

$reservation = mysqli_fetch_assoc($result);

//Close connection
mysqli_close($db);
?>
<!doctype html>
<html lang="en">
<head>
    <title>Reservering Details</title>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>
<body>
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
<main>
    <section>
        <h1><?= $reservation['description'] . ' - platform: ' . $reservation['platform'] ?></h1>
        <div>
            <img src="images/<?= $reservation['media'] ?>" alt=""/>
            <p>
                <?= $reservation['fulldesc'] ?>
            </p>
        </div>
    </section>
</main>
<div>
    <a href="index.php">Go back to the list</a>
</div>
</body>
</html>

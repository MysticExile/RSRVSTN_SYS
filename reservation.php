<?php
session_start();
/** @var mysqli $db */

//May I even visit this page?
if (!isset($_SESSION['loggedInUser'])) {
    header("Location: login.php");
    exit;
}
// Connection data
require_once "includes/database.php";
// Look at all the reservations
$query = "SELECT * FROM reservations";
$result = mysqli_query($db, $query)
or die('Error ' . mysqli_error($db) . ' with query ' . $query);
$reservations = [];
//turn the table into an array so you can turn it into an html list
while ($row = mysqli_fetch_assoc($result)) {
    $reservations[] = $row;
}
mysqli_close($db);
?>
<!doctype html>
<html lang="en">
<head>
    <title>Reserveren</title>
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
        <h1>Mijn Reserveringen</h1>
        <a href="create.php">Maak een reservering</a>
        <table>
            <thead>
            <tr>
                <th>Reserverings Nummer</th>
                <th>Beschrijving</th>
                <th>Platform</th>
                <th>Datum</th>
                <th colspan="2"></th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td colspan="9">&copy; Jeffrey's Computer Assistance</td>
            </tr>
            </tfoot>
            <tbody>
            <?php foreach ($reservations as $reservation) { ?>
                <tr>
                    <td><?= $reservation['id'] ?></td>
                    <td><?= $reservation['description'] ?></td>
                    <td><?= $reservation['platform'] ?></td>
                    <td><?= $reservation['date'] ?></td>
                    <td><a href="detail.php?id=<?= $reservation['id'] ?>">Details</a></td>
                    <td><a href="edit.php?id=<?= $reservation['id'] ?>">Edit</a></td>
                    <td><a href="delete.php?id=<?= $reservation['id'] ?>">Delete</a></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </section>
</main>
</body>
</html>

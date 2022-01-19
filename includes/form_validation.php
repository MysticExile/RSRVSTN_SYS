<?php
//Check if data is valid & generate error if not so
$errors = [];
if ($description == "") {
    $errors['description'] = 'Korte Beschrijving kan niet leeg zijn.';
}
if ($platform == "") {
    $errors['platform'] = 'Platform kan niet leeg zijn.';
}
if ($fulldesc == "") {
    $errors['fulldesc'] = 'Beschrijving kan niet leeg zijn.';
}
if ($date == "") {
    $errors['date'] = 'Datum kan niet leeg zijn.';
}

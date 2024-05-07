<?php
include "connect.php";
//https://3b75-41-186-78-232.ngrok-free.app/ussdsms/index.php
include_once 'hub.php';

$sessionId = $_POST['sessionId'];
$phoneNumber = $_POST['phoneNumber'];
$serviceCode = $_POST['serviceCode']; // Corrected assignment

$text = $_POST['text'];

$isRegistered = true;

$menu = new menu($text, $sessionId); // corrected class name to lowercase 'menu'

if ($text == "" && $isRegistered) {
    // Do something
    $menu->mainMenuRegistered();
} elseif ($text == "" && !$isRegistered) { // Adjusted condition
    // Do something
    $menu->mainMenuUnregistered();
} elseif (!$isRegistered) {
    // Do something
    $textArray = explode("*", $text);
    switch ($textArray[0]) {
        case '1': // Corrected string comparison
            $menu->MenuRegistered($textArray); // Corrected method name
            break;
        default:
            echo "END Invalid Option, Retry";
    }
} else {
    // Do something
    $textArray = explode("*", $text);
    switch ($textArray[0]) {
        case '1': // Corrected string comparison
            $menu->request($textArray); // Corrected method name
            break;
        case '2': // Corrected string comparison
            $menu->accountstatus($textArray); // Corrected method name
            break;
        case '3': // Corrected string comparison
            $menu->changepassword($textArray); // Corrected method name
            break;
        default:
            echo "END Invalid Choice\n";
    }
}


?>
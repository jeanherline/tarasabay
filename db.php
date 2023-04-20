<?php

$db = new mysqli('localhost', 'root', '', 'carpool');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
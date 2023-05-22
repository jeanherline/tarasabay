<?php

$db = new mysqli('localhost', 'id20698788_tarasabay', 'TaraSabay!2023', 'id20698788_carpool');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
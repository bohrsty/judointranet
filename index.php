<?php

/*
 * lib einlesen
 */
require('lib/functions.php');

/*
 * konfig einlesen
 */
init();

/*
 * session starten
 */
session_start();

$main_view = new MainView();

$main_view->init();

$main_view->output();



?>

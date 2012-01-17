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

$calendar_view = new CalendarView();

$calendar_view->init();

$calendar_view->output();




?>

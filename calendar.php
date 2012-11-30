<?php

/*
 * required lib
 */
require('lib/functions.php');

/*
 * start session
 */
session_start();

$calendar_view = new CalendarView();

$calendar_view->init();




?>

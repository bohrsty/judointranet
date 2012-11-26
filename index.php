<?php

/*
 * required lib
 */
require('lib/functions.php');

/*
 * start session
 */
session_start();

$main_view = new MainView();

$main_view->init();



?>

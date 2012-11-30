<?php

/*
 * required lib
 */
require('lib/functions.php');

/*
 * start session
 */
session_start();

$administration_view = new AdministrationView();

$administration_view->init();



?>
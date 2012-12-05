<?php

/*
 * required lib
 */
require('lib/functions.php');

/*
 * start session
 */
session_start();

$announcement_view = new InventoryView();

$announcement_view->init();



?>
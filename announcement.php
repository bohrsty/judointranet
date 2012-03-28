<?php

/*
 * required lib
 */
require('lib/functions.php');

/*
 * start session
 */
session_start();

$announcement_view = new AnnouncementView();

$announcement_view->init();

$announcement_view->output();



?>
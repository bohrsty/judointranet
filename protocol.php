<?php

/*
 * required lib
 */
require('lib/functions.php');

/*
 * start session
 */
session_start();

$protocol_view = new ProtocolView();

$protocol_view->init();

$protocol_view->output();



?>
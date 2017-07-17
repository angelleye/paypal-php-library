<?php
if(!session_id()) session_start();

require_once('../includes/config.php');
require_once('../autoload.php');

if(isset($_REQUEST)){
    echo "<pre>";
    print_r($_REQUEST);
    exit;
}
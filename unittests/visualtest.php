<?php
require_once('/../classes/datamanipulation.class.php');
require_once('/../classes/dataretrieval.class.php');
require_once('/../classes/yahoo.class.php');
require_once('/../classes/morningstar.class.php');
    $morningStar = new Morningstar();
    $symbol = 'RR.L';
    $marketID = 2;
    $csv = $morningStar->GetWebsiteData($symbol, $marketID);
    Database::out($csv);
?>

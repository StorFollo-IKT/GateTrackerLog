<?php

use storfollo\gatetracker\GateTrackerLog;


ini_set('display_errors', true);

require 'vendor/autoload.php';
$log = new GateTrackerLog();
if (!empty($_GET['date']))
    $date = new DateTimeImmutable($_GET['date']);
else
    $date = null;

$log->show_log($date);
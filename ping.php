<?php

$host = $_POST["host"] ? $_POST["host"] : $_GET["host"];
$port = $_POST["port"] ? $_POST["port"] : $_GET["port"];
if (!isset($port)) { $port = 80; }
if (isset($host)) {
    //echo json_encode(array("status"=>pingDomain($host, $port)));
    echo json_encode(array("status"=>ping($host)));
} else {
    echo json_encode(array("status"=>false));
}

// Function to check response time
function pingDomain($domain, $port){
    $starttime = microtime(true);
    $file      = fsockopen ($domain, $port, $errno, $errstr, 10);
    $stoptime  = microtime(true);
    $status    = 0;

    if (!$file) $status = -1;  // Site is down
    else {
        fclose($file);
        $status = ($stoptime - $starttime) * 1000;
        $status = floor($status);
    }
    return $status;
}

function ping($host)
{
        exec(sprintf('ping -c 1 -W 5 %s', escapeshellarg($host)), $res, $rval);
        return $rval === 0;
}

?>
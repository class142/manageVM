<?php

$mac_addr = isset($_POST["mac"]) ? $_POST["mac"] : $_GET["mac"];
if (isset($mac_addr)) { 
    $broadcast = "255.0.0.0";
    wakeup($mac_addr, $broadcast);
}


function wakeup ($mac_addr, $broadcast) {

    if (!$fp = fsockopen('udp://' . $broadcast, 2304, $errno, $errstr, 2))
        return false;

    $mac_hex = preg_replace('=[^a-f0-9]=i', '', $mac_addr);

    $mac_bin = pack('H12', $mac_hex);

    $data = str_repeat("\xFF", 6) . str_repeat($mac_bin, 16);

    fputs($fp, $data);
    fclose($fp);
    return true;
}

?>
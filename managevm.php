<?php

require("mysql_config.php");

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD) or die(mysqli_error()); 
mysqli_set_charset($conn, 'utf8');
mysqli_select_db($conn, DB_DATABASE) or die(mysql_error());

$name = isset($_GET["name"]) ? $_GET["name"] : $_POST["name"];
$newState = isset($_GET["newState"]) ? $_GET["newState"] : $_POST["newState"];

if (!isset($name) || empty($name)) {
  //header("Location: index.php");
  echo json_encode(array("success"=>false));
  die();
}

$res = mysqli_query($conn, "SELECT * FROM vm as v JOIN vm_data as vd ON vd.name=v.name WHERE v.name='" . $name . "'");

if (mysqli_num_rows($res) != 1) {
  print_r("$name not found");
  die();
  //header("Location: index.php");
  //die();
} else {
  $vm = mysqli_fetch_object($res); 
  $ins = mysqli_query($conn, "UPDATE vm SET newState=$newState WHERE name='$name'");
  if ($ins) {
    echo json_encode(array("success"=>true));
  } else {
    echo json_encode(array("success"=>false, "message"=>"Error updating $name"));
    die();
  }
}

?>
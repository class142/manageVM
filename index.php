<?php

require("mysql_config.php");

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD) or die(mysqli_error()); 
mysqli_set_charset($conn, 'utf8');
mysqli_select_db($conn, DB_DATABASE) or die(mysql_error());

$h_res = mysqli_query($conn, "SELECT * FROM vm_hosts");

$hosts = array();
while($i = mysqli_fetch_object($h_res)){
	array_push($hosts, $i);
}

?>

<html>
  <head>
    <meta charset="UTF-8"> 
    
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    
    <style>
        table {
            width: 100%;
        }
        table, th, td {
            border: 0;
        }
        iframe {
            width: 100%;
            height: 90%;
            border: 0;
        }
        .btn {
            font-size: 30pt;
        }
    </style>
  </head>
  <body>
</div>
    <div>
        <?php foreach($hosts as $host): ?>
            <button type="button" class="btn btn-secondary btn-lg btn-block" id="host_<?php echo $host->id; ?>" onclick="changeHost('<?php echo $host->id; ?>')"><?php echo $host->description; ?></button>
        <?php endforeach; ?>
    </div>
    <hr/>
    <iframe src="host.php?hostid=1">iFrame kann nicht angezeigt werden</iframe>

    <script>
        function changeHost(hostid) {
            $("iframe")[0].src = "host.php?hostid=" + hostid;
            $("button").removeClass("btn-primary").addClass("btn-secondary");
            $("#host_" + hostid).addClass("btn-primary").removeClass("btn-secondary");
        }
    </script>
  </body>
</html>
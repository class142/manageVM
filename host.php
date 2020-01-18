<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require("mysql_config.php");

$hostid = 1;
if (isset($_GET["hostid"])) { $_GET["hostid"]; }
if (isset($_POST["hostid"])) { $_POST["hostid"]; }

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD) or die(mysqli_error()); 
mysqli_set_charset($conn, 'utf8');
mysqli_select_db($conn, DB_DATABASE) or die(mysql_error());

$i_res = mysqli_query($conn, "SELECT * FROM vm as v JOIN vm_data as vd ON vd.name=v.name JOIN vm_hosts AS vh ON vh.id=vd.host WHERE vh.id=$hostid");
$h_res = mysqli_query($conn, "SELECT * FROM vm_hosts WHERE id=$hostid");

$host = mysqli_fetch_object($h_res);

$vms = array();
while($i = mysqli_fetch_object($i_res)){
	array_push($vms, $i);
}


?>

<html>
  <head>
    <meta charset="UTF-8"> 
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <style>
  table {
    width: 100%;
  }
  
	table, th, td {
		border: 1px solid black;
	}
	td {
		padding: 5px;
	}
  button, td {
    font-size: 40px;
  }
	.hidden {
		display: none;
	}
    </style>
  </head>
  <body>
    <h1>Virtuelle Maschinen auf <?php echo $host->description; ?></h1>
    <table>
    <thead>
      <th>Name</th>
      <th>Status</th>
      <th>IP</th>
      <th>Funktionen</th>
    </thead>
    <tbody>
      <?php foreach($vms as $vm): ?>
      <tr>
        <td><?php echo $vm->name; ?></td>
        <td>
          <div style='width:80px;display: block;text-align: center' id="status_<?php echo $vm->name; ?>">
            <img id="status_<?php echo $vm->name; ?>_loading" src="img/loading4.gif" style="width: 60px;"/>
            <img id="status_<?php echo $vm->name; ?>_check" src="img/check.png" class="hidden" style="width: 60px;"/>
            <img id="status_<?php echo $vm->name; ?>_fail" src="img/fail.png" class="hidden" style="width: 60px;"/>
            <img id="status_<?php echo $vm->name; ?>_warning" src="img/warning.png" class="hidden" style="width: 60px;"/>
          </div>
        </td>
        <td><?php echo $vm->ip; ?></td>
          <td class="hidden" id="cancel_<?php echo $vm->name; ?>"><button onClick="cancel('<?php echo $vm->name; ?>')">Abbrechen</button></td>
          <td class="hidden" id="start_<?php echo $vm->name; ?>"><button onClick="start('<?php echo $vm->name; ?>')">Starten</button></td>
          <td class="hidden" id="stop_<?php echo $vm->name; ?>">
            <button onClick="window.open('<?php echo $vm->guac_link; ?>')">Verbinden</button>
            <button onClick="stop('<?php echo $vm->name; ?>')">Stoppen</button>
	</div>
      </tr>
	<script>
		$.ajax({
			url: "ping.php",
			data: {
				"host": "<?php echo $vm->ip; ?>"
			},
			success: function(data) {
				res = JSON.parse(data);
				$("#status_<?php echo $vm->name; ?>_loading").hide();
        if (res && (res.status ? 1 : 2) != <?php echo $vm->state; ?>) {
          $("#status_<?php echo $vm->name; ?>_warning").show();
          $("#cancel_<?php echo $vm->name; ?>").show();
          var button = $("#cancel_ubuntuserver3").find("button");
          button[0].onclick = null;
          button.on("click", function() {
            manageVM("<?php echo $vm->name; ?>", (res.status ? 1 : 2));
          });

        } else {
          $("#status_<?php echo $vm->name; ?>_" + (res && res.status == 1 ? "check" :"fail")).show();
          if (res && res.status) {
            $("#start_<?php echo $vm->name; ?>").hide();
            $("#stop_<?php echo $vm->name; ?>").show();
          } else {
            $("#start_<?php echo $vm->name; ?>").show();
            $("#stop_<?php echo $vm->name; ?>").hide();					
          }				
        }
			}
		});
	</script>
      <?php endforeach; ?>
    </tbody>
    </table>
    
    <script>
      function start(name) {
        manageVM(name, 1);
      };
      function stop(name) {
        manageVM(name, 2);
      };
      function manageVM(name, state) {
        $("img[id^='status_" + name + "']").hide();
        $("#status_" + name + "_loading").show();
        $.ajax({
          url: "managevm.php",
          data: {
            name: name,
            newState: state
          },
          success: function(data) {
            res = JSON.parse(data);
            if (res && res.success) {
              setTimeout(() => {
                window.location.reload();  
              }, 1000);
            }
          }
        });
      }
    </script>
  </body>
</html>
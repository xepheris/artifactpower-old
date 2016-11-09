<?php

if(isset($_GET['updategraph']) && $_GET['updategraph'] == '1') {
	
	$valid_update_query = mysqli_fetch_array(mysqli_query($conn, "SELECT `users` FROM `total_graph_data` WHERE `total` = '101'"));
	$update_diff = time('now')-$valid_update_query['users'];
				
	if($update_diff >= '3600') {
			
		// KILL OLD GRAPH TABLE
		$kill_old_graph_table = mysqli_query($conn, "TRUNCATE TABLE `total_graph_data`");
		
		$rankone = mysqli_fetch_array(mysqli_query($conn, "SELECT `total`, `char`, `region`, `server` FROM `data1` ORDER BY `total` DESC LIMIT 1"));
		$server = $rankone['server'];
		$char = $rankone['char'];
		$region = $rankone['region'];
		$total = $rankone['total'];
		
		// ENABLE SSL
		$arrContextOptions=array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, ),);  
	
		// API KEY
		$api = '';
	
		// REMOVE SPACES IN SERVER NAME TO PREVENT BUGS IN URL
		if(strpos($server, ' ') !== false) {
			$server = str_replace(' ', '%20', $server);
		}
		// REMOVE SLASHES IN SERVER NAME TO ALLOW ACTUAL SEARCH AGAIN
		$server = stripslashes($server);
	
		// ARMORY API LINK ACHIEVEMENTS
		$url = 'https://' .$region. '.api.battle.net/wow/character/' .$server. '/' .$char. '?fields=achievements&locale=en_GB&apikey=' .$api. '';
	
		$data = @file_get_contents($url, false, stream_context_create($arrContextOptions));
		$data = json_decode($data, true);
		$key = array_search('10671', $data['achievements']['achievementsCompleted']);

		$criterias = array();
		array_push($criterias, $data['achievements']['achievementsCompletedTimestamp']);
		$criterias = $criterias['0'];
		$timestamp = $criterias[$key];	
		
		// FETCH LV110 TIMESTAMP FROM RANK 1
		$timestamp = substr($timestamp, '-13', '10');
	
		// CALC DAILY GAIN FROM RANK 1
		$dayspassed = (time('now')-$timestamp)/60/60/24;
		
		$daily = round(($total/$dayspassed)/10, 0);
		
		$spam_prevention = mysqli_query($conn, "INSERT INTO `total_graph_data` (`total`, `users`) VALUES ('101', '" .time('now'). "')");
		
		// BUILD NEW TABLE
		
		function generate_graph_data($total) {
			global $conn, $daily;
			
			if($total > '0') {
				
				$next_lower = $total-$daily;
				
				$fetch_amount_of_users_sql = "SELECT COUNT(`id`) AS `sum_users` FROM `data1` WHERE `total` <= '" .$total. "' AND `total` > '" .$next_lower. "'";
				$fetch_amount_of_users = mysqli_fetch_array(mysqli_query($conn, $fetch_amount_of_users_sql));
				
				$insert_into_graph_table_sql = "INSERT INTO `total_graph_data` (`total`, `users`) VALUES ('" .$total. "', '" .$fetch_amount_of_users['sum_users']. "')";
				$insert_into_graph_table = mysqli_query($conn, $insert_into_graph_table_sql);
				
				$total = $next_lower;
				
				generate_graph_data($total);
			}				
		}

		generate_graph_data($total);
		
		echo '<p style="color: green;">Thanks for updating!</p>';
		
	}
	elseif($update_diff < '3600') {
		echo '<p id="error">Sorry, updating the graph is allowed only once every one hour. Next update possible in: ' .(3600-$update_diff). ' seconds.</p>';
	}
}
echo '<div id="graph_users" style="width: 90%; height: 500px"></div>';

$last_update = mysqli_fetch_array(mysqli_query($conn, "SELECT `users` FROM `total_graph_data` WHERE `total` = '101'"));
$last_update = time('now')-$last_update['users'];

if($last_update >= '3600') {
	echo '<p id="cent"><span style="background-color: green;"><a href="?updategraph=1" style="text-decoration: none;">Update graph</a></span></p>';
}

$remaining_time = 3600-$last_update;

if($remaining_time >= '0') {
	$remaining_time_text = 'next update available in ' .$remaining_time. ' seconds';
}
elseif($remaining_time < '0') {
	$remaining_time_text = 'update available! see button below';
}

?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
	google.charts.load('current', {'packages':['corechart']});
	google.charts.setOnLoadCallback(drawChart);

	function drawChart() {
		var data = google.visualization.arrayToDataTable([
		['Total AP', 'Users'],
		  
		<?php
		$fetch_table_rows = mysqli_query($conn, "SELECT * FROM `total_graph_data` WHERE `total` != '101' ORDER BY `id` DESC");
		while($rows = mysqli_fetch_array($fetch_table_rows)) {
				
			// outputs json format for javascript: ['0.5%', number],
			echo "['" .round($rows['total']*0.95, 0). "-" .$rows['total']. "', " .$rows['users']. "],";
		}		  
		?>
          
		]);

		var options = {
			title: 'Distribution of farmed Artifact Power, steps based on 1/10th of daily gain of rank 1 (<?php echo $remaining_time_text; ?>)',
			curveType: 'function',
			backgroundColor: '#D6CDAE',
			legend: { position: 'none' },
			vAxis: {
				viewWindow: {
					min: 0 },
				gridlines: { 
					color: 'grey' },	
			}
		};

		var chart = new google.visualization.LineChart(document.getElementById('graph_users'));

		chart.draw(data, options);
	}
</script>
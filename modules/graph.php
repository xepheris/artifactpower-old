<?php
if(isset($_GET['updategraph']) && ($_GET['updategraph'] == '1')) {
		
	$valid_update_query = mysqli_fetch_array(mysqli_query($conn, "SELECT `users` FROM `total_graph_data` WHERE `total` = '101'"));
	$update_diff = time('now')-$valid_update_query['users'];
				
	if($update_diff >= '3600') {
			
		// KILL OLD GRAPH TABLE
		$kill_old_graph_table = mysqli_query($conn, "TRUNCATE TABLE `total_graph_data`");
		
		// SELECT HIGHEST PERCENTAGE AND ROUND UP TO NEXT QUARTER PERCENT AS CAP FOR CURRENT GRAPH
		$select_highest_total = mysqli_fetch_array(mysqli_query($conn, "SELECT `total` FROM `data1` ORDER BY `total` DESC LIMIT 1"));
		$highest_total = $select_highest_total['total'];
		
		// BUILD NEW TABLE CONTENT
		$factor = '0.95';
		
		$spam_prevention = mysqli_query($conn, "INSERT INTO `total_graph_data` (`total`, `users`) VALUES ('101', '" .time('now'). "')");
		
		function generate_graph_data($highest_total) {
			global $conn, $minus;
			
			$factor = '0.95';
					
			if($highest_total >= '1000') {
				$highest_total = round($highest_total, 0);
				$next_lower = round($highest_total*$factor, 0);
				
				$fetch_amount_of_users_sql = "SELECT COUNT(`id`) AS `sum_users` FROM `data1` WHERE `total` <= '" .$highest_total. "' AND `total` > '" .$next_lower. "'";
				$fetch_amount_of_users = mysqli_fetch_array(mysqli_query($conn, $fetch_amount_of_users_sql));
				
				$insert_into_graph_table_sql = "INSERT INTO `total_graph_data` (`total`, `users`) VALUES ('" .$highest_total. "', '" .$fetch_amount_of_users['sum_users']. "')";
				$insert_into_graph_table = mysqli_query($conn, $insert_into_graph_table_sql);
				
				$highest_total = $highest_total*$factor;
				
				generate_graph_data($highest_total);
				
			}
		}
		
		generate_graph_data($highest_total);
		
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
			title: 'Distribution of farmed Artifact Power (<?php echo $remaining_time_text; ?>)',
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

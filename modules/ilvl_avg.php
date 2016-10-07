<?php
if(isset($_GET['updateilvl']) && ($_GET['updateilvl'] == '1')) {
		
	$valid_update_query = mysqli_fetch_array(mysqli_query($conn, "SELECT `users` FROM `ilvl_avg_graph` WHERE `percentage` = '101'"));
	$update_diff = time('now')-$valid_update_query['users'];
				
	if($update_diff >= '3600') {
			
		// KILL OLD GRAPH TABLE
		$kill_old_graph_table = mysqli_query($conn, "TRUNCATE TABLE `ilvl_avg_graph`");
		
		// SELECT HIGHEST PERCENTAGE AND ROUND UP TO NEXT QUARTER PERCENT AS CAP FOR CURRENT GRAPH
		$select_highest_ilvl = mysqli_fetch_array(mysqli_query($conn, "SELECT `ilvl` FROM `data1` ORDER BY `ilvl` DESC LIMIT 1"));
		$highest_ilvl = $select_highest_ilvl['ilvl'];
		
		// BUILD NEW TABLE CONTENT
		$ilvlarray = array('1', '750', '751', '752', '753', '754', '755', '756', '757', '758', '759', '760', '761', '762', '763', '764', '765', '766', '767', '768', '769', '770', '771', '772', '773', '774', '775', '776', '777', '778', '779', '780', '781', '782', '783', '784', '785', '786', '787', '788', '789', '790', '791', '792', '793', '794', '795', '796', '797', '798', '799', '800', '801', '802', '803', '804', '805', '806', '807', '808', '809', '810', '811', '812', '813', '814', '815', '816', '817', '818', '819', '820', '821', '822', '823', '824', '825', '826', '827', '828', '829', '830', '831', '832', '833', '834', '835', '836', '837', '838', '839', '840', '841', '842', '843', '844', '845', '846', '847', '848', '849', '850', '851', '852', '853', '854', '855', '856', '857', '858', '859', '860', '861', '862', '863', '864', '865', '866', '867', '868', '869', '870', '871', '872', '873', '874', '875', '876', '877', '878', '879', '880', '881', '882', '883', '884', '885', '886', '887', '888', '889', '890', '891', '892', '893', '894', '895', '896', '897', '898', '899', '900', '901', '902', '903', '904', '905', '906', '907', '908', '909', '910', '911', '912', '913', '914', '915', '916', '917', '918', '919', '920', '921', '922', '923', '924', '925', '926', '927', '928', '929', '930', '931', '932', '933', '934', '935', '936', '937', '938', '939', '940', '941', '942', '943', '944', '945', '946', '947', '948', '949', '950');
		
		foreach($ilvlarray as $ilvl) {
			if($ilvl == '1') {
					$spam_prevention = mysqli_query($conn, "INSERT INTO `ilvl_avg_graph` (`ilvl`, `users`) VALUES ('" .$ilvl. "', '" .time('now'). "')");
			}
			if($ilvl <= $highest_ilvl) {
				if($ilvl != '1') {
									
					$fetch_amount_of_users_sql = "SELECT *, COUNT(`id`) AS `sum_users` FROM `data1` WHERE `ilvl` = '" .$ilvl. "'";
					$fetch_amount_of_users = mysqli_fetch_array(mysqli_query($conn, $fetch_amount_of_users_sql));
					
					$insert_into_graph_table_sql = "INSERT INTO `ilvl_avg_graph` (`ilvl`, `users`) VALUES ('" .$ilvl. "', '" .$fetch_amount_of_users['sum_users']. "')";
					$insert_into_graph_table = mysqli_query($conn, $insert_into_graph_table_sql);
				}
			}
		}
		echo '<p style="color: green;">Thanks for updating!</p>';
	}
	elseif($update_diff < '3600') {
		echo '<p id="error">Sorry, updating the graph is allowed only once every per hour. Next update possible in: ' .(3600-$update_diff). ' seconds.</p>';
	}
}

echo '<div id="graph_ilvl" style="width: 75%; height: 500px"></div>';

$last_update = mysqli_fetch_array(mysqli_query($conn, "SELECT `users` FROM `ilvl_avg_graph` WHERE `ilvl` = '1'"));
$last_update = time('now')-$last_update['users'];

if($last_update >= '3600') {
	echo '<p id="cent"><span style="background-color: green;"><a href="?updateilvl=1" style="text-decoration: none;">Update graph</a></span></p>';
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
		['Percentage', 'Users'],
		  
		<?php
		$fetch_table_rows = mysqli_query($conn, "SELECT * FROM `ilvl_avg_graph` WHERE `ilvl` != '1'");
		while($rows = mysqli_fetch_array($fetch_table_rows)) {
			// outputs json format for javascript: ['850', number],
			echo "['" .$rows['ilvl']. "', " .$rows['users']. "],";
		}		  
		?>
          
		]);

		var options = {
			title: 'Distribution of itemlevels (<?php echo $remaining_time_text; ?>)',
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

		var chart = new google.visualization.LineChart(document.getElementById('graph_ilvl'));

		chart.draw(data, options);
	}
</script>
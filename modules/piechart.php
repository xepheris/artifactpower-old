<?php
include('serv.php');

if(isset($_GET['updatepiechart']) && $_GET['updatepiechart'] == '1') {
	
	$valid_update_query = mysqli_fetch_array(mysqli_query($conn, "SELECT `users` FROM `pie_chart_data` WHERE `class` = '100'"));
	$update_diff = time('now')-$valid_update_query['users'];
				
	if($update_diff >= '900') {
		
		$delete_old_data = mysqli_query($conn, "TRUNCATE TABLE `pie_chart_data`");
		
		$amountofclasses = mysqli_query($conn, "SELECT `class` FROM `classes`");
		$c = '1';
		
		while($class = mysqli_fetch_array($amountofclasses)) {
			${'class' . $c} = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(`id`) AS `sumclass" .$c. "` FROM `data1` WHERE `class` = '" .$class['class']. "'"));
			$save_data = mysqli_query($conn, "INSERT INTO `pie_chart_data` (`class`, `users`) VALUES ('" .$c. "', '" .${'class' . $c}['sumclass' .$c. '']. "'); ");
			$c++;
		}
		
		$spam_prevention = mysqli_query($conn, "INSERT INTO `pie_chart_data` (`class`, `users`) VALUES ('100', '" .time('now'). "')");	
		echo '<p style="color: green;">Thanks for updating!</p>';
	}
	elseif($update_diff < '900') {
		echo '<p id="error">Sorry, updating the graph is allowed only once every 15 minutes. Next update possible in: ' .(900-$update_diff). ' seconds.</p>';
	}
}

echo '<div id="pie_chart_users" style="width: 50%; height: 500px;"></div>';

$last_update = mysqli_fetch_array(mysqli_query($conn, "SELECT `users` FROM `pie_chart_data` WHERE `class` = '100'"));
$last_update = time('now')-$last_update['users'];

if($last_update >= '900') {
	echo '<p id="cent"><span style="background-color: green;"><a href="?updatepiechart=1" style="text-decoration: none;">Update graph</a></span></p>';
}

$remaining_time = 900-$last_update;

if($remaining_time >= '0') {
	$remaining_time_text = 'next update available in ' .$remaining_time. ' seconds';
}
elseif($remaining_time < '0') {
	$remaining_time_text = 'update available! see button below';
}

?>

<script type="text/javascript">
	google.charts.load('current', {'packages':['corechart']});
	google.charts.setOnLoadCallback(drawChart);
	function drawChart() {

		var data = google.visualization.arrayToDataTable([
		['Class', 'Amount'],
		<?php
		$fetch_table_rows = mysqli_query($conn, "SELECT * FROM `pie_chart_data` WHERE `class` != '100'");
		while($rows = mysqli_fetch_array($fetch_table_rows)) {
			$classes = mysqli_fetch_array(mysqli_query($conn, "SELECT `class` FROM `classes` WHERE `id` = '" .$rows['class']. "'"));
			// outputs json format for javascript: ['Class name', users],
			echo "['" .$classes['class']. "', " .$rows['users']. "],";
		}		  
		?>
        ]);

		var options = {
			title: 'Current class distribution (<?php echo $remaining_time_text; ?>)',
			backgroundColor: '#D6CDAE',
			slices: {0: {color: '#C79C6E'}, 1: {color: '#F58CBA'}, 2: {color: '#ABD473'}, 3: {color: '#FFF569'}, 4: {color: '#FFFFFF'}, 5: {color: '#C41F3B'}, 6: {color: '#0070DE'}, 7: {color: '#69CCF0'}, 8: {color: '#9482C9'}, 9: {color: '#00FF96'}, 10: {color: '#FF7D0A'}, 11: {color: '#A330C9'},},
			pieSliceTextStyle: {color: 'black'}
		};

	var chart = new google.visualization.PieChart(document.getElementById('pie_chart_users'));

	chart.draw(data, options);
}
</script>
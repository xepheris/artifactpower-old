<?php
		
echo '<div id="apgain" style="width: 90%; height: 500px"></div>';

?>
<script type="text/javascript">
	google.charts.load('current', {'packages':['corechart']});
	google.charts.setOnLoadCallback(drawChart);

	function drawChart() {
		var data = google.visualization.arrayToDataTable([
		['Time', 'Total AP'],
		  
		<?php
		$fetch_table_rows = mysqli_query($conn, "SELECT DISTINCT(`total`) FROM `totalap` WHERE `timestamp` >= '" .(time('now')-172800). "' ORDER BY `id` ASC");
		while($rows = mysqli_fetch_array($fetch_table_rows)) {
				
			// outputs json format for javascript: ['0.5%', number],
			$timestamp = mysqli_fetch_array(mysqli_query($conn, "SELECT `timestamp` FROM `totalap` WHERE `total` = '" .$rows['total']. "' ORDER BY `id` ASC LIMIT 1"));
			echo "['" .date('h:i', $timestamp['timestamp']). "', " .$rows['total']. "], ";
		}		  
		?>
          
		]);

		var options = {
			title: 'Total AP gained stream (latest 2500 updates)',
			curveType: 'function',
			backgroundColor: '#D6CDAE',
			legend: { position: 'none' },
			vAxis: {
				<?php
			$minValue = mysqli_fetch_array(mysqli_query($conn,"SELECT `total` FROM `totalap` WHERE `timestamp` >= '" .(time('now')-172800). "' ORDER BY `id` ASC LIMIT 1"));
			echo "minValue: '" .$minValue['total']. "', ";
				
			?>
				gridlines: { 
					color: 'grey' },
				format: 'short',
			
			},
		};

		var chart = new google.visualization.LineChart(document.getElementById('apgain'));

		chart.draw(data, options);
	}
</script>

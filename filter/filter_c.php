<?php

if((!isset($topregion)) && (!isset($topserver)) && (isset($topclass)) && $topclass != '') {
				
	topclasssearch();
								
	$topclasssearch = mysqli_query($conn, "SELECT * FROM `data1` WHERE `class` = '" .$classname. "' ORDER BY `total` DESC LIMIT 100");
							
	while($users = mysqli_fetch_array($topclasssearch)) {
					
		$classshort = mysqli_fetch_array(mysqli_query($conn, "SELECT `class_short`, `color` FROM `classes` WHERE `class` = '" .$users['class']. "'"));
							
		// TIMESTAMP LAST UPDATE IN SECONDS
		$timesincelastupdate = round(time('now')-$users['timestamp'], 2);
					
		// COLORIZATION DEPENDING ON LAST UPDATE
		if($timesincelastupdate < '60') {
			$effectivetime = '<span id="seconds">' .$timesincelastupdate. ' seconds</span>';
		}
		elseif(($timesincelastupdate > '60') && ($timesincelastupdate < '3600')) {
			$effectivetime = '<span id="seconds">' .round($timesincelastupdate/60, 1). ' minutes</span>';
		}
		elseif(($timesincelastupdate >= '3600') && ($timesincelastupdate < '64800')) {
			$effectivetime = '<span id="minutes">' .round($timesincelastupdate/60/60, 1). ' hours</span>';
		}
		elseif(($timesincelastupdate >= '64800') && ($timesincelastupdate < '86400')) {
			$effectivetime = '<span id="hours">' .round($timesincelastupdate/60/60, 1). ' hours</span>';
		}
		elseif($timesincelastupdate >= '86400') {
			$effectivetime = '<span id="days">' .round($timesincelastupdate/60/60/24, 2). ' days</span>';
		}
		
		if($users['alevel'] == '0') {
			$users['alevel'] = 'unknown';
		}
				
		echo '<div id="tr">
		<div id="td">' .$i. '</div>
		<div id="td">' .number_format($users['total']). '</div>';
		$cap = '5216130';
		$second_cap = '65256330';
		if($users['total'] > $cap) {
			$users['percent'] = '100';
			$bonusprogress = round(($users['total']-$cap)/$second_cap, 5)*100;
		}
		elseif($users['total'] <= $cap) {
			$bonusprogress = '0';
		}
		echo '<div id="td">' .$users['percent']. '</div>
		<div id="td">' .$bonusprogress. '</div>
		<div id="td">' .$users['alevel']. '</div>
		<div id="td"><a href="http://' .$users['region']. '.battle.net/wow/en/character/' .$users['server']. '/' .$users['char']. '/simple">' .$users['char']. ' (' .$users['region']. '-' .$users['server']. ')</a></div>
		<div id="td">' .$users['ilvl']. '</div>
		<div id="td" style="background-color: ' .$classshort['color']. '"><a href="?class=' .$classshort['class_short']. '">' .$users['class']. '</a></div>
		<div id="td">' .$effectivetime. '</div>
		<div id="td"><a href="?updatechar=' .$users['id']. '">update</a></div>
		</div>';
		$i++;
	}
				
	$classusers = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(DISTINCT `char`) AS `chars` FROM `data1` WHERE `class` = '" .$classname. "'"));
	
	$averageapgained = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(`total`) AS `sumtotal` FROM `data1` WHERE `class` = '" .$classname. "'"));
	$averageapgained = round($averageapgained['sumtotal']/$classusers['chars'], 0);
				
	$averagepercent = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(`percent`) AS `sumpercent` FROM `data1` WHERE `class` = '" .$classname. "'"));
	$averagepercent = round($averagepercent['sumpercent']/$classusers['chars'], 2);
			
	echo '</div>
	<h3>Statistics</h3>
	<div id="t">
	<div id="tr">
	<div id="td">total ' .$classname. 's</div>
	<div id="td">average AP gained</div>
	<div id="td">average % completed</div>
	</div>
	<div id="tr">
	<div id="td">' .$classusers['chars']. '</div>
	<div id="td">' .$averageapgained. '</div>
	<div id="td">' .$averagepercent. '</div>
	</div>
	</div>
	</center><br /></div>';
	include('modules/footer.php');
	echo '</div>
	</body>
	</html>';
	die();
}
?>
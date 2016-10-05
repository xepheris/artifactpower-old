<?php
if(isset($topregion) && ($topserver != '') && ($topclass == '')) {
	
	topregionsearch();	
	topserversearch();


	$topserver = addslashes($topserver);
	$topserversearch = mysqli_query($conn, "SELECT * FROM `data1` WHERE `server` = '" .$topserver. "' AND `region` = '" .$topregion. "' ORDER BY `total` DESC LIMIT 50");	
	$topserver = stripslashes($topserver);
	while($users = mysqli_fetch_array($topserversearch)) {
		
		$classshort = mysqli_fetch_array(mysqli_query($conn, "SELECT `class_short`, `color` FROM `classes` WHERE `class` = '" .$users['class']. "'"));
				
		// TIMESTAMP LAST UPDATE IN SECONDS
		$timesincelastupdate = round(time('now')-$users['timestamp'], 2);
	
		// IF LESS THAN A MINUTE
		if($timesincelastupdate < '60') {
			$effectivetime = '<span id="seconds">' .$timesincelastupdate. ' seconds</span>';
		}
		elseif(($timesincelastupdate > '60') && ($timesincelastupdate < '3600')) {
			$effectivetime = '<span id="minutes">' .round($timesincelastupdate/60, 1). ' minutes</span>';
		}
		elseif(($timesincelastupdate > '3600') && ($timesincelastupdate < '86400')) {
			$effectivetime = '<span id="hours">' .round($timesincelastupdate/60/60, 1). ' hours</span>';
		}
		elseif(($timesincelastupdate > '8400') && ($timesincelastupdate < '31622400')) {
			$effectivetime = '<span id="days">' .round($timesincelastupdate/60/60/24, 2). ' days</span>';
		}
		elseif($timesincelastupdate > '31622400') {
			$effectivetime = '<span id="days">' .round($timesincelastupdate/60/60/24/365.25, 2). ' years</span>';
		}
		
		if($users['alevel'] == '0') {
			$users['alevel'] = 'unknown';
		}
		
		echo '<div id="tr">
		<div id="td">' .$i. '</div>
		<div id="td">' .$users['total']. '</div>
		<div id="td">' .$users['percent']. '</div>
		<div id="td">' .$users['alevel']. '</div>
		<div id="td"><a href="http://' .$users['region']. '.battle.net/wow/en/character/' .$users['server']. '/' .$users['char']. '/simple">' .$users['char']. ' (' .$users['region']. '-' .$users['server']. ')</a></div>
		<div id="td">' .$users['ilvl']. '</div>
		<div id="td" style="background-color: ' .$classshort['color']. '"><a href="?topregion=' .$topregion. '&topserver=' .$topserver. '&class=' .$classshort['class_short']. '">' .$users['class']. '</a></div>
		<div id="td">' .$effectivetime. '</div>
		<div id="td"><a href="?updatechar=' .$users['id']. '">update</a></div>
		</div>';
		$i++;
	}
	$topserver = addslashes($topserver);
	$regionserverusers = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(DISTINCT `char`) AS `chars` FROM `data1` WHERE `region` = '" .$topregion. "' AND `server` = '" .$topserver. "'"));
	$averageapgained = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(`total`) AS `sumtotal` FROM `data1` WHERE `region` = '" .$topregion. "' AND `server` = '" .$topserver. "'"));
	$averageapgained = round($averageapgained['sumtotal']/$regionserverusers['chars'], 0);
	
	$averagepercent = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(`percent`) AS `sumpercent` FROM `data1` WHERE `region` = '" .$topregion. "' AND `server` = '" .$topserver. "'"));
	stripslashes($topserver);
	$averagepercent = round($averagepercent['sumpercent']/$regionserverusers['chars'], 2);
	echo '</div>
	<h3>Statistics</h3>
	<div id="t">
	<div id="tr">
	<div id="td">total users</div>
	<div id="td">average AP gained</div>
	<div id="td">average % completed</div>
	</div>
	<div id="tr">
	<div id="td">' .$regionserverusers['chars']. '</div>
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
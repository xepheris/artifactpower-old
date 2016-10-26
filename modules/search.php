<?php

$char = $_POST['char'];
$server = $_POST['server'];
$region = $_POST['region'];

$server = addslashes($server);						
$check_if_exists = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `data1` WHERE `char` = '" .$char. "' AND `server` = '" .$server. "' AND `region` = '" .$region. "'"));
$server = stripslashes($server);
			
if($check_if_exists['id'] == '' || $check_if_exists['id'] == '0') {
	echo '<p id="error">Sorry, could not find the character <a href="http://' .$region. '.battle.net/wow/en/character/' .$server. '/' .$char. '/simple">' .$char. ' (' .$region. '-' .$server. ')</a> within the database.</p>';
}
elseif($check_if_exists['id'] != '' || $check_if_exists['id'] != '0') {
				
	$user_id = $check_if_exists['id'];
				
	$rank = mysqli_fetch_array(mysqli_query($conn, "SELECT z.rank FROM (SELECT `id`, `char`, @rownum := @rownum + 1 AS `rank` FROM `data1`, (SELECT @rownum := 0) r ORDER BY `total` DESC) as z WHERE `id` = '" .$user_id. "'"));
				
	echo '<center>
	<p>Important: search is supposed to give you an approximate indication of your absolute rank. If entries show twice or more, the db is updating currently.</p>
	<h3>Showing +- 12 users above/below ' .$char. ' (' .$region. ' - ' .$server. ')</h3>
	<div id="t">
	<div id="tr">
	<div id="td">#</div>
	<div id="td">total AP gained</div>
	<div id="td">regular trait %</div>
	<div id="td">bonus trait %</div>
	<div id="td">artifact level</div>
	<div id="td">armory</div>
	<div id="td">itemlevel</div>
	<div id="td">class</div>
	<div id="td">last updated</div>
	<div id="td"></div>
	</div>';
				
	$top_selection = array('12', '11', '10', '9', '8', '7', '6', '5', '4', '3', '2', '1');
				
	foreach($top_selection as $usersabove) {

		$user = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM (SELECT *, @rownum := @rownum + 1 AS `rank` FROM `data1`, (SELECT @rownum := 0) r ORDER BY `total` DESC) as z WHERE `rank` = '" .($rank['rank']-$usersabove). "'"));
					
		// TIMESTAMP LAST UPDATE IN SECONDS
		$timesincelastupdate = round(time('now')-$user['timestamp'], 2);
		
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
			$effectivetime = '<span id="days">' .round($timesincelastupdate/60/60/24, 2). ' years</span>';
		}
					
		$classshort = mysqli_fetch_array(mysqli_query($conn, "SELECT `class_short`, `color` FROM `classes` WHERE `class` = '" .$user['class']. "'"));
		
		if($user['alevel'] == '0') {
			$user['alevel'] = 'unknown';
		}
					
		echo '<div id="tr">
		<div id="td">' .$user['rank']. '</div>
		<div id="td">' .$user['total']. '</div>';
		$cap = '5216130';
		$second_cap = '65256330';
		if($user['total'] > $cap) {
			$user['percent'] = '100';
			$bonusprogress = round(($user['total']-$cap)/$second_cap, 5)*100;	
		}
		elseif($user['total'] <= $cap) {
			$bonusprogress = '0';
		}
		echo '<div id="td">' .$user['percent']. '</div>
		<div id="td">' .$bonusprogress. '</div>
		<div id="td">' .$user['alevel']. '</div>
		<div id="td"><a href="http://' .$user['region']. '.battle.net/wow/en/character/' .$user['server']. '/' .$user['char']. '/simple">' .$user['char']. ' (' .$user['region']. '-' .$user['server']. ')</a></div>
		<div id="td">' .$user['ilvl']. '</div>
		<div id="td" style="background-color: ' .$classshort['color']. ';"><a href="?class=' .$classshort['class_short']. '">' .$user['class']. '</a></div>
		<div id="td">' .$effectivetime. '</div>
		<div id="td"><a href="?updatechar=' .$user['id']. '">update</a></div>
		</div>';
		}
				
	// TIMESTAMP LAST UPDATE IN SECONDS
	$timesincelastupdate = round(time('now')-$check_if_exists['timestamp'], 2);
					
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
				
	$classshort = mysqli_fetch_array(mysqli_query($conn, "SELECT `class_short`, `color` FROM `classes` WHERE `class` = '" .$check_if_exists['class']. "'"));
	
	if($check_if_exists['alevel'] == '0') {
			$check_if_exists['alevel'] = 'unknown';
	}
				
	echo '<div id="tr" style="background-color: white;">
	<div id="td">' .$rank['rank']. '</div>
	<div id="td">' .$check_if_exists['total']. '</div>';
	$cap = '5216130';
		$second_cap = '65256330';
	if($check_if_exists['total'] > $cap) {
		$check_if_exists['percent'] = '100';
		$bonusprogress = round(($check_if_exists['total']-$cap)/$second_cap, 5)*100;	
	}
	elseif($check_if_exists['total'] <= $cap) {
		$bonusprogress = '0';
	}
	echo '<div id="td">' .$check_if_exists['percent']. '</div>
	<div id="td">' .$bonusprogress. '</div>
	<div id="td">' .$check_if_exists['alevel']. '</div>
	<div id="td"><a href="http://' .$check_if_exists['region']. '.battle.net/wow/en/character/' .$check_if_exists['server']. '/' .$check_if_exists['char']. '/simple">' .$check_if_exists['char']. ' (' .$check_if_exists['region']. '-' .$check_if_exists['server']. ')</a></div>
	<div id="td">' .$check_if_exists['ilvl']. '</div>
	<div id="td" style="background-color: ' .$classshort['color']. ';"><a href="?class=' .$classshort['class_short']. '">' .$check_if_exists['class']. '</a></div>
	<div id="td">' .$effectivetime. '</div>
	<div id="td"><a href="?updatechar=' .$check_if_exists['id']. '">update</a></div>
	</div>';		
				
	$top_selection = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12');
				
	foreach($top_selection as $usersabove) {

		$user = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM (SELECT *, @rownum := @rownum + 1 AS `rank` FROM `data1`, (SELECT @rownum := 0) r ORDER BY `total` DESC) as z WHERE `rank` = '" .($rank['rank']+$usersabove). "'"));
					
		// TIMESTAMP LAST UPDATE IN SECONDS
		$timesincelastupdate = round(time('now')-$user['timestamp'], 2);
					
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
					
		$classshort = mysqli_fetch_array(mysqli_query($conn, "SELECT `class_short`, `color` FROM `classes` WHERE `class` = '" .$user['class']. "'"));
		
		if($user['alevel'] == '0') {
			$user['alevel'] = 'unknown';
		}
					
		echo '<div id="tr">
		<div id="td">' .$user['rank']. '</div>
		<div id="td">' .$user['total']. '</div>';
		$cap = '5216130';
		$second_cap = '65256330';
		if($user['total'] > $cap) {
			$user['percent'] = '100';
			$bonusprogress = round(($user['total']-$cap)/$second_cap, 5)*100;
		}
		elseif($user['total'] <= $cap) {
			$bonusprogress = '0';
		}
		echo '<div id="td">' .$user['percent']. '</div>
		<div id="td">' .$bonusprogress. '</div>
		<div id="td">' .$user['alevel']. '</div>
		<div id="td"><a href="http://' .$user['region']. '.battle.net/wow/en/character/' .$user['server']. '/' .$user['char']. '/simple">' .$user['char']. ' (' .$user['region']. '-' .$user['server']. ')</a></div>
		<div id="td">' .$user['ilvl']. '</div>
		<div id="td" style="background-color: ' .$classshort['color']. ';"><a href="?class=' .$classshort['class_short']. '">' .$user['class']. '</a></div>
		<div id="td">' .$effectivetime. '</div>
		<div id="td"><a href="?updatechar=' .$user['id']. '">update</a></div>
		</div>';
	}
	
	echo '</div></center><br /></div>';
	include('modules/footer.php');
	echo '</div>
	</body>
	</html>';
	die();
}
?>
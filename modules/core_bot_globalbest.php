<?php

$cap = '5216130';
$second_cap = '65256330';

$r1EU = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `data1` WHERE `region` = 'EU' ORDER BY `total` DESC LIMIT 1"));
$r1EUupdated = round(time('now')-$r1EU['timestamp'], 2);
			
$r1US = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `data1` WHERE `region` = 'US' ORDER BY `total` DESC LIMIT 1"));
$r1USupdated = round(time('now')-$r1US['timestamp'], 2);

// COLORIZATION DEPENDING ON LAST UPDATE
if($r1EUupdated < '60') {
	$r1EUupdated = '<span id="seconds">' .$r1EUupdated. ' seconds</span>';
}
elseif(($r1EUupdated > '60') && ($r1EUupdated < '3600')) {
	$r1EUupdated = '<span id="seconds">' .round($r1EUupdated/60, 1). ' minutes</span>';
}
elseif(($r1EUupdated >= '3600') && ($r1EUupdated < '64800')) {
	$r1EUupdated = '<span id="minutes">' .round($r1EUupdated/60/60, 1). ' hours</span>';
}
elseif(($r1EUupdated >= '64800') && ($r1EUupdated < '86400')) {
	$r1EUupdated = '<span id="hours">' .round($r1EUupdated/60/60, 1). ' hours</span>';
}
elseif($r1EUupdated >= '86400') {
	$r1EUupdated = '<span id="days">' .round($r1EUupdated/60/60/24, 2). ' days</span>';
}

if($r1USupdated < '60') {
	$r1USupdated = '<span id="seconds">' .$r1USupdated. ' seconds</span>';
}
elseif(($r1USupdated > '60') && ($r1USupdated < '3600')) {
	$r1USupdated = '<span id="seconds">' .round($r1USupdated/60, 1). ' minutes</span>';
}
elseif(($r1USupdated >= '3600') && ($r1USupdated < '64800')) {
	$r1USupdated = '<span id="minutes">' .round($r1USupdated/60/60, 1). ' hours</span>';
}
elseif(($r1USupdated >= '64800') && ($r1USupdated < '86400')) {
	$r1USupdated = '<span id="hours">' .round($r1USupdated/60/60, 1). ' hours</span>';
}
elseif($r1USupdated >= '86400') {
	$r1USupdated = '<span id="days">' .round($r1USupdated/60/60/24, 2). ' days</span>';
}
					
echo '</div>
<h3>Top users for each region & class</h3>
<div id="t">
<div id="tr" style="border-bottom: 2px solid black;">
<div id="td">#1 of</div>
<div id="td">total AP gained</div>
<div id="td">weapon completed</div>
<div id="td">artifact level</div>
<div id="td">armory</div>
<div id="td">itemlevel</div>
<div id="td">last updated</div>
<div id="td"></div>
</div>
<div id="tr">
<div id="td"><a href="?topregion=EU">EU</a></div>
<div id="td">' .number_format($r1EU['total']). '</div>';
if($data['alevel'] == '34') {
	$weapon = 'yes';
}
else {
	$weapon = 'no';
}
echo '<div id="td">' .$weapon. '</div>
<div id="td">' .$r1EU['alevel']. '</div>
<div id="td"><a href="http://' .$r1EU['region']. '.battle.net/wow/en/character/' .$r1EU['server']. '/' .$r1EU['char']. '/simple">' .$r1EU['char']. ' (' .$r1EU['region']. '-' .$r1EU['server']. ')</a></div>
<div id="td">' .$r1EU['ilvl']. '</div>
<div id="td">' .$r1EUupdated. '</div>
<div id="td"><a href="?updatechar=' .$r1EU['id']. '">update</a></div>
</div>
<div id="tr" style="border-bottom: 2px solid black;">
<div id="td"><a href="?topregion=US">US</a></div>
<div id="td">' .number_format($r1US['total']). '</div>';
if($data['alevel'] == '34') {
	$weapon = 'yes';
}
else {
	$weapon = 'no';
}
echo '<div id="td">' .$weapon. '</div>
<div id="td">' .$r1US['alevel']. '</div>
<div id="td"><a href="http://' .$r1US['region']. '.battle.net/wow/en/character/' .$r1US['server']. '/' .$r1US['char']. '/simple">' .$r1US['char']. ' (' .$r1US['region']. '-' .$r1US['server']. ')</a></div>
<div id="td">' .$r1US['ilvl']. '</div>
<div id="td">' .$r1USupdated. '</div>
<div id="td"><a href="?updatechar=' .$r1US['id']. '">update</a></div>
</div>';

$amountofclasses = mysqli_query($conn, "SELECT `class`, `color`, `class_short` FROM `classes`");
			
$c = '1';
while($class = mysqli_fetch_array($amountofclasses)) {
	${'r1cl' . $c} = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `data1` WHERE `class` = '" .$class['class']. "' ORDER BY `total` DESC LIMIT 1"));
	${'r1cl' . $c . 'updated'} = round(time('now')-${'r1cl' . $c}['timestamp'], 2);
	
	if(${'r1cl' . $c . 'updated'} != time('now')) {
		// IF LESS THAN A MINUTE etc.
		if(${'r1cl' . $c . 'updated'} < '60') {
				$subtimestamp = '<span id="seconds">' .${'r1cl' . $c . 'updated'}. ' seconds</span>';
		}
		elseif((${'r1cl' . $c . 'updated'} > '60') && (${'r1cl' . $c . 'updated'} < '3600')) {
			$subtimestamp = '<span id="seconds">' .round(${'r1cl' . $c . 'updated'}/60, 1). ' minutes</span>';
		}
		elseif((${'r1cl' . $c . 'updated'} > '3600') && (${'r1cl' . $c . 'updated'} < '64800')) {
			$subtimestamp = '<span id="minutes">' .round(${'r1cl' . $c . 'updated'}/60/60, 1). ' hours</span>';
		}
		elseif((${'r1cl' . $c . 'updated'} > '64800') && (${'r1cl' . $c . 'updated'} < '86400')) {
			$subtimestamp = '<span id="hours">' .round(${'r1cl' . $c . 'updated'}/60/60, 1). ' hours</span>';
		}
		elseif(${'r1cl' . $c . 'updated'} >= '86400') {
			$subtimestamp = '<span id="days">' .round(${'r1cl' . $c . 'updated'}/60/60/24, 2). ' days</span>';
		}
	}
	elseif(${'r1cl' . $c . 'updated'} == time('now')) {
		$subtimestamp = '';
	}
				
	echo '<div id="tr">
	<div id="td" style="background-color: ' .$class['color']. ';"><a href="?class=' .$class['class_short']. '">' .$class['class']. '</a></div>
	<div id="td">' .number_format(${'r1cl' . $c}['total']). '</div>';
	if(${'r1cl' . $c}['alevel'] == '34') {
		$weapon = 'yes';
	}
	else {
		$weapon = 'no';
	}
	echo '<div id="td">' .$weapon. '</div>
	<div id="td">' .${'r1cl' . $c}['alevel']. '</div>
	<div id="td"><a href="http://' .${'r1cl' . $c}['region']. '.battle.net/wow/en/character/' .${'r1cl' . $c}['server']. '/' .${'r1cl' . $c}['char']. '/simple">' .${'r1cl' . $c}['char']. ' (' .${'r1cl' . $c}['region']. '-' .${'r1cl' . $c}['server']. ')</a></div>
	<div id="td">' .${'r1cl' . $c}['ilvl']. '</div>
	<div id="td">' .$subtimestamp. '</div>
	<div id="td"><a href="?updatechar=' .${'r1cl' . $c}['id']. '">update</a></div>
	</div>';
	$c++;
}
		
echo '</div>';
			
?>
<?php

echo '<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="author" content="reddit.com/u/xepher1s" />
<meta name="description" content="artifact power ranking">
<meta name="publisher" content="reddit.com/u/xepher1s" />
<meta name="keywords" lang="en" content="artifact power, world of warcraft, legion, wow, 2016, expansion, addon, tool, calc, ap, toplist" />
<meta name="robots" content="index, follow" />
<meta name="language" content="en" />
<meta name="publisher" content="reddit.com/u/xepher1s" />
<meta name="distribution" content="global" />
<meta name="reply-to" content="artifactpowerinfo@gmail.com" />
<meta name="revisit-after" content="7 days" />
<meta name="page-topic" content="artifact power tool" />
<meta name="copyright" content="MIT License reddit.com/u/xepher1s" />
<link rel="apple-touch-icon" href="apple-touch-icon.png"/>
<link rel="apple-touch-icon-precomposed" href="apple-touch-icon-precomposed.png" />
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
<title>Artifact Power Ranking</title>
<link rel="stylesheet" href="css/core.css">
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Carme" />
<script src="js/jquery-1.10.1.min.js"></script>';
?>
<script type="text/javascript">
  $(document).ready(function($) {
    $('#accordion').find('.accordion-toggle').click(function(){

      $(this).next().slideToggle('fast');

      $(".accordion-content").not($(this).next()).slideUp('fast');

    });
  });
</script>

<?php
echo '</head>

<body>

<div id="content">';

	// HEADER
	include('modules/top.php');
	
	// SERVER INFORMATION
	include('modules/serv.php');
	
	// PREPARE SERVER ARRAYS
	$server_EU = array();
	$server_KR = array();
	$server_US = array();
	$server_TW = array();
	
	// CORE CONTENT DIV
	echo '<div id="core">';
		if((isset($_GET['topregion'])) || (isset($_GET['class'])) || (isset($_GET['topregion']) && isset($_GET['topserver']))) {
			include('filter/filter_func.php');
			
			$topregion = $_GET['topregion'];			
			$topserver = $_GET['topserver'];
			$topclass = $_GET['class'];
			
			if(isset($topregion) && ($topregion != '')) {
				$filter = $topregion;
			}
			if(isset($topserver) && ($topserver != ''))  {
				$filter.= ' – ' .$topserver. '';
			}
			if(isset($topclass) && ($topclass != '')) {
				$classname = mysqli_fetch_array(mysqli_query($conn, "SELECT `class` FROM `classes` WHERE `class_short` = '" .$topclass. "'"));
				$classname = $classname['class'];
				if(!isset($topregion)) {
					$filter.= $classname;
				}
				else {
					$filter.= ' – ' .$classname. '';
				}
			}
												
			$i = '1';
			echo '<center>
			<h3>' .$filter. '</h3>
			<div id="t">
			<div id="tr">
			<div id="td">#</div>
			<div id="td">total AP gained</div>
			<div id="td">weapon completed</div>
			<div id="td">artifact level</div>
			<div id="td">armory</div>
			<div id="td">itemlevel</div>
			<div id="td">class</div>
			<div id="td">last updated</div>
			<div id="td"></div>
			</div>';
			
			// REGION & CLASS SELECTED
			include('filter/filter_rc.php');
						
			// CLASS SELECTED
			include('filter/filter_c.php');
									
			// REGION SELECTED
			include('filter/filter_r.php');
			
			// REGION & SERVER SELECTED
			include('filter/filter_rs.php');
			
			// REGION & SERVER & CLASS SELECTED
			include('filter/filter_rsc.php');
			
		}
		
		// EXECUTE SEARCH
		if(isset($_POST['search'])) {
			include('modules/search.php');
		}
		
		// EXECUTE NEW ENTRY		
		if(isset($_POST['exec']) || isset($_GET['updatechar'])) {
			
			if(isset($_POST['exec'])) {
				$char = addslashes($_POST['char']);
				$server = $_POST['server'];
				$region = $_POST['region'];
			}
			
			if(isset($_GET['updatechar']) && is_numeric($_GET['updatechar'])) {
				$char_id = $_GET['updatechar'];
				$fetch_char_data = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `data1` WHERE `id` = '" .$char_id. "'"));
				
				$char = $fetch_char_data['char'];
				$server = $fetch_char_data['server'];
				$region = $fetch_char_data['region'];
			}
				
		
			// INCLUDE ACTUAL RETRIEVAL METHOD
			include('modules/api.php');
			
			if((isset($_GET['updatechar'])) && (($data === FALSE) || ($reached == '0'))) {
				echo '<p id="error">This character is either inactive or has transferred server. Hence it was removed from the database.</p>';
				$delete = mysqli_query($conn, "DELETE FROM `data1` WHERE `id` = '" .$_GET['updatechar']. "'");
			}
			
			// IF API RETURNS..
			
			if(isset($reached) && $reached == '0') {
				echo '<p id="error">Sadly the character information you provided does not link to a level 110 character.</p>';
			}
			
			if(isset($totalgained) && $totalgained == '0') {
				echo '<p id="error">An error occured; most likely the Blizzard API changed and I cannot retrieve achievement progress. Please wait for an update.</p>';
			}
			
			// ON CORRECT INFORMATION
			if(isset($level110) && isset($totalgained)) {
				$cap = '5216130';
				$second_cap = '65256330';
				
				if(strpos($server, '%20') !== false) {
					$server = str_replace('%20', ' ', $server);
				}
				
				$percent = round($totalgained/$cap, 5)*100;
								
				if($alevel > '35') {
					$alevel = '0';
				}
				
				// FETCH AP WORLD RANK
				$apranking = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(`id`) AS `ranking` FROM `data1` WHERE `total` >= '" .$totalgained. "'"));
				$apranking = number_format($apranking['ranking']);
								
				// FETCH ITEM LEVEL WORLD RANK
				$ilvlranking = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(`id`) AS `ilvlranking` FROM `data1` WHERE `ilvl` >= '" .$itemlevel. "'"));
				$ilvlranking = number_format($ilvlranking['ilvlranking']);
				$ilvlsame = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(`id`) AS `same` FROM `data1` WHERE `ilvl` = '" .$itemlevel. "'"));
				$ilvlsame = number_format($ilvlsame['same']-1);
				if($ilvlsame < '0') {
					$ilvlsame = '0';
				}
				
				echo '<br /><div id="result"><span style="background-color: #FD9E84; margin: 0px auto;">DISCLAIMER:<br />the data is fetched from the armory, so it is as accurate as can be.</span></div>
				<p id="result">Updated <a href="http://' .$region. '.battle.net/wow/en/character/' .$server. '/' .$char. '/simple">' .$char. ' (' .$region. '-' .$server. ')</a>.</p>
				<p id="result">Total Artifact Power gained: ' .number_format($totalgained). '</p>
				<p id="result">Artifact level: ' .$alevel. '</p>
				<p id="result">Average itemlevel: ' .$itemlevel. '</p>
				<p id="result">Artifact Power World Rank: <u>' .$apranking. '</u></p>
				<p id="result">Item Level World Rank: <u>' .$ilvlranking. '</u> (although ' .$ilvlsame. ' have the same itemlevel)</p>';
				
				
				$char = ucwords(strtolower($char));
				// CHECK IF USER ALREADY EXISTS
				$searcholduser = mysqli_fetch_array(mysqli_query($conn, "SELECT `id`, `char`, `region`, `server` FROM `data1` WHERE `char` = '" .$char. "' AND `server` = '" .addslashes($server). "' AND `region` = '" .$region. "'"));

				// IF YES, UPDATE INFORMATION TO AVOID DUPLICATES
				if($searcholduser != '') {
					$updateuser = mysqli_query($conn, "UPDATE `data1` SET `class` = '" .$class. "', `total` = '" .$totalgained. "', `percent` = '" .(round($totalgained/$cap, 5)*100). "',  `alevel` = '" .$alevel. "', `ilvl` = '" .$itemlevel. "', `timestamp` = '" .time(). "' WHERE `id` = '" .$searcholduser['id']. "'");		
				
				echo '<p style="color: green;">Hello again! Thanks for revisiting, your stats have been updated.</p>';
				}
				else {
				// ELSE INSERT NEW USER
				$data1 = mysqli_query($conn, "INSERT INTO `data1` (`total`, `percent`, `alevel`, `ilvl`, `timestamp`, `char`, `class`, `region`, `server`) VALUES('" .$totalgained. "', '" .(round($totalgained/$cap, 4)*100). "', '" .$alevel. "', '" .$itemlevel. "', '" .time(). "', '" .$char. "', '" .$class. "', '" .$region. "', '" .addslashes($server). "')");	
				}
			}
			
			echo '</div>';			
		}
		
		// LANDING PAGE	
		if((!isset($_POST['exec'])) && (!isset($_GET['updatechar'])) && (!isset($_GET['topregion'])) && (!isset($_GET['topserver'])) && (!isset($_GET['class']))) {
			// CHECK AMOUNT OF UNIQUE ENTRIES IN DP
			$users = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(`id`) AS `chars` FROM `data1`"));
			
			// SUPPORTED REGIONS
			$regions = array('EU', 'US');
			
			foreach($regions as $region) {
				// FETCH ALL SERVERS OF EACH REGION
				$sql = mysqli_query($conn, "SELECT * FROM `server_" .$region. "` ORDER BY `server` ASC");
				// SHOVE INTO ARRAY
				while($servers = mysqli_fetch_array($sql)) {				
					$servervar = 'server_' .$region. '';
					array_push($$servervar, $servers['server']);					
				}
			}
			// CORE RETRIEVAL & SEARCH FORM
			echo '<form action="" method="POST">
			<p>To retrieve your total Artifact Power gained or search for an already existing character outside of the top25, please enter your information:</p>
			<input type="text" name="char" value="" placeholder="character name" maxlength="12"/>
			<select name="region" id="region">';
			
			foreach($regions as $region) {
					echo '<option value="' .$region. '">' .$region. '</option>';
				}
			
			echo '</select>
			<select name="server" id="server">
				
			</select>	
			<button type="submit" name="exec">Retrieve</button>
			<button type="submit" name="search">Search</button>
			</form>
			<hr>';
			
			// COMPARISON FUNCTION
					
			include('modules/compare.php');
			echo '<hr>';			
			
			// START OF TOPLIST INCLUDING SELECTION FORM
			echo '<h3>Toplist</h3>
			<p>Select region and/or server to filter from:</p>
			<form action="" method="GET">
			<select name="topregion">';
			
			foreach($regions as $region) {
				echo '<option value="' .$region. '">' .$region. '</option>';
			}
			echo '</select>
			<input name="topserver"	style="width: 140px" value="" maxlength="30" placeholder="servername case sensitive!" />
			<button type="submit" onUnfocus="send()">Select</button>
			</form>
			<form action="" method="GET">
			<select name="class">';
			
			$classes = mysqli_query($conn, "SELECT `class`, `class_short` FROM `classes` ORDER BY `class` ASC");
			
			while($class = mysqli_fetch_array($classes)) {
				echo '<option value="' .$class['class_short'].'">' .$class['class']. '</option>';
			}
			
			echo '</select>
			<button type="submit">Filter class</button>
			</form>
			<center>
			<div id="t">
			<div id="tr" style="border-bottom: 2px solid black;">
			<div id="td">#</div>
			<div id="td">total AP gained</div>
			<div id="td">weapon completed</div>
			<div id="td">artifact level</div>
			<div id="td">armory</div>
			<div id="td">itemlevel</div>
			<div id="td">class</div>
			<div id="td">last updated</div>
			<div id="td"></div>
			</div>';
	
			$i = '1';
			$query = mysqli_query($conn, "SELECT `id`, `total`, `alevel`, `ilvl`, `timestamp`, `char`, `class`, `region`, `server` FROM `data1` WHERE `char` != '' AND `region` != '' AND `server` != '' ORDER BY `total` DESC LIMIT 25");
			while($data = mysqli_fetch_array($query)) {
				if(($data['server'] != '') || ($data['char'] != '' ) || ($data['region'] != '')) {	
					
					$classshort = mysqli_fetch_array(mysqli_query($conn, "SELECT `class_short`, `color` FROM `classes` WHERE `class` = '" .$data['class']. "'"));
								
					// TIMESTAMP LAST UPDATE IN SECONDS
					$timesincelastupdate = round(time('now')-$data['timestamp'], 2);
					
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
						$effectivetime = '<span id="days">' .round($timesincelastupdate/60/60/24, 2). ' years</span>';
					}
										
					if($data['alevel'] == '0') {
						$data['alevel'] = 'unknown';
					}
																	
					echo '<div id="tr">
					<div id="td">' .$i. '</div>
					<div id="td">' .number_format($data['total']). '</div>';
					if($data['alevel'] == '34') {
						$weapon = 'yes';
					}
					else {
						$weapon = 'no';
					}
					echo '<div id="td">' .$weapon. '</div>
					<div id="td">' .$data['alevel']. '</div>
					<div id="td"><a href="http://' .$data['region']. '.battle.net/wow/en/character/' .$data['server']. '/' .$data['char']. '/simple">' .$data['char']. ' (' .$data['region']. '-' .$data['server']. ')</a></div>
					<div id="td">' .$data['ilvl']. '</div>
					<div id="td" style="background-color: ' .$classshort['color']. ';"><a href="?class=' .$classshort['class_short']. '">' .$data['class']. '</a></div>
					<div id="td">' .$effectivetime. '</div>
					<div id="td"><a href="?updatechar=' .$data['id']. '">update</a></div>
					</div>';
					$i++;
				}
			}
			
			// TOPLIST FOR REGION & CLASSES
			include('modules/core_bot_globalbest.php');
			echo '<br />';
			
			// % USER DISTRIBUTION GRAPH
			include('modules/graph.php');
			
			// ITEMLEVEL CHART
			include('modules/ilvl_avg.php');
			
			// PIE CHART
			include('modules/piechart.php');
					
			// GENERAL STATISTICS
			$averageapgained = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(`total`) AS `sumtotal` FROM `data1`"));
			$averageapgained = round($averageapgained['sumtotal']/$users['chars'], 0);
				
			$sumtotal = mysqli_fetch_array(mysqli_query($conn, "SELECT `total`, SUM(`total`) AS `sumtotal` FROM `data1`"));
			
			$sumEUusers = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(`id`) AS `sumEUusers` FROM `data1` WHERE `region` = 'EU'"));
			$sumUSusers = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(`id`) AS `sumUSusers` FROM `data1` WHERE `region` = 'US'"));
			
			$averageEUgained = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(`total`) AS `sumtotalEU` FROM `data1` WHERE `region` = 'EU'"));
			$averageUSgained = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(`total`) AS `sumtotalUS` FROM `data1` WHERE `region` = 'US'"));
			
			$itemlevelsum = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(`ilvl`) AS `totalilvl` FROM `data1`"));
			$alevelsum = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(`alevel`) AS `totalalevel` FROM `data1`"));
				
			$cap = '5216130';
						
			echo '<h3>Statistics</h3>
			<div id="t">
			<div id="tr" style="border-bottom: 2px solid black;">
			<div id="td">unique profiles</div>
			<div id="td">total AP gained</div>
			<div id="td">average AP gained</div>
			<div id="td">EU users</div>
			<div id="td">average AP gained EU</div>
			<div id="td">US users</div>
			<div id="td">average AP gained US</div>
			<div id="td">average itemlevel</div>
			<div id="td">average artifact level</div>
			</div>
			<div id="tr">
			<div id="td">' .number_format($users['chars']). '</div>
			<div id="td"><span title="' .number_format($sumtotal['sumtotal']). '">' .number_format($sumtotal['sumtotal']/1000000000). ' billion</span></div>
			<div id="td">' .number_format($averageapgained). '</div>
			<div id="td">' .number_format($sumEUusers['sumEUusers']). '</div>
			<div id="td">' .number_format(round($averageEUgained['sumtotalEU']/$sumEUusers['sumEUusers'], 0)). '</div>
			<div id="td">' .number_format($sumUSusers['sumUSusers']). '</div>
			<div id="td">' .number_format(round($averageUSgained['sumtotalUS']/$sumUSusers['sumUSusers'], 0)). '</div>
			<div id="td">' .round($itemlevelsum['totalilvl']/$users['chars'], 0). '</div>
			<div id="td">' .round($alevelsum['totalalevel']/$users['chars'], 0). '</div>
			</div>
			</div>
			<p>All Artifact Power tracked on this page would be enough to fill out ' .number_format(round($sumtotal['sumtotal']/$cap, 0)). ' Artifact Weapons.<br />That means, you would in average need the progress of ' .round($cap/$averageapgained, 0). ' players for one completed.</p></center></div>';
			?>
			<script type="text/javascript">
			server_EU=new Array(<?php echo str_replace(array('[', ']'), '', htmlspecialchars(json_encode($server_EU), ENT_NOQUOTES)); ?>);
			server_US=new Array(<?php echo str_replace(array('[', ']'), '', htmlspecialchars(json_encode($server_US), ENT_NOQUOTES)); ?>);
		
			populateSelect();
			
			$(function() {
				$('#region').change(function(){
					populateSelect();
				});
			});
			
			function populateSelect(){
				region=$('#region').val();
				$('#server').html('');
		
			if(region=='EU'){
				server_EU.forEach(function(t) { 
					$('#server').append('<option>'+t+'</option>');
				});
			}
		
			if(region=='US'){
				server_US.forEach(function(t) {
					$('#server').append('<option>'+t+'</option>');
					});
				}
			}
			
			</script>	
			
  			<?php
		}
	include('modules/footer.php');	
	echo '</div>
</body>
</html>';

?>
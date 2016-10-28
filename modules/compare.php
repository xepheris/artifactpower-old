<?php
include('serv.php');

$server_EU = array();
$server_US = array();

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

$server = array_merge($server_EU, $server_US);
$server = array_unique($server);
sort($server);

if((isset($_GET['char1'])) && ($_GET['char1'] != '')) {
	echo '<center><br />';
	
	$amount = array();
	
	// CHECK HOW MANY CHARACTERS ARE SET
	for($i = '1'; $i <= '5'; $i++) {
		// IF CHARACTER n IS NOT EMPTY
		if((isset($_GET['char' .$i. ''])) && ($_GET['char' .$i. ''] != '')) {	
			// SET EASIER VAR (char2, char3, ...)	
			${'char' .$i. ''} = $_GET['char' .$i. ''];
			
			// IF REGION IS NOT EMPTY (ANTI TAMPERING), SET EASIER VAR
			if((isset($_GET['reg' .$i. ''])) && ($_GET['reg' .$i. ''] != '')) {
				${'reg' .$i. ''} = $_GET['reg' .$i. ''];
				// IF REGION IS NOT EU OR US AS IT SHOULD BE
				if((${'reg' .$i. ''} != 'EU') && (${'reg' .$i. ''} != 'US')) {
					echo '<p id="error">Congratulations, you successfully altered the region to ' .${'reg' .$i. ''}. '. Now stop trying to manipulate my page, thanks.</p>
					</div>';
					include('footer.php');
					die();					
				}
			}
			// IF REGION IS EMPTY
			else {
				echo '<p id="error">You somehow forgot the region of character ' .$i. ', which is weird, because there is a predefined value. Try again.</p>
				</div>';
				include('footer.php');
				die();
			}
			
			// IF SERVER IS NOT EMPTY, SET EASIER VAR
			if((isset($_GET['ser' .$i. ''])) && ($_GET['ser' .$i. ''] != '')) {
				${'ser' .$i. ''} = $_GET['ser' .$i. ''];
								
				${'ser' .$i. ''} = addslashes(${'ser' .$i. ''});
				$check_server_existence = mysqli_fetch_array(mysqli_query($conn, "SELECT `server` FROM `server_" .${'reg' .$i. ''}. "` WHERE `server` = '" .${'ser' .$i. ''}. "'"));
				${'ser' .$i. ''} = stripslashes(${'ser' .$i. ''});
				
				if($check_server_existence == '') {
					echo '<p id="error">This server does not exist in the region you selected or not at all if you tried to input something awkward.</p>
					<br /></div>';
					include('footer.php');
					die();
				}
			}
			// IF SERVER IS EMPTY
			else {
				echo '<p id="error">You somehow forgot the server of character ' .$i. ', which is weird, because there is a predefined value. Try again.</p>
				</div>';
				include('footer.php');
				die();
			}			
		}
		
		// CHECK CHARACTER EXISTENCE
		if((${'char' .$i. ''} != '') && (${'reg' .$i. ''} != '') && (${'ser' .$i. ''} != '')) {
			${'ser' .$i. ''} = addslashes(${'ser' .$i. ''});
			$check_existence = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `data1` WHERE `char` = '" .${'char' .$i. ''}. "' AND `region` = '" .${'reg' .$i. ''}. "' AND `server` = '" .${'ser' .$i. ''}. "'"));
			${'ser' .$i. ''} = stripslashes(${'ser' .$i. ''});
			if($check_existence == '') {
				echo '<p id="error">The character "' .${'char' .$i. ''}. '" - (' .${'reg' .$i. ''}. '-' .${'ser' .$i. ''}. ') does not exist in the database (yet). Please add the character on the <a href="http://artifactpower.info">main page</a> first.</p>';
			}
			elseif($check_existence != '') {
				 ${'comparedata' .$i. ''} = $check_existence;
				 array_push($amount, $i);
			}
		}
	}
	
	if(!empty($amount)) {
		
		// SORT BY HIGHEST TOTAL GAINED WITHIN SET VARIABLES WORKAROUND WITHOUT NEW DB QUERIES
		// FETCH TOTAL VALUE
		$totalarray = array();
		foreach($amount as $i) {
			$totalarray['total' .$i. ''] = ${'comparedata' .$i. ''}['total'];
		}
		// SORT BY HIGHEST VALUE WITHIN ARRAY
		arsort($totalarray);
		
		// CLEAR ARRAY
		$amount = array();
		
		// FOR EACH NEWLY SORTED, SUBSTR 'total' FROM 'total$i'. BEFORE: total2 AFTER: 2; THEN SHOVE INTO NEW ARRAY
		foreach($totalarray as $total => $value) {
			$new_amount = substr($total, '5');
			array_push($amount, $new_amount);	
		}
		
		echo '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<div id="t">
		<div id="tr" style="border-bottom: 2px solid black;">
		<div id="td">#</div>
		<div id="td">character name</div>
		<div id="td">total AP gained</div>
		<div id="td">weapon completed</div>
		<div id="td">artifact level</div>
		<div id="td">armory link</div>
		<div id="td">itemlevel</div>
		<div id="td">class</div>
		<div id="td">last updated</div>
		<div id="td"></div>
		</div>';
				
		foreach($amount as $number) {
		
			$classshort = mysqli_fetch_array(mysqli_query($conn, "SELECT `class_short`, `color` FROM `classes` WHERE `class` = '" .${'comparedata' .$number. ''}['class']. "'"));
								
			// TIMESTAMP LAST UPDATE IN SECONDS
			$timesincelastupdate = round(time('now')-${'comparedata' .$number. ''}['timestamp'], 2);
					
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
		
			echo '<div id="tr">
			<div id="td">' .$number. '</div>
			<div id="td">' .${'comparedata' .$number. ''}['char']. '</div>
			<div id="td">' .number_format(${'comparedata' .$number. ''}['total']). '</div>';
			if(${'comparedata' .$number. ''}['alevel'] == '34') {
				$weapon = 'yes';
			}
			else {
				$weapon = 'no';
			}
			echo '<div id="td">' .$weapon. '</div>
			<div id="td">' .${'comparedata' .$number. ''}['alevel']. '</div>
			<div id="td"><a href="http://' .${'comparedata' .$number. ''}['region']. '.battle.net/wow/en/character/' .${'comparedata' .$number. ''}['server']. '/' .${'comparedata' .$number. ''}['char']. '/simple">' .${'comparedata' .$number. ''}['char']. ' (' .${'comparedata' .$number. ''}['region']. '-' .${'comparedata' .$number. ''}['server']. ')</a></div>
			<div id="td">' .${'comparedata' .$number. ''}['ilvl']. '</div>
			<div id="td" style="background-color: ' .$classshort['color']. ';">' .${'comparedata' .$number. ''}['class']. '</div>
			<div id="td">' .$effectivetime. '</div>
			<div id="td"><a href="http://artifactpower.info/index.php?updatechar=' .${'comparedata' .$number. ''}['id']. '">update now</a></div>
			</div>';	
		}
		echo '</div><br />';	
		?>
		<script type="text/javascript">
		google.charts.load('current', {packages: ['corechart', 'bar']});
		google.charts.setOnLoadCallback(drawAxisTickColors);

		function drawAxisTickColors() {
			var data = google.visualization.arrayToDataTable([
			['Character', 'AP gained', { role: 'style' }],
			<?php
			
			foreach($amount as $number) {
				$classshort = mysqli_fetch_array(mysqli_query($conn, "SELECT `colorhex` FROM `classes` WHERE `class` = '" .${'comparedata' .$number. ''}['class']. "'"));
				echo "['" .${'comparedata' .$number. ''}['char']. "', " .${'comparedata' .$number. ''}['total']. ", '" .$classshort['colorhex']. "'],";
			}
			?>
			]);

		var options = {
			title: 'Total AP gained comparison between selected characters',
			focusTarget: 'category',
			backgroundColor: '#D6CDAE',
			legend: { position: 'none' },
			vAxis: { baseline: 0, gridlines: { color: 'grey' } },	
		};

		var chart = new google.visualization.ColumnChart(document.getElementById('comparison_graph'));
		chart.draw(data, options);
		}
		</script>
	<?php
		echo '<div id="comparison_graph" style="width: 75%; height: 400px"></div></center></div>';
		include('footer.php');
		die();
	}
	elseif(empty($amount) && $char1 == '' && $char2 == '' && $char3 == '' && $char4 == '' && $char5 == '') {
		echo '<p id="error">You forgot to insert characters.</p>
		<br />
		</div>';
		include('footer.php');
		die();
	}
	
}
elseif(isset($_GET['char1']) && $_GET['char1'] == '') {
	echo '<br /><p id="error">You forgot the name of the first character to be compared with the other(s).<br />
	<a href="compare.php">Go back and try again.</a></p>';
}

if(!isset($_GET['char1'])) {
	echo '<div id="accordion">
	<h3 class="accordion-toggle">click here to compare up to five different profiles at once!</h3>
	<div class="accordion-content default">
	<span style="background-color: #FD9E84;">Make sure all characters are already in the database!<br />Upper and lower case are irrelevant, but the character name must include all special characters like î, à or similar.<br /></span><br /><span style="background-color: #FD9E84;">It is not necessary to fill out all five, you can also compare only two or three.</span>
	
	<p>Enter information for the characters to compare:</p>
	<form action="" method="GET">
	<input type="text" placeholder="character1" name="char1" />
	<select id="reg1" name="reg1">
	<option value="EU">EU</option>
	<option value="US">US</option>
	</select>
	<select name="ser1" id="ser1">
	</select>
	<br />
	<input type="text" placeholder="character2" name="char2" />
	<select id="reg2" name="reg2">
	<option value="EU">EU</option>
	<option value="US">US</option>
	</select>
	<select name="ser2" id="ser2">
	</select>
	<br />
	<input type="text" placeholder="character3" name="char3" />
	<select id="reg3" name="reg3">
	<option value="EU">EU</option>
	<option value="US">US</option>
	</select>
	<select name="ser3" id="ser3">
	</select>
	<br />
	<input type="text" placeholder="character4" name="char4" />
	<select id="reg4" name="reg4">
	<option value="EU">EU</option>
	<option value="US">US</option>
	</select>
	<select name="ser4" id="ser4">
	</select>
	<br />
	<input type="text" placeholder="character5" name="char5" />
	<select id="reg5" name="reg5">
	<option value="EU">EU</option>
	<option value="US">US</option>
	</select>
	<select name="ser5" id="ser5">
	</select>
	<br /><br />
	<input type="button" value="Set other region & server to first region & server" onClick="selectFunction()" />
	<br /><br />
	<button type="submit">Compare</button>
	</form>
	
	<script type="text/javascript">
	var selectFunction = function() {
		var reg1 = document.getElementById("reg1").value;
		var ser1 = document.getElementById("ser1").value;
		
		document.getElementById("reg2").value = reg1;
		document.getElementById("reg3").value = reg1;
		document.getElementById("reg4").value = reg1;
		document.getElementById("reg5").value = reg1;
		
		document.getElementById("ser2").value = ser1;
		document.getElementById("ser3").value = ser1;
		document.getElementById("ser4").value = ser1;
		document.getElementById("ser5").value = ser1;
	};
	</script>
	</div>
	</div>';
}
?>

<script type="text/javascript">
	server=new Array(<?php echo str_replace(array('[', ']'), '', htmlspecialchars(json_encode($server), ENT_NOQUOTES)); ?>);
			
	populateSelect1();
	populateSelect2();
	populateSelect3();
	populateSelect4();
	populateSelect5();
			
	$(function() {
		$('#reg1').change(function(){
			populateSelect1();
		});
		
		$('#reg2').change(function(){
			populateSelect2();
		});
		
		$('#reg3').change(function(){
			populateSelect3();
		});
		
		$('#reg4').change(function(){
			populateSelect4();
		});
		
		$('#reg5').change(function(){
			populateSelect5();
		});
	});
			
	function populateSelect1(){
		region=$('#reg1').val();
		$('#ser1').html('');
		
		if(region=='EU'){
			server.forEach(function(t) { 
				$('#ser1').append('<option>'+t+'</option>');
			});
		}
		
		if(region=='US'){
			server.forEach(function(t) {
				$('#ser1').append('<option>'+t+'</option>');
			});
		}
	}
	function populateSelect2(){	
		region=$('#reg2').val();
		$('#ser2').html('');
		
		if(region=='EU'){
			server.forEach(function(t) { 
				$('#ser2').append('<option>'+t+'</option>');
			});
		}
		
		if(region=='US'){
			server.forEach(function(t) {
				$('#ser2').append('<option>'+t+'</option>');
			});
		}
	}
	function populateSelect3(){	
		region=$('#reg3').val();
		$('#ser3').html('');
		
		if(region=='EU'){
			server.forEach(function(t) { 
				$('#ser3').append('<option>'+t+'</option>');
			});
		}
		
		if(region=='US'){
			server.forEach(function(t) {
				$('#ser3').append('<option>'+t+'</option>');
			});
		}
	}	
	function populateSelect4(){	
		region=$('#reg4').val();
		$('#ser4').html('');
		
		if(region=='EU'){
			server.forEach(function(t) { 
				$('#ser4').append('<option>'+t+'</option>');
			});
		}
		
		if(region=='US'){
			server.forEach(function(t) {
				$('#ser4').append('<option>'+t+'</option>');
			});
		}
	}	
	function populateSelect5(){	
		region=$('#reg5').val();
		$('#ser5').html('');
		
		if(region=='EU'){
			server.forEach(function(t) { 
				$('#ser5').append('<option>'+t+'</option>');
			});
		}
		
		if(region=='US'){
			server.forEach(function(t) {
				$('#ser5').append('<option>'+t+'</option>');
			});
		}
	}
</script>	
<?php

// SPAM BLOCK (ONE UPDATE PER CHARACTER PER 15 MINUTES)

if(strpos($server, "'") !== false) {
	$server = addslashes($server);
}


$fetcholdtimestamp = mysqli_fetch_array(mysqli_query($conn, "SELECT `timestamp` FROM `data1` WHERE `char` = '" .$char. "' AND `server` = '" .$server. "' AND `region` = '" .$region. "'"));
$oldtimestamp = $fetcholdtimestamp['timestamp'];

$calcdiff = time('now')-$oldtimestamp;

if($calcdiff >= '900') {
	// ENABLE SSL
	$arrContextOptions=array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, ),);  
	
	// API KEY
	$api = 'zs94heuda8vnyd6tks5wj4pq57939s5p';
	
	// REMOVE SPACES IN SERVER NAME TO PREVENT BUGS IN URL
	if(strpos($server, ' ') !== false) {
		$server = str_replace(' ', '%20', $server);
	}
	// REMOVE SLASHES IN SERVER NAME TO ALLOW ACTUAL SEARCH AGAIN
	$server = stripslashes($server);
	
	// ARMORY API LINK ACHIEVEMENTS
	$url = 'https://' .$region. '.api.battle.net/wow/character/' .$server. '/' .$char. '?fields=achievements&locale=en_GB&apikey=' .$api. '';
	
	$data = @file_get_contents($url, false, stream_context_create($arrContextOptions));
	
	if($data === FALSE) {
		echo '<p id="error">Sorry, either the armory is busy at the moment or the data you entered is simply wrong.<br />If you are sure your data is correct, retry. Here is what you entered:</p>
		<p id="error">Name: "' .$char. '"</p>
		<p id="error">Region: "' .$region. '"</p>
		<p id="error">Server: "' .$server. '"</p>
		<p id="error">Check for yourself: <a href="http://' .$region. '.battle.net/wow/en/character/' .$server. '/' .$char. '/simple">armory link</a></p>';
	}
		
	elseif($data != '') {
		$data = json_decode($data, true);
		
		// RETRIEVE LEVEL110 STATUS
		$level = $data['level'];
		
		if($level == '110') {
			
			$level110 = $level;
		
			// FETCH CLASS
			$class = $data['class'];
			$class = mysqli_fetch_array(mysqli_query($conn, "SELECT `class` FROM `classes` WHERE `id` = '" .$class. "'"));
			$class = $class['class'];
									
			// RETRIEVE ACHIEVEMENT STATUS CLASS HALL BELT -> CRITERIA QUANTITY VALUE = TOTAL AP GAINED
			// RETRIEVE ACHIEVEMENT STATUS 'POWER REALIZED' -> CRITERIA QUANTITY VALUE = HIGHEST ARTIFACT LEVEL
			
			$key = array_search('30103', $data['achievements']['criteria']);
			$key2 = array_search('29395', $data['achievements']['criteria']);
			
			if($key != '') {
				$criterias = array();
				array_push($criterias, $data['achievements']['criteriaQuantity']);
				$criterias = $criterias['0'];
				$totalgained = $criterias[$key];
				$alevel = $criterias[$key2];
			}
			elseif($key == '') {
				$totalgained = '0';
				$alevel = '0';
			}
		}
		// IF CHARACTER EXISTS BUT IS NOT 110
		elseif($level != '110') {
			$reached = '0';
		}
		
		// CONTINUE TO FETCH GEARSCORE
		// ARMORY API LINK ITEMS
		$url = 'https://' .$region. '.api.battle.net/wow/character/' .$server. '/' .$char. '?fields=items&locale=en_GB&apikey=' .$api. '';
	
		$data = @file_get_contents($url, false, stream_context_create($arrContextOptions));
		$data = json_decode($data, true);
		
		$itemlevel = $data['items']['averageItemLevel'];	
	}	
}
elseif($calcdiff < '900') {
	echo '<p id="error">Sorry, you can update your character only once every 15 minutes. You can refresh in ' .(900-$calcdiff). ' seconds.</p>';
}

?>
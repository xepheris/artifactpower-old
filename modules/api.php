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
	$api = '';
	
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
									
			// RETRIEVE ACHIEVEMENT STATUS CLASS HALL BELT -> CRITERIA QUANTITS VALUE = TOTAL AP GAINED
			
			$key = array_search('30103', $data['achievements']['criteria']);
			
			if($key != '') {
				$criterias = array();
				array_push($criterias, $data['achievements']['criteriaQuantity']);
				$criterias = $criterias['0'];
				$totalgained = $criterias[$key];
			}
			elseif($key == '') {
				$totalgained = '0';
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
			
		// CONTINUE TO FETCH ARTIFACT LEVEL
		
		$quality = $data['items']['mainHand']['quality'];
		// IF CURRENT WEAPON IS ARTEFACT LEVEL
		if($quality == '6') {
			// IF MAINHAND IS WEAPON WITH RELICS
			if(!empty($data['items']['mainHand']['relics'])) {
				$sum_this_traits_array = array();
				$sum_relic_amount = array();
			
				// CHECK AMOUNT OF RELICS (3 WHEN CLASS CAMPAIGN COMPLETED, 2 ELSE)
				$relic_amount_array = $data['items']['mainHand']['relics'];
						
				for($i = '0'; $i <= '3'; $i++) {
					if(!empty($relic_amount_array[$i])) {
						array_push($sum_relic_amount, '1');
					}
				}
			
				// SUM ARTIFACT TRAITS
				$source_traits_array = $data['items']['mainHand']['artifactTraits'];
			
				for($i = '0'; $i <= '34'; $i++) {
					array_push($sum_this_traits_array, $source_traits_array[$i]['rank']);
				}
			
				$alevel = array_sum($sum_this_traits_array);
				$relicsum = array_sum($sum_relic_amount);

				// SUBSTRACT IMPROVED TRAITS
				$alevel = $alevel-$relicsum;
				if($alevel < '0') {
					$alevel = '0';
				}
			}
			// ELSE IF OFF HAND IS WEAPON WITH RELICS
			elseif(!empty($data['items']['offHand']['relics'])) {
				$sum_this_traits_array = array();
				$sum_relic_amount = array();
			
				// CHECK AMOUNT OF RELICS (3 WHEN CLASS CAMPAIGN COMPLETED, 2 ELSE)
				$relic_amount_array = $data['items']['offHand']['relics'];
						
				for($i = '0'; $i <= '3'; $i++) {
					if(!empty($relic_amount_array[$i])) {
						array_push($sum_relic_amount, '1');
					}
				}
			
				// SUM ARTIFACT TRAITS
				$source_traits_array = $data['items']['offHand']['artifactTraits'];
			
				for($i = '0'; $i <= '34'; $i++) {
					array_push($sum_this_traits_array, $source_traits_array[$i]['rank']);
				}
			
				$alevel = array_sum($sum_this_traits_array);
				$relicsum = array_sum($sum_relic_amount);

				// SUBSTRACT IMPROVED TRAITS
				$alevel = $alevel-$relicsum;		
				if($alevel < '0') {
					$alevel = '0';
				}		
			}
		}
		elseif($quality != '6') {
			$alevel = '0';
		}		
	}	
}
elseif($calcdiff < '900') {
	echo '<p id="error">Sorry, you can update your character only once every 15 minutes. You can refresh in ' .(900-$calcdiff). ' seconds.</p>';
}

?>
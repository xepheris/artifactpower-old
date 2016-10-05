<?php

function topserversearch() {
	global $conn, $topregion, $topserver;
	
	$topserver = addslashes($topserver);
	
	$servername = mysqli_fetch_array(mysqli_query($conn, "SELECT `server` FROM `server_" .$topregion. "` WHERE `server` = '" .$topserver. "'"));
	$servername = $servername['server'];
	
	$topserver = stripslashes($topserver);
				
	if($servername == '') {
		echo '</div><p id="error">No tampering allowed, sorry. In case you see this without manipulation intent, you probably mispelled something.</p>
		</div>
		</div>
		</center>';
		include('modules/footer.php');
		die();
	}
}

function topclasssearch() {
	global $conn, $topclass;
	
	$classname = mysqli_fetch_array(mysqli_query($conn, "SELECT `class` FROM `classes` WHERE `class_short` = '" .$topclass. "'"));
	$classname = $classname['class'];
				
	if($classname == '') {
		echo '</div><p id="error">No tampering allowed, sorry. In case you see this without manipulation intent, you probably mispelled something.</p>
		</div>
		</div>
		</center>';
		include('modules/footer.php');
		die();
	}
}

function topregionsearch() {
	global $conn, $topregion;
	
	$topregionsearch = mysqli_query($conn, "SELECT * FROM `data1` WHERE `region` = '" .$topregion. "' ORDER BY `total` DESC LIMIT 25");
				
	if($topregionsearch == '') {
		echo '</div><p id="error">No tampering allowed, sorry. In case you see this without manipulation intent, you probably mispelled something.</p>
		</div>
		</div>
		</center>';
		include('modules/footer.php');
		die();
	}
}

?>
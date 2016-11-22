<div id="start">
	<h1>Artifact Power Ranking</h1>
	<?php
	if($_SERVER['PHP_SELF'] == '/modules/changelog.php') {
		echo '<h2>Changelog</h2>
		<p id="cent"><a href="http://artifactpower.info/">Back</a></p>';
	}
	
	if((($_SERVER['PHP_SELF'] != '/modules/changelog.php') && (isset($_POST['exec']) || isset($_GET['updatechar']) || isset($_GET['char1']) || isset($_GET['topregion']) || isset($_GET['topserver']) || isset($_GET['class']) || isset($_POST['search'])) || $_SERVER['PHP_SELF'] == '/modules/compare.php')) {
		echo '<p id="cent"><a href="http://artifactpower.info/">Back</a></p>';
	}
	?>
	<p id="cent"><b>NEW: <a href="http://artifactpower.info/mythic">Completed Mythic Dungeon Comparison subsite</a></b></p>
    <p id="cent"><b>Something's missing, bugged or you have an idea for improvement? Message me on reddit or add xhs207ga#2703 on battle.net</b></p>
</div>
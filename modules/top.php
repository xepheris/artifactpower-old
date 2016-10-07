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
    <p id="cent"><b>NEWS: itemlevel graph included! check below</b></p>
    <p id="cent"><b>ZIP file up to date. Many bugfixes. Whoever runs a regular update on the whole DB, please message me, I'd like to implement that!</b></p>
    <p id="cent"><b>Something's missing, bugged or you have an idea for improvement? Message me on reddit or add xhs207ga#2703 on battle.net</b></p>
</div>
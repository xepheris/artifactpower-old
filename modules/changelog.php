<html doctype="html" lang="en">
<head>
<meta charset="utf-8">
<meta content="reddit.com/u/xepher1s" name="author">
<meta content="artifact power calculator" name="description">
<title>Artifact Power Calculator - Changelog</title>
<link rel="stylesheet" href="../css/core.css">
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Carme" />
</head>

<body>

<div id="top">
	<?php
	include('top.php');
	?>
	<div id="core">
    	<div id="changelog">
    	<?php
		$changelogarray = array('v4.2 (11/08/16)' => 'new stream graph, some small bugfixes',
		'v4.1 (10/31/16)' => 'visual improvements',
		'v4.0 (10/28/16)' => 'removal of %-related columns due to naturally given inaccuracy :( updated everything else to reflect continuity',
		'v3.3 (10/26/16)' => 'prepared page for bonus trait progression',
		'v3.2 (10/25/16)' => 'minor textfixes :^) also included world ranking on individual update pages',
		'v3.1 (10/19/16)' => 'accurate artifact level API call',
		'v3.0 (10/18/16)' => 'timestamp colorization updated to reflect automatic DB updates (every 18 hours)',
		'v2.9 (10/17/16)' => 'updating a moved character deletes faulty entry now & regular DB updates for each class every 15 minutes',
		'v2.8 (10/14/16)' => 'number formatting, another percentile, several bugfixes',
		'v2.7 (10/07/16)' => 'added itemlevel graph since requested',
		'v2.6 (10/05/16)' => 'bugfixed compare-module to allow servers with apostrophes', 
		'v2.5 (10/04/16)' => 'updated .zip, many bugfixes',
		'v2.4 (09/28/16)' => '<b>implementation of compare function within a jQuery-free accordion, new columns: artifact level & itemlevel</b> bugfixes, recoloring gridlines of graphs',
		'v2.3 (09/27/16)' => 'y-axis of distribution graph will not show values below 0 since they are not possible; filters always show top50 of selected filter',
		'v2.2 (09/26/16)' => 'removal of "daily gain"-column (if selected character is second character to reach level 110 of this user, it will take the global achievement, not the individual)<br /><b>new function:</b> search<br />
		<b>new function:</b> click to update-column<br />0.25 jumps instead of 0.5 for user distribution graph',
		'v2.1 (09/23/16)' => '<b>implementation of user-updated graph</b>',
		'v2.0 (09/21/16)' => 'finalization, modularization, bug fixes & RELEASE', 
		'v1.2 (09/20/16)' => 'additional filter options',
		'v1.1 (09/19/16)' => 'table updates',
		'v1.0 (09/16/16)' => 'visual redesign, donate button, new formula (exact values!)',
		'v0.5 (09/14/16)' =>'daily gain table, anti cheat formula',
		'v0.4 (09/13/16)' => 'security updates, revisiting the page actually updates your toplist entry now, unique user count',
		'v0.3 (09/12/16)' => 'database with toplist, minor text fixes, bugfixes)',
		'v0.2 (09/08/16)' => 'some visual improvement, formula insight at last step',
		'v0.1 (09/07/16)' => 'everything');
		
		$hallofhonorarray = array('<a href="http://reddit.com/u/kerradeph">kerradeph</a>' => 'Bugfix on old page',
		'<a href="http://eu.battle.net/wow/en/character/Blackmoore/Magtokk/simple">Magtokk (EU-Blackmoore)</a>' => 'additional filters, testing',
		'<a href="http://eu.battle.net/wow/en/character/Blackmoore/Phìne/simple">Phìne (EU-Blackmoore)</a>' => 'beta testing & ideas',
		'<a href="http://eu.battle.net/wow/en/character/Kazzak/Znufflessd/simple">Znufflessd (EU-Kazzak)</a>' => '<a href="https://github.com/Znuff/artifactpower-feeder">python script</a> for scrapping wowprogress realms');
		
		foreach($changelogarray as $date => $info) {
			echo '<p><u>' .$date. '</u></p>
			<p>' .$info. '</p>';
		}
		
		echo '<hr><h3>Hall of Honor</h3>';
		
		foreach($hallofhonorarray as $person => $info) {
			echo '<p><u>' .$person. '</u></p>
			<p>' .$info. '</p>';
		}
        ?>
        </div>
	</div>

	<?php
	include('footer.php');
	?>
</div>

</body>
</html>
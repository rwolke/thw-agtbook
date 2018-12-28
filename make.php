<?php
	
	define('DEBUG' ,false);
	define('JPEG_QUALITY', 95);
	define('BLEED', 3);
	
	define('LOGO_ZEILE_1', 'Ortsverband Rostock');
//	define('LOGO_ZEILE_2', 'Regionalbereich Schwerin');

	require('pdf.inc.php');
	
	if(isset($argv[2]) && intval($argv[2]) && intval($argv[2]) > 2000)
		define('STARTYEAR', intval($argv[2]));
	else
		define('STARTYEAR', 2016);
	
	$pdf = new PDF();
	$pdf->SetAuthor('Robert Wolke <r.wolke+agtpass@thw-rostock.de>', true);
	$pdf->SetTitle('AGT/CBRN-Pass Version 1.1', true);
	
	$teile = [
		['cover/cover.php'],
		['cover/coverinside.php'],
		['parts/title.php'],
		['parts/verantwPerson.php'],
		['parts/g263.php'],
		['parts/ausbildung.php'],
		['parts/quali.php'],
		['parts/activity.php', ['year' => STARTYEAR, 'num' => 8]],
		['parts/sample.php'],
		['parts/weitere.php', ['num' => 4]],
		['cover/backinside.php'],
		['cover/backside.php']
	];
	
	foreach($teile AS $t) {
		$cb = require($t[0]);
		$cb($pdf, isset($t[1]) ? $t[1] : []);
	}
	
	$pdf->Output('a5.pdf', 'F');

?>

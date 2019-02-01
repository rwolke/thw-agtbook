<?php
	
	define('DEBUG' ,false);
	define('JPEG_QUALITY', 95);
	define('BLEED', 3);
	
	// Erste Zeile unter THW-Logo
	define('LOGO_ZEILE_1', 'Ortsverband Musterstadt');
	// (optionale) zweite Zeile unter THW-Logo
	define('LOGO_ZEILE_2', 'Regionalbereich Musterregion');

	// define('COVER_IMAGE_CREDIT', 'THW Ortsverband Musterstadt');
	$titelbild = function($pdf) {
		/* 
			In diesem Beispiel wird von dem Bid IMG_2788 ein vertikaler Streifen
			von 24 % der Bildbreite ausgeschnitten. Dieser beginnt bei 41% der 
			Bildbreite. 
		*/
		$pdf->Img(
			// relativer Dateipfad
			'cover/IMG_2788.jpg', 
			// Positionierung auf Blatt (NICHT ÄNDERN!)
			0, 0, 
			// Größe Bild (NICHT ÄNDERN!)
			'H', PAGE_HEIGHT+2*BLEED,
			// Prozent der Bildgröße (Breite und Höhe) die gedruckt werden sollen.
			24, 100,
			// Offset (x, y) wo mit Druck begonnen werden soll in Prozent der Bildbreite
			41, 0
		);
	};
	
	require('pdf.inc.php');
	
	if(isset($argv[2]) && intval($argv[2]) && intval($argv[2]) > 2000)
		define('STARTYEAR', intval($argv[2]));
	else
		define('STARTYEAR', 2016);
	

	$pdf = new PDF();
	$pdf->SetAuthor('Robert Wolke <agtpass@thw-rostock.de>', true);
	$pdf->SetTitle('AGT/CBRN-Pass Version 1.1', true);
	
	$teile = [
		['cover/cover.php', ['coverimage' => $titelbild]],
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

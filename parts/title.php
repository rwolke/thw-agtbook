<?php
	return function($pdf) {
		global $YEARS;
		$pdf->CreatePage('P', true);
	
		$pdf->addFontStyle('title', 'sansB', 40);
		$pdf->useFont('title');
		$pdf->putText('center', 28, 'Atemschutznachweis');
	
		$pdf->addFontStyle('subHeader', 'sans', 12);
		$pdf->useFont('subHeader');
		$pdf->putText('center', 38, 'gemäß THW DV 7 und THW DV 500');
	
		$pdf->addFontStyle('text', 'sans', 10);
		$pdf->useFont('text');
	
		$pdf->putMultiText(
			0, 48, 
			'Dieser Pass dokumentiert alle Tätigkeiten des nachfolgend genannten Kameraden unter Atemschutz. '.
			'Er dokumentiert ebenso alle zu erbringenden Nachweise zum Erhalt der Einsatzbereitschaft als Atemschutzgeräteträger. '.
			'Fort- und Weiterbildungen im Bereich Atemschutz oder CBRN können ebenfalls erfasst werden.'
		);

		$pdf->addFontStyle('dots', 'sans', 4);
		$pdf->addFontStyle('label', 'sans', 12);
	
		$table = [
			'Nachname:', 'Vorname:',
			'Geburtsdatum:', 'Ortsverband:',
			'Stammnummer:', 'Ausgabedatum:',
		];
	
		$dy = 11;
		$xd = 2;
		$x0 = 50;
		$y0 = 80;
		$wd = 80;
		$doff = 2; 
		$dotNum = $wd / $pdf->getStrWidth('.', 'dots');
		foreach($table AS $i => $t) {
			$pdf->useFont('label');
			$pdf->putText(0, $y0 + $dy*$i, $t, 'right', $x0 - $xd);
		
			$pdf->useFont('dots');
			$pdf->putText($x0 + $xd, $doff + $y0 + $dy*$i, str_repeat('.', $dotNum));
		}
	
		$pdf->addFontStyle('under', 'sans', 6);
		$pdf->addFontStyle('underK', 'sansI', 6);
		$pdf->addFontStyle('head0', 'sansB', 16);
		$pdf->addFontStyle('head1', 'sansB', 13);
		$pdf->addFontStyle('head2', 'sansB', 11);
		$pdf->addFontStyle('head3', 'sansB', 10);
		$pdf->useFont('head2');
		$x0 = $pdf->getPos(X);
		$y0 = 153;
		$pdf->putText($x0, $y0, 'Inhalt');
		
		$content = [
			['Verantwortliche Person Atemschutz für diesen Ausweis', '2'],
			['Arbeitsmedizinische Vorsorgeuntersuchungen nach G 26.3', '3'],
			['Atemschutzausbildung', '6'],
			['Weiterqualifizierung (Fort- und Weiterbildungen)', '7'],
			['Qualifikationserhalt nach Jahren'],
			['      ab '.(STARTYEAR +  0), '8'],
			['      ab '.(STARTYEAR + 12), '12'],
			['      ab '.(STARTYEAR + 24), '16'],
			['      ab '.(STARTYEAR + 36), '20'],
			['      Ausfüllmuster', '24'],
			['Zusätzliche Vermerke zu Einsätzen und Übungen', '25']
		];
		
		$pdf->addFontStyle('table', 'sans', 8);
		$dy = 4.5;
		$w = $pdf->getPos(W) - 10;
		$y0 = 159;
		$x0 += 5;
		$s = .3;
		foreach($content AS $i => $t) {
			$t[2] = $pdf->getStrWidth($t[0], 'table');
		
			$pdf->useFont('table');
			$pdf->putText($x0, $y0 + $dy*$i, $t[0], 'left', $w);
			if(isset($t[1])) {
				$t[3] = $w - $t[2] - $pdf->getStrWidth($t[1], 'table');
				$pdf->putText($x0, $y0 + $dy*$i, $t[1], 'right', $w);
		
				$pdf->useFont('dots');
				$dotNum = ($t[3] - 2*$s) / $pdf->getStrWidth('.', 'dots');
				$pdf->putText($x0 + $t[2] + $s, $y0 + $dy*$i, str_repeat('.', $dotNum));
			}
		}
	}

?>
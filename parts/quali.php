<?php
	return function($pdf) {
		$pdf->CreatePage();
	
		$dotW = $pdf->getStrWidth('.', 'dots');
	
		$pdf->useFont('head0');
		$x0 = $pdf->getPos(X);
		$y0 = $pdf->getPos(Y) + 6;
		$w = $pdf->getPos(W);
		$pdf->putText($x0, $y0, 'Weiterqualifizierung', 'center', $w);
		
		$pdf->useFont('text');
		
		$text = 'Weiterbildungen und Fortbildungen des Atemschutzgeräteträgers für weitere Gerätearten und Schutzausstattungen, z. B. für Regenerationsgeräte, Körperschutz Form 3 oder Weiterqualifikation zum Atemschutzgerätewart bzw. Ausbilder.';
		$pdf->putMultiText(
			0, 21.5, $text
		);
		
		$w = $pdf->getPos(W);
		$x0 = $pdf->getPos(X);
		$y0 = $pdf->getPos(Y) + 30;
		$dy = 10;
		$dy2 = 4;
		$f = 1.4;
		$dx = [30, 35, 20, 0];
		$dx[3] = $w - array_sum($dx);
		
		$pdf->addFontStyle('thead', 'sansB', 10);
		$pdf->useFont('thead');
		$fontHp = $pdf->getFontIDHeight('', 'thead');
		$yadd = ($dy - 2*$dy2) / 2;
		$xp = 0;
		$pdf->putText($x0 + $xp, $y0 + $yadd + $pdf->calcMidText('', 'thead', $dy2), 'Qualifikation /', 'center', $dx[0]);
		$pdf->putText($x0 + $xp, $y0 + $yadd + $pdf->calcMidText('', 'thead', $dy2) + $dy2, 'Ausstattung', 'center', $dx[0]);
		$xp += $dx[0];
		$pdf->putText($x0 + $xp, $y0 + $yadd + $pdf->calcMidText('', 'thead', $dy2), 'Bildungseinrichtung,', 'center', $dx[1]);
		$pdf->putText($x0 + $xp, $y0 + $yadd + $pdf->calcMidText('', 'thead', $dy2) + $dy2, 'Lehrgang / Ausbilder', 'center', $dx[1]);
		$xp += $dx[1];
		$pdf->putText($x0 + $xp, $y0 + $pdf->calcMidText('', 'thead', $dy) , 'Datum', 'center', $dx[2]);
		$xp += $dx[2];
		$pdf->putText($x0 + $xp, $y0 + $yadd + $pdf->calcMidText('', 'thead', $dy2), 'Stempel /', 'center', $dx[3]);
		$pdf->putText($x0 + $xp, $y0 + $yadd + $pdf->calcMidText('', 'thead', $dy2) + $dy2, 'Unterschrift', 'center', $dx[3]);
		
		$n = 11;

		$y = $y0;
		$pdf->Line($x0, $y, $x0 + $w, $y);
		for($i = 0; $i <= $n; $i++) {
			$y += $dy * ($i ? $f : 1);
			$pdf->Line($x0, $y, $x0 + $w, $y);
		}
		
		$x = $x0;
		foreach($dx AS $xa) {
			if($x == $x0)
				$pdf->Line($x, $y0 + 0, $x, $y);
			$x += $xa;
			$pdf->Line($x, $y0 + 0, $x, $y);
		}
	};
?>
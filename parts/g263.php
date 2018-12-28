<?php
	function nachweisTabelle($pdf, $y0, $n) {
		$w = $pdf->getPos(W);
		$x0 = $pdf->getPos(X);
		$y0 = $pdf->getPos(Y) + $y0;
		$dy = 10.5;
		$dy2 = 4;
		$f = 1.4;
		$dx = [22, 22, 13, 40, 0];
		$dx[4] = $w - array_sum($dx);
		
		$pdf->useFont('thead');
		$fontHp = $pdf->getFontIDHeight('', 'thead');
		$yadd = ($dy - 2*$dy2) / 2;
		$xp = 0;
		$ix = 0;
		$pdf->putText($x0 + $xp, $y0 + $yadd + $pdf->calcMidText('', 'thead', $dy2), 'Datum der ', 'center', $dx[$ix]);
		$pdf->putText($x0 + $xp, $y0 + $yadd + $pdf->calcMidText('', 'thead', $dy2) + $dy2, 'Untersuchung', 'center', $dx[$ix]);
		$xp += $dx[$ix++];
		$pdf->putText($x0 + $xp, $y0 + $yadd + $pdf->calcMidText('', 'thead', $dy2), 'Ende der', 'center', $dx[$ix]);
		$pdf->putText($x0 + $xp, $y0 + $yadd + $pdf->calcMidText('', 'thead', $dy2) + $dy2, 'Gültigkeit', 'center', $dx[$ix]);
		$xp += $dx[$ix++];
		$pdf->putText($x0 + $xp, $y0 + $pdf->calcMidText('', 'thead', $dy) , 'Gruppe', 'center', $dx[$ix]);
		$xp += $dx[$ix++];
		$pdf->putText($x0 + $xp, $y0 + $yadd + $pdf->calcMidText('', 'thead', $dy2), 'Anmerkungen / ', 'center', $dx[$ix]);
		$pdf->putText($x0 + $xp, $y0 + $yadd + $pdf->calcMidText('', 'thead', $dy2) + $dy2, 'Einschränkungen', 'center', $dx[$ix]);
		$xp += $dx[$ix++];
		$pdf->putText($x0 + $xp, $y0 + $pdf->calcMidText('', 'thead', $dy) , 'Bestätigung', 'center', $dx[$ix]);
		
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
	}

	$f = function($pdf) {
		$pdf->CreatePage();
	
		$dotW = $pdf->getStrWidth('.', 'dots');

		$pdf->useFont('head0');
		$x0 = $pdf->getPos(X);
		$y0 = $pdf->getPos(Y) + 6;
		$w = $pdf->getPos(W);
		$pdf->putText($x0, $y0, 'Arbeitsmedizinische Untersuchungen nach G 26.3', 'center', $w);
		
		$pdf->useFont('text');
		
		$text = 'Diese Eintragung bestätigt, dass ein ärztlicher Befund mit der genannten Gültigkeit und Anmerkungen bei der Verantwortlichen Person Atemschutz vorliegt.';
		$pdf->putMultiText(
			0, 21.5, $text
		);

		///
		nachweisTabelle($pdf, 22, 11);
		
		// fortsetzung
		$pdf->CreatePage();
		$pdf->useFont('head1');
		$pdf->putText($pdf->getPos(X), $pdf->getPos(Y) + 5, 'Arbeitsmedizinische Untersuchungen nach G 26 - Fortführung', 'left');
		nachweisTabelle($pdf, 8, 12);
		
		// fortsetzung
		$pdf->CreatePage();
		$pdf->useFont('head1');
		$pdf->putText($pdf->getPos(X), $pdf->getPos(Y) + 5, 'Arbeitsmedizinische Untersuchungen nach G 26 - Fortführung', 'left');
		nachweisTabelle($pdf, 8, 12);
		
	};
	
	$f2 = function($pdf) {
		$x0 = $pdf->getPos(X);
		$y0 = $pdf->getPos(Y) + 7;
		$w = $pdf->getPos(W);
		
		// fortsetzung
		$pdf->CreatePage();
		$pdf->useFont('head0');
		$pdf->putText($x0, $y0, 'Arbeitsmedizinische Untersuchungen nach G 26.3', 'center', $w);
		nachweisTabelle($pdf,14, 12);
		
	};
	
	return $f;
?>
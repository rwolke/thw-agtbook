<?php

	function ausbildungPart($pdf, $yBase, $name, $desc, $inhalte) {
		$dotW = $pdf->getStrWidth('.', 'dots');
	
		$pdf->useFont('head1');
		$x0 = $pdf->getPos(X);
		$y0 = $yBase;
		$pdf->putText($x0, $y0, $name, 'center');
	
		$pdf->useFont('text');
		$y0 = $yBase + 8;
		$x = $x0 + 5;
		$pdf->putText($x0 + 9, $y0, $desc);
		$pdf->Rect($x0+4.5, $y0-2, 2, 2);
		$y0 += 8;
		$x += $pdf->putText($x+4, $y0, 'Ausbildung nach');
		$pdf->useFont('dots');
		$pdf->putText($x+5.5, $y0+.5, str_repeat('.', 93 / $dotW));
		$pdf->Rect($x0+4.5, $y0-2, 2, 2);
	
		$pdf->useFont('head3');
		$x0 = $pdf->getPos(X);
		$y0 = $yBase + 24;
		$pdf->putText($pdf->getPos(X), $y0, ' Inhalte', 'center', $pdf->getPos(W));
	
		$y0 = $yBase + 30;
		$dw = 4;
		$dy = 6.5;
		$cols = 2;
		$w0 = $pdf->getPos(W) - ($cols+1)*$dw - 4;
		$x0 += 2;
		$w = $w0 / $cols;
		foreach($inhalte AS $i => $in) {
			$x = $x0 + ($i % $cols) * ($w + $dw) + $dw;
			$y = $y0 + floor($i / $cols) * $dy;
		
			$pdf->Rect($x+1, $y-2, 2, 2);
			$pdf->useFont(isset($in) ? 'text' : 'dots');
			if($in)
				$pdf->putText($x+6, $y, $in);
			else
				$pdf->putText($x+6, $y+.5, str_repeat('.', ($w-6) / $dotW));
		}
		for($i = 1; $i < $cols; $i++)
			$pdf->Line(
				$x0 + $dw * ($i+.5) + $w * $i, $y0 - $dy * .5,
				$x0 + $dw * ($i+.5) + $w * $i, $y0 + ceil(count($inhalte) / $cols) * $dy - $dy * .8
			);
	
		$x0 = $pdf->getPos(X);
		$y0 = $yBase + 68;
		$x = $x0 + 4;
		$underd = 2.5;
		$pdf->useFont('text');
		$pdf->putText($x, $y0-4, 'Die Ausbildung wurde mit Erfolg abgeschlossen.');
	
		$w = $pdf->getPos(W);
		$y0 = $yBase + 75;
		$x += 5;
		$pdf->useFont('dots');
		$pdf->putText($x, $y0,  str_repeat('.', 30 / $dotW));
		$pdf->useFont('under');
		$pdf->putText($x, $y0 + $underd, 'Datum', 'center', 30);
		
		$wu = 75;
		$x += $w - 2*9 - 75;
		$pdf->useFont('dots');
		$pdf->putText($x, $y0,  str_repeat('.', 75 / $dotW)) ;
		$pdf->useFont('under');
		$pdf->putText($x, $y0 + $underd, 'Name / Stempel und Unterschrift', 'center', 75);
	}

	function ausbildung12($pdf) {
		$pdf->CreatePage();
	
		$dotW = $pdf->getStrWidth('.', 'dots');
	
		$pdf->useFont('head0');
		$x0 = $pdf->getPos(X);
		$y0 = $pdf->getPos(Y) + 6;
		$w = $pdf->getPos(W);
		$pdf->putText($x0, $y0, 'Atemschutzausbildung', 'center', $w);
	
		$pdf->useFont('text');
		$y0 = $pdf->getPos(Y) + 17;
		$x = $x0 + 10;
		$pdf->putText($x, $y0, 'von', 'left');
		$x+= $pdf->getStrWidth('von', 'text') + 2;
		$pdf->useFont('dots');
		$pdf->putText($x, $y0+.5, str_repeat('.', 64 / $dotW));
		$x += 65;
		$pdf->useFont('text');
		$pdf->putText($x, $y0, ', geboren am', 'left');
		$x+= $pdf->getStrWidth(', geboren am', 'text') + 2;
		$pdf->useFont('dots');
		$pdf->putText($x, $y0+.5, str_repeat('.', 25 / $dotW));
	
		ausbildungPart( 
			$pdf, $pdf->getPos(Y) + 28,
			'Teil 1 - Atemschutz',
			'Ausbildung nach THW DV 7 / FW DV 7 für Filtergeräte und Pressluftatmer (24 h)',
			[
				'Filtergerät: Gewöhnung',
				'Behältergerät: Gewöhnung',
				'Behältergerät: Orientierung',
				'Behältergerät: Körperliche Belastung',
				'Behältergerät: Psychische Belastung',
				'Behältergerät: Einsatznahe Tätigkeit',
				'Behältergerät: Notfalltraining',
				null,
				null,
				null
			]
		);
		ausbildungPart(
			$pdf, $pdf->getPos(Y) + 116,
			'Teil 2 - CBRN-Schutz',
			'Ausbildung nach THW DV 500 für CBRN mit Körperschutz Form 2 und Not-Dekon (24 h)',
			[
				'Theorie C/B/RN-Gefahrstoffe (7,5 h)',
				'Umgang mit C u. RN-Messtechnik',
				'Theorie CBRN-Einsatz (Organisation)',
				'Kennz. u. Erkennen v. Gefahrstoffen',
				'Anlegen von Körperschutz Stufe II ',
				'Einsatznahe Tätigkeit',
				'Not-Dekon', 
				'Sicheres Ablegen der Schutzbekleidung',
				null,
				null
			]
		);
	}
	return function($pdf) {
		ausbildung12($pdf);
	}
?>
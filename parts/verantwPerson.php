<?php
	return function($pdf) {
		$pdf->CreatePage();
		
		$x0 = $pdf->getPos(X);
		$y0 = $pdf->getPos(Y) + 6;
		$pdf->useFont('head0');
		$pdf->putText($x0, $y0, 'Verantwortliche Person Atemschutz', 'center');
		
		$pdf->useFont('text');
		
		$text = 'Gemäß THW DV 7 ist für jede Einsatzkraft unter Atemschutz ein Nachweis zu führen. Hierfür ist der Dienststellenleiter verantwortlich, der diese Funktion an eine Verantwortliche Person Atemschutz delegieren kann. ';
		$text.= 'In folgender Tabelle wird die Verantwortliche Person Atemschutz vermerkt, welche für die Pflege dieses Atemschutznachweises verantwortlich ist.';
		$pdf->putMultiText(
			0, $y0 + 7.5, $text
		);

		$w = $pdf->getPos(W);
		$x0 = $pdf->getPos(X);
		$y0 = $pdf->getPos(Y) + 33;
		$dy = 10.5;
		$dy2 = 5.25;
		$f = 1.3;
		$dx = [20, 25, 35, 0];
		$dx[3] = $w - array_sum($dx);
		
		$pdf->addFontStyle('thead', 'sansB', 10);
		$pdf->useFont('thead');
		$fontHp = $pdf->getFontIDHeight('', 'thead');
		$xp = 0;
		$pdf->putText($x0 + $xp, $y0 + $pdf->calcMidText('', 'thead', $dy2), 'von', 'center', $dx[0]);
		$pdf->putText($x0 + $xp, $y0 + $pdf->calcMidText('', 'thead', $dy2) + $dy2, 'bis', 'center', $dx[0]);
		$xp += $dx[0];
		$pdf->putText($x0 + $xp, $y0 + $pdf->calcMidText('', 'thead', $dy) , 'Dienststelle', 'center', $dx[1]);
		$xp += $dx[1];
		$pdf->putText($x0 + $xp, $y0 + $pdf->calcMidText('', 'thead', $dy) , 'Name', 'center', $dx[2]);
		$xp += $dx[2];
		$pdf->putText($x0 + $xp, $y0 + $pdf->calcMidText('', 'thead', $dy2), 'Bestätigung durch', 'center', $dx[3]);
		$pdf->putText($x0 + $xp, $y0 + $pdf->calcMidText('', 'thead', $dy2) + $dy2, 'Ortsbeauftragten', 'center', $dx[3]);
		
		$n = 11;

		$y = $y0;
		$pdf->SetLineWidth('thin');
		for($i = 0; $i <= $n; $i++) {
			$pdf->Line($x0, $y + $dy2 * ($i ? $f : 1), $x0 + $dx[0], $y + $dy2 * ($i ? $f : 1));
			$y += $dy * ($i ? $f : 1);
		}
		$pdf->SetLineWidth('normal');
		
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
?>
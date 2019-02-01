<?php
	function calendarTable($pdf, $y0, $years, $export = null) {
		$x0 = $pdf->getPos(X);
		$dy = 3.9;
		$dyh = 4;
		$y = $y0;
		
		$text = ['Jahr','Aktivität*', 'Jan','Feb','Mär','Apr','Mai','Jun','Jul','Aug','Sep','Okt','Nov','Dez'];
		$dx = [6, 18, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6];
		
		$y = $y0 + $dyh;
		$xt0 = $x0 + $dx[0];
		$w = array_sum($dx) + $x0 - $xt0;
		
		if(is_array($export)) {
			$list = [];
			foreach($export AS $k)
				$list[$k] = $$k;
			return $list;
		}
		elseif($export === null)
			foreach($years AS $year) {
				$pdf->SetFillColor(100 - 4*3);
				$pdf->Rect($xt0, $y + 0*$dy, $w, $dy, 'F');
				$pdf->SetFillColor(100 - 3*3);
				$pdf->Rect($xt0, $y + 1*$dy, $w, $dy, 'F');
				$pdf->SetFillColor(100 - 2*3);
				$pdf->Rect($xt0, $y + 2*$dy, $w, $dy, 'F');
				$pdf->SetFillColor(100 - 1*3);
				$pdf->Rect($xt0, $y + 3*$dy, $w, $dy, 'F');
				$y += 5*$dy;
			}
		
		$pdf->addFontStyle('sthead', 'sansB', 8);
		
		$pdf->useFont('sthead');
		$x0 = $pdf->getPos(X);
		$xp = 0;
		foreach($text AS $i => $t) {
			$pdf->putText($x0 + $xp, $y0 + $pdf->calcMidText('', 'sthead', $dyh), $t, 'center', $dx[$i]);
			$xp += $dx[$i];
		}
		
		$pdf->SetDrawColor(0);
		$pdf->SetLineWidth('normal');
		$y = $y0 + $dyh;
		$pdf->Line($x0, $y, $xt0 + $w, $y);
		$pdf->Line($x0, $y0, $xt0 + $w, $y0);
		foreach($years AS $year) {
			$pdf->SetDrawColor(50);
			$pdf->SetLineWidth('thin');
			$pdf->Line($xt0, $y + 1*$dy, $xt0 + $w, $y + 1*$dy);
			$pdf->Line($xt0, $y + 2*$dy, $xt0 + $w, $y + 2*$dy);
			$pdf->Line($xt0, $y + 3*$dy, $xt0 + $w, $y + 3*$dy);
			$pdf->Line($xt0, $y + 4*$dy, $xt0 + $w, $y + 4*$dy);
			$pdf->SetDrawColor(0);
			$pdf->SetLineWidth('normal');
			$pdf->Line($x0, $y + 5*$dy, $xt0 + $w, $y + 5*$dy);
			
			$pdf->useFont('thead');
			$pdf->verticalText($x0, $y + .8*$dy, $year, $dx[0], 3.6*$dy);
			
			$pdf->useFont('sthead');
			$pdf->putText($xt0, $y + 0*$dy + $pdf->calcMidText('', 'sthead', $dy), 'UW AGT', 'center', $dx[1]);
			$pdf->putText($xt0, $y + 1*$dy + $pdf->calcMidText('', 'sthead', $dy), 'UW CBRN', 'center', $dx[1]);
			$pdf->putText($xt0, $y + 2*$dy + $pdf->calcMidText('', 'sthead', $dy), 'Belastung', 'center', $dx[1]);
			$pdf->putText($xt0, $y + 3*$dy + $pdf->calcMidText('', 'sthead', $dy), 'E/Ü AGT', 'center', $dx[1]);
			$pdf->putText($xt0, $y + 4*$dy + $pdf->calcMidText('', 'sthead', $dy), 'E/Ü CBRN', 'center', $dx[1]);
			
			$y += 5* $dy;
		}
		
		$x = $x0;
		$pdf->SetLineWidth('normal');
		foreach($dx AS $n => $xa) {
			if($x == $x0)
				$pdf->Line($x, $y0 + 0, $x, $y);
			if($n+1 == count($dx) || $n < 2)
				$pdf->SetLineWidth('normal');
			elseif($xa == $dx[4])
				$pdf->SetLineWidth('thin');
			$x += $xa;
			$pdf->Line($x, $y0 + 0, $x, $y);
		}
		
		$pdf->useFont('under');
		$pdf->putText(
			$x0 + array_sum(array_slice($dx, 0, 0)),
			$y + 2.2,
			'*) UW: Unterweisung / E/Ü: Einsatz oder Einsatzübung'
		);
		$pdf->useFont('underK');
		$pdf->putText(
			$x0 + array_sum(array_slice($dx, 0, 2)),
			$y + 2.2,
			'Bitte mit Marker auch Gültigkeit der G26.3 eintragen!',
			'right', array_sum(array_slice($dx, 2))
		);
		
		return $w;
	}
	
	function uwTable($pdf, $x0, $y0, $n, $export = null) {
		$x0+= $pdf->getPos(X);
		$dyh1 = 5;
		$dyh2 = 4;
		$dyb = 7.1;
		$dyt = 15;
		$dx = [10, 20, 25];
		$w = array_sum($dx);
		$pdf->SetDrawColor(0);
		
		$pdf->addFontStyle('sthead', 'sansB', 8);
		$pdf->addFontStyle('stbody', 'sans', 8);
		
		$y = $y0;
		$xp = 0;
		$ix = 0;
		$pdf->useFont('thead');
		$pdf->putText($x0 + $xp, $y + $pdf->calcMidText('', 'sthead', $dyh1) , 'Unterweisung AGT/CBRN', 'center', $w);
		$pdf->useFont('sthead');
		$y+= $dyh1;
		$pdf->putText($x0 + $xp, $y + $pdf->calcMidText('', 'sthead', $dyh2) , 'DV', 'center', $dx[$ix]);
		$xp += $dx[$ix++];
		$pdf->putText($x0 + $xp, $y + $pdf->calcMidText('', 'sthead', $dyh2) , 'Datum', 'center', $dx[$ix]);
		$xp += $dx[$ix++];
		$pdf->putText($x0 + $xp, $y + $pdf->calcMidText('', 'sthead', $dyh2) , 'Unterschrift', 'center', $dx[$ix]);
		$xp += $dx[$ix++];
		$y+= $dyh2;
		
		$pdf->useFont('stbody');
		$pdf->SetLineWidth('normal');
		$pdf->Line($x0, $y0, $x0 + $w, $y0);
		for($i = 0; $i <= $n; $i++) {
			$y += $i ? $dyb : 0;
			$pdf->Line($x0, $y, $x0 + $w, $y);
			
			if($i) {
				$pdf->SetLineWidth('thin');
				$pdf->Line($x0, $y-($i?$dyb:$dyh)/2, $x0 + $dx[0], $y-($i?$dyb:$dyh)/2);
				$pdf->SetLineWidth('normal');
				$pdf->Rect($x0+1, $y - $dyb-2.1 + $pdf->calcMidText('', 'stbody', $dyb/2), 2, 2);
				$pdf->putText($x0+4.5 + $pdf->getStrWidth('5', 'stbody'), $y - $dyb + $pdf->calcMidText('', 'stbody', $dyb/2) , '7', 'left', $dx[0]);
				$pdf->Rect($x0+1, $y - $dyb/2-2.1 + $pdf->calcMidText('', 'stbody', $dyb/2), 2, 2);
				$pdf->putText($x0+4.5, $y - $dyb/2 + $pdf->calcMidText('', 'stbody', $dyb/2) , '500', 'left', $dx[0]);
			}
		}
		
		$pdf->Line($x0, $y0 + 0, $x0, $y);
		$pdf->Line($x0 + $dx[0], $y0 + 6, $x0 + $dx[0], $y);
		$pdf->Line($x0 + $dx[0] + $dx[1], $y0 + 6, $x0 + $dx[0] + $dx[1], $y);
		$pdf->Line($x0 + $w, $y0 + 0, $x0 + $w, $y);
		
		$pdf->useFont('under');
		$pdf->putText(
			$x0,
			$y + 2.2,
			'*) Die Unterweisung nach DV 500 beinhaltet Unterweisungen '
		);
		$pdf->putText(
			$x0,
			$y + 4.5,
			'nach § 38 StrlSchV, § 12 BioStoffV und § 14 GefStoffV'
		);
		
		if($export) {
			$list = [];
			foreach($export AS $k)
				$list[$k] = $$k;
			return $list;
		}
		else
			return $w;
	}
	
	function belTable($pdf, $x0, $y0, $n, $export = null) {
		$x0+= $pdf->getPos(X);
		$dyh1 = 5;
		$dyh2 = 4;
		$dyb = 7.1;
		$dyt = 15;
		$dx = [20, 25];
		$w = array_sum($dx);
		$pdf->SetDrawColor(0);
		
		$y = $y0;
		$xp = 0;
		$ix = 0;
		$pdf->useFont('thead');
		$pdf->putText($x0 + $xp, $y + $pdf->calcMidText('', 'sthead', $dyh1) , 'Belastungsübung AGT', 'center', $w);
		$pdf->useFont('sthead');
		$y+= $dyh1;
		$pdf->putText($x0 + $xp, $y + $pdf->calcMidText('', 'sthead', $dyh2) , 'Datum', 'center', $dx[$ix]);
		$xp += $dx[$ix++];
		$pdf->putText($x0 + $xp, $y + $pdf->calcMidText('', 'sthead', $dyh2) , 'Unterschrift', 'center', $dx[$ix]);
		$xp += $dx[$ix++];
		$y+= $dyh2;
		
		$pdf->SetLineWidth('normal');
		$pdf->Line($x0, $y0, $x0 + $w, $y0);
		for($i = 0; $i <= $n; $i++) {
			$y += $i ? $dyb : 0;
			$pdf->Line($x0, $y, $x0 + $w, $y);
		}
		
		$pdf->Line($x0, $y0 + 0, $x0, $y);
		$pdf->Line($x0 + $dx[0], $y0 + 6, $x0 + $dx[0], $y);
		$pdf->Line($x0 + $w, $y0 + 0, $x0 + $w, $y);
		
		if($export) {
			$list = [];
			foreach($export AS $k)
				$list[$k] = $$k;
			return $list;
		}
		else
			return $w;
	}
	
	function echtTabelle($pdf, $y0, $n, $export = null) {
		$w = $pdf->getPos(W);
		$x0 = $pdf->getPos(X);
		$y0 = $pdf->getPos(Y) + $y0;
		$dy = 9;
		$dy2 = 4;
		$f = .68;
		
		$dx = [9, 17, 10, 34, 34, 10, 10, 12, 13, 26, 0];
//		$dx = [10, 16, 10, 30, 30, 12, 12, 12, 8, 26, 0];
		$dx[10] = $w - array_sum($dx);
		$pdf->SetDrawColor(0);
		
		
		$pdf->useFont('sthead');
		$yadd = ($dy - 2*$dy2) / 2;
		$xp = 0;
		$ix = 0;
		$pdf->putText($x0 + $xp, $y0 + $yadd + $pdf->calcMidText('', 'sthead', $dy2), 'Nr.*', 'center', $dx[$ix]);
		$pdf->putText($x0 + $xp, $y0 + $yadd + $pdf->calcMidText('', 'sthead', $dy2) + $dy2, 'Anm.¹', 'center', $dx[$ix]);
		$xp += $dx[$ix++];
		$pdf->putText($x0 + $xp, $y0 + $pdf->calcMidText('', 'sthead', $dy) , 'Datum', 'center', $dx[$ix]);
		$xp += $dx[$ix++];
		$pdf->putText($x0 + $xp, $y0 + $yadd + $pdf->calcMidText('', 'sthead', $dy2), 'Art²', 'center', $dx[$ix]);
		$pdf->putText($x0 + $xp, $y0 + $yadd + $pdf->calcMidText('', 'sthead', $dy2) + $dy2, 'A/Ü/E', 'center', $dx[$ix]);
		$xp += $dx[$ix++];
		$pdf->putText($x0 + $xp, $y0 + $pdf->calcMidText('', 'sthead', $dy) , 'Einsatz / Einsatzort', 'center', $dx[$ix]);
		$xp += $dx[$ix++];
		
		$pdf->putText($x0 + $xp, $y0 + $pdf->calcMidText('', 'sthead', $dy) , 'Auftrag / Aktivität', 'center', $dx[$ix]);
		$xp += $dx[$ix++];
		$pdf->putText($x0 + $xp, $y0 + $yadd + $pdf->calcMidText('', 'sthead', $dy2), 'AG-', 'center', $dx[$ix]);
		$pdf->putText($x0 + $xp, $y0 + $yadd + $pdf->calcMidText('', 'sthead', $dy2) + $dy2, 'Gerät', 'center', $dx[$ix]);
		$xp += $dx[$ix++];

		$pdf->putText($x0 + $xp, $y0 + $yadd + $pdf->calcMidText('', 'sthead', $dy2), 'Dauer', 'center', $dx[$ix]);
		$pdf->putText($x0 + $xp, $y0 + $yadd + $pdf->calcMidText('', 'sthead', $dy2) + $dy2, '(min)', 'center', $dx[$ix]);
		$xp += $dx[$ix++];
		$pdf->putText($x0 + $xp, $y0 + $yadd + $pdf->calcMidText('', 'sthead', $dy2), 'CBRN³', 'center', $dx[$ix]);
		$pdf->putText($x0 + $xp, $y0 + $yadd + $pdf->calcMidText('', 'sthead', $dy2) + $dy2, 'Gef.grp.', 'center', $dx[$ix]);
		$xp += $dx[$ix++];
		
		$pdf->putText($x0 + $xp, $y0 + $pdf->calcMidText('', 'sthead', $dy), 'CSA Typ', 'center', $dx[$ix]);
		$xp += $dx[$ix++];
		
		$pdf->putText($x0 + $xp, $y0 + $pdf->calcMidText('', 'sthead', $dy) , 'Verantwortlicher', 'center', $dx[$ix]);
		$xp += $dx[$ix++];
		$pdf->putText($x0 + $xp, $y0 + $pdf->calcMidText('', 'sthead', $dy) , 'Unterschrift', 'center', $dx[$ix]);
		$xp += $dx[$ix++];
		
		$y = $y0;
		$pdf->Line($x0, $y, $x0 + $w, $y);
		for($i = 0; $i <= $n; $i++) {
			$y += $dy * ($i ? $f : 1);
			$pdf->Line($x0, $y, $x0 + $w, $y);
		}
		
		$x = $x0;
		foreach($dx AS $i => $xa) {
			if($x == $x0)
				$pdf->Line($x, $y0 + 0, $x, $y);
			$x += $xa;
			$pdf->Line($x, $y0 + 0, $x, $y);
		}
		
		$pdf->useFont('under');
		$pdf->putText(
			$x0 + 8,
			$y + 2.2,
			'¹) Bitte mit * kennzeichnen, wenn Anmerkungen eingetragen werden (ab Seite 25)'
		);
		$pdf->putText(
			$x0 + 82,
			$y + 2.2,
			'²) A - Ausbildung / Ü - Übung / E - Einsatz'
		);
		$pdf->putText(
			$x0 + 124,
			$y + 2.2,
			'³) welche Gefahrengruppe, z.B. IIB oder IC'
		);
		$pdf->useFont('underK');
		$pdf->putText(
			$x0 + 168,
			$y + 2.2,
			'Fragen? Ausfüllmuster ist auf Seite 24.'
		);

		if($export) {
			$list = [];
			foreach($export AS $k)
				$list[$k] = $$k;
			return $list;
		}
		else
			return $w;
	}
	
	function activity($pdf, $years) {
		$pdf->CreatePage('L');
		$dotW = $pdf->getStrWidth('.', 'dots');
		
		$pdf->useFont('head0');
		$x0 = $pdf->getPos(X);
		$y0 = $pdf->getPos(Y);
		$w = $pdf->getPos(W);
		$pdf->putText($x0 + 5, $y0 + 6, 'Aktivitäten '.min($years).' - '.max($years).'', 'center', 91);
		
		// w = 89
		$wc = calendarTable($pdf, $y0 + 11.5, $years);
		// 41
		$wb = belTable($pdf, $w-45, $y0+.5, 17);
		$wu = 55;
		// 51
		$xx = ($w - $wc - $wb - $wu) / 2;
		$wu = uwTable($pdf, $wc + 8, $y0+.5, 17);
		
		// fortsetzung
		$pdf->CreatePage('L');
		echtTabelle($pdf, 0, 20);
	};
	
	$c = function($pdf, $args = []) {
		if(!isset($args['year']))
			die("Anfangsjahr nicht definiert!");
		if(!isset($args['num']))
			die("Seitenanzahl für Aktivitäten nicht definiert!");

		for($i = 0; $i < $args['num']; $i++) {
			$y = $args['year'] + $i * 6;
			activity($pdf, range($y, $y+5));
		}
	};

	return $c;
?>
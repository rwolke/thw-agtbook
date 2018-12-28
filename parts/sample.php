<?php
	
	function calendarTableSample($pdf, $y0, $years) {
		foreach(calendarTable($pdf, $y0, $years, ['x0','dx','dy','dyh']) AS $k => $v) 
			$$k = $v;
		
		$y = $y0 + $dyh;
		$xt0 = $x0 + $dx[0];
		$mark = [
			[[0,2,0],[2,10,1]],
			[[0,2,1],[2,1,0],[3,9,1]],
			[[0,12,1]]
		];
		foreach($years AS $i => $year) {
			for($c = 0; $c < 5; $c++) {
				$pdf->SetFillColor(100 - (4-$c)*3);
				$pdf->Rect($x0+6, $y + $c*$dy, $x0+24, $dy, 'F');
			}
			foreach($mark[$i] AS $a) {
				for($c = 0; $c < 5; $c++) {
					if($a[2])
						$pdf->SetFillColor(255 - (4-$c)*9, 255 - (4-$c)*9, 192 - (4-$c)*9);
					else
						$pdf->SetFillColor(100 - (4-$c)*3);
					$pdf->Rect($x0 + 6*$a[0]+24, $y + $c*$dy, 6*$a[1], $dy, 'F');
				}
			}
			$y += 5*$dy;
		}
		
		$w = calendarTable($pdf, $y0, $years);
		
		function putX($pdf, $y0, $dyh, $dy, $f, $w,$r,$y,$m,$l=12) {
			$pdf->useFont($f);
			$pdf->putText(
				25 + $m * 6, 
				$y0 + $dyh + ($y*5 + $r) * $dy, 
				$w
			);
			$t = $m + $l - 1;
			if($t >= 12) {
				$pdf->Line(
					28 + $m * 6, 
					$y0 + $dyh + ($y*5 + $r) * $dy - 1.5, 
					28.8 + 12 * 6, 
					$y0 + $dyh + ($y*5 + $r) * $dy - 1.5
				);
				$m = 0.2;
				$y++;
				$t-= 12;
			}
			if($t)
				$pdf->Line(
					28 + $m * 6, 
					$y0 + $dyh + ($y*5 + $r) * $dy - 1.5, 
					28.8 + $t * 6, 
					$y0 + $dyh + ($y*5 + $r) * $dy - 1.5
				);
		}

		$pdf->SetLineWidth('thick');
		$y0 += 3.5;
		// UW
		$pdf->SetDrawColor(0,0,200);
		$pdf->SetTextColor(0,0,200);
		putX($pdf, $y0, $dyh, $dy, 'h1x', 'X', 0, 0, 6);
		putX($pdf, $y0, $dyh, $dy, 'h1x', 'X', 1, 1, 4);
		putX($pdf, $y0, $dyh, $dy, 'h1x', 'X', 0, 1, 7);
		$pdf->SetDrawColor(0,50,220);
		$pdf->SetTextColor(0,50,220);
		putX($pdf, $y0, $dyh, $dy, 'h2x', 'X', 0, 2, 3,10);
		putX($pdf, $y0, $dyh, $dy, 'h2x', 'X', 1, 2, 3,10);
		// Belastung
		$pdf->SetDrawColor(0,0,200);
		$pdf->SetTextColor(0,0,200);
		putX($pdf, $y0, $dyh, $dy, 'h1x', 'X', 2, 0, 6);
		putX($pdf, $y0, $dyh, $dy, 'h1x', 'X', 2, 1, 2);
		$pdf->SetDrawColor(0,50,220);
		$pdf->SetTextColor(0,50,220);
		putX($pdf, $y0, $dyh, $dy, 'h2x', 'X', 2, 2, 4, 9);
		// E/Ü
		$pdf->SetDrawColor(0,0,200);
		$pdf->SetTextColor(0,0,200);
		putX($pdf, $y0, $dyh, $dy, 'h1x', '2', 3, 0, 6);
		putX($pdf, $y0, $dyh, $dy, 'h1x', '4', 3, 1, 4);
		putX($pdf, $y0, $dyh, $dy, 'h1x', '4', 4, 1, 4);
		$pdf->SetDrawColor(0,50,220);
		$pdf->SetTextColor(0,50,220);
		putX($pdf, $y0, $dyh, $dy, 'h2x', '6', 3, 2, 2, 11);
		putX($pdf, $y0, $dyh, $dy, 'h2x', '6', 4, 2, 2, 11);
		
		$pdf->SetDrawColor(0,0,0);
		$pdf->SetTextColor(0,0,0);

		return $w;
	}

	function uwTableSample($pdf, $x0, $y0, $n) {
		foreach(uwTable($pdf, $x0, $y0, $n, ['x0','dx','w']) AS $k => $v) 
			$$k = $v;
		
		$dx = [10, 20, 25];
		
		$pdf->SetTextColor(0,0,200);
		$pdf->useFont('h1');
		$pdf->putText($x0 +  1, $y0 + 11.8, 'x');
		$pdf->putText($x0 +  1, $y0 + 22.2, 'x');
		$pdf->putText($x0 +  1, $y0 + 25.8, 'x');
		
		$pdf->putText($x0 + 10, $y0 + 14, '7.6.2003', 'center', $dx[1]);
		$pdf->putText($x0 + 10, $y0 + 21, '19.4.2004', 'center', $dx[1]);
		$pdf->putText($x0 + 10, $y0 + 28, '19.7.2004', 'center', $dx[1]);
		
		$pdf->putText($x0 + 28, $y0 + 14, 'Müller', 'center', $dx[2]);
		$pdf->putText($x0 + 28.5, $y0 + 21, 'Müller', 'center', $dx[2]);
		$pdf->putText($x0 + 28.5, $y0 + 28, 'Müller', 'center', $dx[2]);
		
		$pdf->SetTextColor(0,50,220);
		$pdf->useFont('h2');
		$pdf->putText($x0 +  1, $y0 + 33.4, 'x');
		$pdf->putText($x0 +  1, $y0 + 36.8, 'x');
		$pdf->putText($x0 + 10, $y0 + 35, '24.3.2005', 'center', $dx[1]);
		$pdf->putText($x0 + 28.5, $y0 + 35, 'Maier', 'center', $dx[2]);
		
		$pdf->SetTextColor(0,0,0);
		
		return $w;
	}

	function belTableSample($pdf, $x0, $y0, $n) {
		foreach(belTable($pdf, $x0, $y0, $n, ['x0','dx','w']) AS $k => $v) 
			$$k = $v;
		
		$dx = [20, 25];
		
		$pdf->useFont('h1');
		$pdf->SetTextColor(0,0,200);
		$pdf->putText($x0, $y0 + 14, '14.6.2003', 'center', $dx[0]);
		$pdf->putText($x0, $y0 + 21, '19.2.2004', 'center', $dx[0]);
		
		$pdf->putText($x0 + $dx[0], $y0 + 14, 'Müller', 'center', $dx[1]);
		$pdf->putText($x0 + $dx[0]+.5, $y0 + 21, 'Müller', 'center', $dx[1]);
		
		$pdf->useFont('h2');
		$pdf->SetTextColor(0,50,220);
		$pdf->putText($x0, $y0 + 28, '24.4.2005', 'center', $dx[0]);
		$pdf->putText($x0 + $dx[0]-.5, $y0 + 28, 'Maier', 'center', $dx[1]);
		$pdf->SetTextColor(0,0,0);

		return $w;
	}

	function echtTabelleSample($pdf, $y0, $n) {
		foreach(echtTabelle($pdf, $y0, $n, ['x0','y0','dx','dy','dy2','f','w']) AS $k => $v) 
			$$k = $v;
		
		$pdf->SetTextColor(0,0,200);
		$pdf->useFont('h1');
		array_unshift($dx, 5);
		$ds = array_map(function($x){static $y=0;$y+=$x;return $y;}, $dx);
		array_shift($dx);
		$l = $y0 + $dy + $pdf->calcMidText('', 'h1', $dy2);
		$lines = [
			['1', '13.6.2003', 'A', 'OV Stadt', 'Gewöhnung', 'PA', '15', '-', '-', 'Müller', 'Müller'],
			['2', '14.6.2003', 'Ü', 'Bunker Dorf', 'Personenrettung', 'PA', '20', '-', '-', 'Müller', 'Müller'],
			['3', '20.4.2004', 'A', 'OV Stadt', 'Dekon', 'Filter', '20', 'IIB', '3', 'Müller', 'Müller'],
			['4', '21.4.2004', 'Ü', 'Goethestr. 4', 'Personenrettung', 'PA', '20', 'IIC', '3', 'Müller', 'Müller'],
		];
		foreach($lines AS $n => $l)
			foreach($l AS $i => $c)
				$pdf->putText($ds[$i], $y0 + $dy*$f * $n + 14, $c, 'center', $dx[$i]);

		$pdf->SetTextColor(0,50,220);
		$pdf->useFont('h2');
		$lines = [ 4=>
			['5', '4.10.2004', 'A', 'FFW Stadt', 'Ausbildung CSA', 'PA', '15', 'IIA/C', '1a-ET', 'Peters, FFW', 'Maier'],
			['6', '28.2.2005', 'E', 'LKW Unfall, Dorf', 'Aufräumarbeiten', 'P3', '15', 'IIA', '3', 'Peters, FFW', 'Maier']
		];
		foreach($lines AS $n => $l)
			foreach($l AS $i => $c)
				$pdf->putText($ds[$i], $y0 + $dy*$f * $n + 14, $c, 'center', $dx[$i]);
		
		$pdf->SetTextColor(0);
	}
	
	$f = function($pdf) {
		$pdf->CreatePage('L');
		$dotW = $pdf->getStrWidth('.', 'dots');
		
		$pdf->addFontStyle('h1', 'hand1', 10);
		$pdf->addFontStyle('h1x', 'hand1', 13);
		$pdf->addFontStyle('h2', 'hand2', 9.5);
		$pdf->addFontStyle('h2x', 'hand2', 13);
		
		$years = ['2003', '2004', '2005'];
		
		$pdf->useFont('head0');
		$x0 = $pdf->getPos(X);
		$y0 = $pdf->getPos(Y);
		$w = $pdf->getPos(W);
		$pdf->putText($x0 + 5, $y0 + 6, 'Aktivitäten - Muster 2003 - 2005', 'center', 91);
		
		// w = 89
		$wc = calendarTableSample($pdf, $y0 + 10, $years);
		// 41
		$wb = belTableSample($pdf, $w-45, $y0+.5, 9);
		$wu = 55;
		// 51
		$xx = ($w - $wc - $wb - $wu) / 2;
		$wu = uwTableSample($pdf, $wc + 8, $y0+.5, 9);
		
		echtTabelleSample($pdf, 80, 8);
		
		$pdf->addFontStyle('muster', 'sans', 160);
		$pdf->Rotate(63,200,140);
		$pdf->useFont('muster');
		$pdf->SetTextColor(0);
		$pdf->SetAlpha(.25);
		$pdf->putText(70, 35, 'MUSTER');
		};
	
	return $f;
?>
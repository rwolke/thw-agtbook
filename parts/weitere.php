<?php
	function weitere($pdf) {
		// fortsetzung
		$pdf->CreatePage();
		$x0 = $pdf->getPos(X);
		$y0 = $pdf->getPos(Y) + 3;
		
		$pdf->SetTextColor(0);
		$pdf->useFont('head0');
		$x0 = $pdf->getPos(X);
		$y0 = $pdf->getPos(Y) + 6;
		$w = $pdf->getPos(W);
		$pdf->putText($x0, $y0, 'Anmerkungen zu Atemschutzeinsätzen / -übungen', 'center');
		$h = 185;
		$y0+= 4;
		$x0 += ($pdf->getPos(W) - $w) / 2; 
//		$pdf->SetLineWidth('thin');
		$pdf->SetDrawColor(75);
		
		for($x = 0; $x <= $w; $x+=5)
			$pdf->Line($x0 + $x, $y0, $x0 + $x, $y0+$h);
		for($y = 0; $y <= $h; $y+=5)
			$pdf->Line($x0, $y0 + $y, $x0+$w, $y0 + $y);
	};
	return function ($pdf, $args = []) {
		if(!isset($args['num']))
			die("Seitenanzahl für Anmerkungen nicht definiert!");
		while($args['num']--)
			weitere($pdf);
	}
?>
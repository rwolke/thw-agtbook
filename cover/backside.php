<?php
	return function($pdf) {
		$pdf->SetTextColor(0);
		$pdf->CreatePage('P', true, true);
		$pdf->SetFillColor(100, 80, 0, 0);
		$pdf->Rect(
			0, 
			0, 
			PAGE_WIDTH + 2*BLEED, 
			PAGE_HEIGHT + 2*BLEED,
			'F'
		);
	};
?>
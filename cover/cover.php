<?php
	return function($pdf) {

		$pdf->CreatePage('P', true, true);
		$xh = PAGE_WIDTH/2 + BLEED;
		$y0 = $pdf->getPos(Y);
		
		$pdf->Img(
			'cover/IMG_2788.jpg', 
			0, 0, 
			'H', PAGE_HEIGHT+2*BLEED,
			24, 100,
			41, 0
		);

		$pdf->SetAlpha(.7);
		$pdf->SetFillColor(100);
		$pdf->Rect(0, 0, BLEED+PAGE_WIDTH/2, BLEED + MARGIN_OUTER + 13, 'F');
		$pdf->SetAlpha(1);
		
		$pdf->SetFillColor(100, 80, 0, 0);
		
		$pdf->Rect(
			$xh, 0, 
			$xh, PAGE_HEIGHT+2*BLEED, 
			'F'
		);

		{
			// logo
			$pdf->setSourceFile('cover/logo/logo_thw_weiss.pdf');
			$tpl = $pdf->importPage(1, '/MediaBox');
			$pdf->useTemplate($tpl, 
				$xh + 0.05*PAGE_WIDTH , BLEED + MARGIN_OUTER + 5,
				0.8 * PAGE_WIDTH/2
			);
			if(defined('LOGO_ZEILE_1')) {
				// line
				$pdf->SetDrawColor(100);
				$pdf->SetDash();
				$pdf->SetLineWidth('logo');
				$pdf->Line(
					$xh + 0.05*PAGE_WIDTH, BLEED + MARGIN_OUTER + 5 + 13.7, 
					$xh + 0.45*PAGE_WIDTH, BLEED + MARGIN_OUTER + 5 + 13.7
				);
				
				// ov
				$pdf->addFontStyle('ovtext', 'lubaM', 9);
				$pdf->useFont('ovtext');
				$pdf->SetTextColor(100);
				$pdf->putText(
					$xh + 0.05*PAGE_WIDTH, 
					BLEED + MARGIN_OUTER + 5 + 17.4, 
					LOGO_ZEILE_1
				);
				if(defined('LOGO_ZEILE_2'))
					$pdf->putText(
						$xh + 0.05*PAGE_WIDTH, 
						BLEED + MARGIN_OUTER + 5 + 21.4, 
						LOGO_ZEILE_2
					);
				if(defined('LOGO_ZEILE_3'))
					$pdf->putText(
						$xh + 0.05*PAGE_WIDTH, 
						BLEED + MARGIN_OUTER + 5 + 25.4, 
						LOGO_ZEILE_3
					);
			}
		}
		
		$pdf->SetDrawColor(0);
		$pdf->SetTextColor(0);

		$pdf->SetLineWidth('normal');
		$pdf->SetDash(.1,1);
		$pdf->Line(
			BLEED + 0.05*PAGE_WIDTH, BLEED + MARGIN_OUTER + 10,
			BLEED + 0.45*PAGE_WIDTH, BLEED + MARGIN_OUTER + 10
		);
		$pdf->addFontStyle('name', 'sans', 7);
		$pdf->useFont('name');
		$pdf->putText(
			BLEED + 0.05*PAGE_WIDTH, 
			BLEED + MARGIN_OUTER + 12.5, 
			'Vor- und Nachname des Inhabers', 
			'center', 
			0.4*PAGE_WIDTH
		);

		$pdf->SetFillColor(0, 0, 100, 0);
		$pdf->Rect($xh, BLEED+.75*PAGE_HEIGHT, 5, 9, 'F');
		
		$pdf->SetLineWidth(.2);
		$pdf->SetDash(.3,.5);
		$pdf->SetDrawColor(100);
		$pdf->Line($xh+5, BLEED+.75*PAGE_HEIGHT+.2, $xh+5, BLEED+PAGE_HEIGHT+BLEED);

		$pdf->SetTextColor(100);
		$pdf->addFontStyle('cover', 'sansB', 22);
		$pdf->useFont('cover');
		$pdf->Text($xh+8, BLEED+.75*PAGE_HEIGHT+7, 'Atemschutzpass');

		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(0);
	};
?>
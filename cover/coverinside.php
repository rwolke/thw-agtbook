<?php
	return function($pdf) {
		$pdf->CreatePage('P', true, true);

		$w = $pdf->getPos(W);
		$h = $pdf->getPos(H);
		$x0 = $pdf->getPos(X);
		$y0 = $pdf->getPos(Y);

		$pdf->addFontStyle('subtext', 'sans', 8);
		$pdf->useFont('subtext');

		$text = [
			[
				'Version 1.1 - 2019',
				'2. Auflage',
				'50 Exemplare'
			],
			[
				'Entwicklung und Design:',
				'THW Ortsverband Rostock'
			],
			[
				'Bildnachweis (Cover):',
				(defined('COVER_IMAGE_CREDIT') ? COVER_IMAGE_CREDIT : 'THW Ortsverband Rostock')
			]
		];
		$dyl = 4;
		$dya = 9;
		$y = 0;
		foreach($text AS $absatz) {
			foreach($absatz AS $t)
				$y += $dyl;
			$y += $dya - $dyl;
		}
		$y = $y0 + ($h - $y - 3*$dyl);
		foreach($text AS $absatz) {
			foreach($absatz AS $t) {
				$pdf->putText($x0, $y, $t);
				$y += $dyl;
			}
			$y += $dya - $dyl;
		}
		
		$pdf->putMultiText(
			0, $y0 + $h - 3*$dyl, 
			'Dies ist die zweite Version eines Atemschutznachweises, der auf die THW-DVs zugeschnitten ist und '.
			'auch die Erfassung von Tätigkeiten unter CBRN ermöglicht. Fragen, Anmerkungen und Verbesserungsvorschläge '.
			'können gerne an agtpass@thw-rostock.de gerichtet werden. ',
			null, 4
		);
	};
?>
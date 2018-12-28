<?php
	define('INCH', 2.54);
	define('IMAGERESOLUTION', 300);

	function in2mm($in) { return $in * 25.4; }
	function mm2in($mm) { return $mm / 25.4; }
	
	//	5.25 x 8 inches
	// 13.335 x 20.32 centimeters
	define('PAGE_HEIGHT', 210);
	define('PAGE_WIDTH', 148);
	// 24 to 150 pages	.375"	at least .25"
	define('MARGIN_INNER', 8);
	define('MARGIN_OUTER', 5);
	
//	define('BLEED', in2mm(.125));
	define('X', 0);
	define('Y', 1);
	define('W', 2);
	define('H', 3);
	
	define('FPDF_FONTPATH', './pdf/font');
	
	require_once("pdf/fpdf.php");
	require_once("pdf/fpdi.php");
	
	if(!defined('pt2mm'))
		define('pt2mm', 25.4/72);
	
	if(!defined('FPDF_FONTPATH'))
		define('FPDF_FONTPATH', dirname(__FILE__).'/font/');
	
	class PDF extends FPDI
	{
		protected $_fontDefinition = [];
		// array([type = family.$style], [size], [color])
		protected $_curFont = ['',0,null];
		// fontIDs: [id] => [fontID, size]
		protected $_font = [];
		
		protected $_pos = [];
		protected $_page = [];
		protected $_flags = [];
		protected $_pageNumOffset = 0;
		
		protected $_imgDPI =IMAGERESOLUTION;

		public function SetLineWidth($name) {
			if($name == 'thick')
				parent::SetLineWidth(.5);
			if($name == 'normal')
				parent::SetLineWidth(.25);
			if($name == 'thin')
				parent::SetLineWidth(.09);
			if($name == 'logo')
				parent::SetLineWidth(.4);
			
		}
		
		public function CreatePage($orientation = '', $skipPageNo = false, $skipPageEnum = false) {
			parent::AddPage();
			
			if($skipPageEnum)
				$this->_pageNumOffset--;
			
			$this->SetLineWidth('normal');
			
			$this->SetBox('MediaBox', PAGE_WIDTH+2*BLEED, PAGE_HEIGHT+2*BLEED);
			$this->SetBox('BleedBox', PAGE_WIDTH+2*BLEED, PAGE_HEIGHT+2*BLEED);
			$this->SetBox('TrimBox', PAGE_WIDTH, PAGE_HEIGHT, BLEED, BLEED);
			
			if($orientation == 'L') {
				$this->_pos[W] = $this->_page[H] - 2*MARGIN_OUTER;
				$this->_pos[H] = $this->_page[W] - MARGIN_OUTER - MARGIN_INNER;
				$this->_pos[Y] = $this->page % 2 ? MARGIN_INNER + BLEED : MARGIN_OUTER + BLEED;
				$this->_pos[X] = MARGIN_OUTER;
			}
			else {
				$this->_pos[H] = $this->_page[H] - 2*MARGIN_OUTER;
				$this->_pos[W] = $this->_page[W] - MARGIN_OUTER - MARGIN_INNER;
				$this->_pos[Y] =  MARGIN_OUTER + BLEED;
				$this->_pos[X] = $this->page % 2 ? MARGIN_INNER + BLEED : MARGIN_OUTER + BLEED;
			}
			
			if(DEBUG) {
				
				$this->SetFillColor(255,128,128);
				$this->Rect(0, 0, PAGE_WIDTH + BLEED + BLEED, PAGE_HEIGHT + 2*BLEED, 'F');
				$this->SetFillColor(255,255,255);
				if($this->page % 2)
					$this->Rect(BLEED, BLEED, PAGE_WIDTH, PAGE_HEIGHT, 'F');
				else
					$this->Rect(BLEED, BLEED, PAGE_WIDTH, PAGE_HEIGHT, 'F');
				
				$this->SetFillColor(255,255, 192);
				if($this->page % 2)
					$this->Rect(BLEED, BLEED, PAGE_WIDTH, PAGE_HEIGHT, 'F');
				else
					$this->Rect(BLEED, BLEED, PAGE_WIDTH, PAGE_HEIGHT, 'F');
				
				$this->SetFillColor(255,255,255);
				$this->Rect(
					$this->page % 2 ? MARGIN_INNER + BLEED : MARGIN_OUTER + BLEED, 
					MARGIN_OUTER + BLEED, 
					$this->_page[W] - MARGIN_INNER - MARGIN_OUTER, 
					$this->_page[H] - MARGIN_OUTER - MARGIN_OUTER, 
					'F'
				);
			}
			else
				$this->SetBox('CropBox', PAGE_WIDTH, PAGE_HEIGHT, BLEED, BLEED);

			if(!$skipPageNo)
				$this->printPageNo();
			
			if($orientation == 'L')
				$this->Rotate(90,(PAGE_HEIGHT+BLEED)/2,(PAGE_HEIGHT+BLEED)/2);
		}
		
		public function printPageNo() {
			$this->useFont('pageno');
			$this->putText(
				$this->page % 2 ? MARGIN_INNER + BLEED : MARGIN_OUTER + BLEED, 
				BLEED + $this->_page[H] - MARGIN_OUTER - .1,
				$this->page + $this->_pageNumOffset,
				$this->page % 2 ? 'right' : 'left',
				$this->_page[W] - MARGIN_OUTER - MARGIN_INNER
			);
		}
		
		/**
		 *  create an instance of MyPDF
		 */
		public function __construct($flags = []) {
			$this->_flags = $flags;
			
			parent::__construct(
				(PAGE_WIDTH > PAGE_HEIGHT ? 'L' : 'P'), 
				'mm', 
				[PAGE_WIDTH + 2*BLEED, PAGE_HEIGHT + 2*BLEED]
			);
			
			$this->cMargin = 0;
			$this->SetMargins(0,0);
			
			$this->_page = [
				W => PAGE_WIDTH,
				H => PAGE_HEIGHT
			];
			$this->_pos = [
				X => BLEED,
				Y => BLEED,
			];
			
			$fonts = [
				'sans'  => ['BundesSans', '','BundesSansOffice-Regular.php'],
				'sansB' => ['BundesSans','B','BundesSansOffice-Bold.php'],
				'sansI' => ['BundesSans','I','BundesSansOffice-Italic.php'],
				'lubaM' => ['LubalinGB', '', 'Lubalin Graph Bold.php'],
				'hand1' => ['ArchitectsDaughter', '', 'ArchitectsDaughter.php'],
				'hand2' => ['GloriaHallelujah', '', 'GloriaHallelujah.php']
			];
			foreach($fonts AS $k => $v) {
				$this->AddFont($v[0], $v[1], $v[2]);
				$id = strtolower($v[0]).strtoupper($v[1]);
				$this->_fontDefinition[$k] = [
					'name' => $v,
					'key' => $id,
					'width' => $this->fonts[$id]['cw'],
					'height' => [
						'x' => isset($this->fonts[$id]['desc']['xHeight']) 
							? $this->fonts[$id]['desc']['xHeight'] 
							: $this->fonts[$id]['desc']['Ascent'] + $this->fonts[$id]['desc']['Descent'],
						'A' => $this->fonts[$id]['desc']['Ascent'],
						'p' => $this->fonts[$id]['desc']['Descent']
					]
				];
			}
			
			$this->addFontStyle('pageno', 'sans', 10);
			
			// k.A.
			$this->_importedFile = false;
		}
		
		public function getPos($key) {
			assert(isset($this->_pos[$key]));
			return $this->_pos[$key];
		}
		
		public function addFontStyle($fontID, $font, $size) {
			assert($this->_fontDefinition[$font]);
			if(!isset($this->_fontDefinition[$font]))
				die('Schriftart '.$font.' nicht gefunden!'."\n");
			$this->_font[$fontID] = [$font, $size];
		}
		
		public function useFont($fontID) {
			assert($this->_font[$fontID]);
			if(!isset($this->_font[$fontID]))
				die('Schriftart '.$fontID.' nicht gefunden!'."\n");
			$this->_curFontID = $fontID;
			$f = $this->_font[$fontID];
			
			if($this->_curFont[0] != $f[0])
				parent::SetFont(
					$this->_fontDefinition[$f[0]]['name'][0], 
					$this->_fontDefinition[$f[0]]['name'][1],
					$f[1]
				);
			
			elseif($this->_curFont[1] != $f[1])
				parent::SetFontSize($f[1]);
			
			$this->_curFont = $f;
			$this->_curFont[2] = $this->_fontDefinition[$f[0]];
		}
		
		public function getFontIDHeight($text, $fontID = false) {
			if($fontID === false)
				list($fontName, $fontSize) = $this->_curFont;
			else {
				assert($this->_font[$fontID]);
				list($fontName, $fontSize) = $this->_font[$fontID];
			}
			
			if(!isset($this->_fontDefinition[$fontName]))
				die('PDF::getFontIDHeight() font "'.$fontName.'" not found!'."\n");
			
			$h = $this->_fontDefinition[$fontName]['height'];
			if(strpbrk($text,'gjpqyQ') !== false) // if small letters
				return ($h['A'] - $h['p']) * pt2mm * $fontSize / 1000;
			return $h['A'] * pt2mm * $fontSize / 1000;
		}
		
		public function calcMidText($text, $fontID, $height) {
			$h = $this->getFontIDHeight($text, $fontID);
			return $h + ($height - $h) / 2;
		}
		
		public function getStrWidth($text, $fontID) {
			if(!$fontID) 
				list($fontName, $fontSize) = $this->_font[$this->_curFontID];
			elseif(!is_array($fontID)) {
				assert($this->_font[$fontID]);
				list($fontName, $fontSize) = $this->_font[$fontID];
			}
			else
				list($fontName, $fontSize) = $fontID;
			
			if(!isset($this->_fontDefinition[$fontName])) {
				debug_print_backtrace();
				die('PDF::getStrWidth() font "'.$fontName.'" not found!'."\n");
			}
			
			// other font, than current requested
			$text = mb_convert_encoding($text, 'ISO-8859-1', 'UTF8');
			$w = 0;
			$l = strlen($text);
			$cw =& $this->_fontDefinition[$fontName]['width'];
			for($i = 0; $i < $l; $i++)
				$w += $cw[$text[$i]];
			
			return $w * pt2mm * $fontSize / 1000;
		}

		/**
		 *  fit in a text into a given width starting from given font
		 *
		 *  decrements font size by 0.5 pt until text fits in.
		 *  leaves fitting font size set.
		 *
		 *  @return width of text when it fits
		 */
		public function fitInText($text, $fontID, $max_width) {
			$width = $this->getStrWidth($text, $fontID);
			
			if($width <= $max_width)
				return $width;
			
			$fontSize = $max_width/$width * $this->_font[$fontID][1];
			// round on 0.5 downwards
			$fontSize = floor($fontSize / 0.5) * 0.5;
			
			return $this->getStrWidth($text, [$this->_font[$fontID][0], $fontSize]);
		}
		
		
		/**
		 *  cut in a text into a given width by 
		 *
		 *  @return width of text when it fits
		 */
		public function cutIn(&$full, &$cut, $fontID, $max_width) {
			$max_width /= $this->_font[$fontID][1] * pt2mm / 1000;
			$cw =& $this->_fontDefinition[$this->_font[$fontID][0]]['width'];
			$cw["\0"] = 0;
			$trenner = '-/&';
			for($i=0; $i<strlen($full) && $max_width > 0; $i++){
				if($full[$i] == ' ')
					$breaks[$i] = [$i, $i+1, '', ''];
				elseif($full[$i] == "\0" && $max_width > $cw['-'])
					$breaks[$i] = [$i, $i+1, '-', ''];
				elseif($full[$i] == "\n") {
					$breaks[PHP_INT_MAX] = [$i, $i+1, '', ''];
					break;
				}
				else {
					$p = strpos($trenner, $full[$i]);
					if($p !== false && $max_width > $cw[$trenner[$p]])
						$breaks[$i] = [$i+1, $i+1, '', ''];
				}
				$max_width -= $cw[$full[$i]];
			}
			if($max_width > 0 && !isset($breaks[PHP_INT_MAX]))
				$breaks[PHP_INT_MAX] = [strlen($full),strlen($full),'',''];
				
			
			$i = max(array_keys($breaks));
			$cut = strtr(substr($full, 0, $breaks[$i][0]), ["\0" => '']).$breaks[$i][2];
			$full = $breaks[$i][3].substr($full, $breaks[$i][1]);
			return $i != PHP_INT_MAX;
		}
		
		/**
		 *  put given Text on pdf at given position
		 *
		 *  @param $x numeric position on x axis in mm
		 *  @param $y numeric position on y axis in mm
		 *  @param $text text to be written
		 *  @param $width (optional) if given: text will be justified along given width 
		 */
		public function putText($x, $y, $text, $style = 'left', $width = null, $decode=true) {
			if($decode)
				$text = mb_convert_encoding($text, 'ISO-8859-1', 'UTF8');
			
			$text = preg_replace('/"(\S)/', "\x84\\1", $text);
			$text = preg_replace('/(\S)"/', "\\1\x93", $text);
			$text = strtr($text, [
				'~' => "\xA0",
				'\\,' => "\X7F"
			]);
			$txtWidth = $this->GetStringWidth($text);
			
			if($width === null && isset($this->_pos[W]))
				$width = $this->_pos[W];
			
			if($x === 'c' || $x === 'center') {
				$x = $this->_pos[X];
				$width = $this->_pos[W];
				$style = 'center';
			}
			
			$f = 0;
			if($style == 'center') $f = .5;
			elseif($style == 'right') $f = 1;
			elseif($style == 'justify') $f = -1;
			if($f >= 0) { 
				$this->Text($x + ($width - $txtWidth) * $f, $y, $text);
				return $txtWidth;
			}
			if($style === false || !substr_count($text,' ')) {
				$this->Text($x, $y, $text);
				return $txtWidth;
			}
			
			$ws = ( $width - $txtWidth ) / substr_count($text,' ');
			$this->_out(sprintf('%.3F Tw', $ws * $this->k));
				$this->Text($x, $y, $text);
			$this->_out('0 Tw');
			return $txtWidth;
		}
		
		public function putMultiText($x0, $y, $text, $width = null, $height = 5) {
			$text = mb_convert_encoding($text, 'ISO-8859-1', 'UTF8');
			
			if($width === null)
				$width = $this->_pos[W] - 2*$x0;
			if($width < 0)
				$width = $this->_pos[W] + $width;
			
			$x0 += $this->_pos[X];

			$lines = [];
			while($text) {
				$w = is_array($width) ? array_shift($width) : $width;
				$x = is_array($x0) ? array_shift($x0) : $x0;
				$res = $this->cutIn($text, $cut, $this->_curFontID, $w);
				$lines[] = [$cut, $res, $x, $w];
			}
			
			foreach($lines AS $n => $l) {
				$this->putText($l[2], $y + $n * $height, $l[0], $l[1] ? 'justify' : 'left', $l[3], false);
			}
			return $n * $height + (defined('ABSATZ') ? ABSATZ : 0);
		}
		
		public function verticalText($x, $y, $text, $width, $height) {
			$text = mb_convert_encoding($text, 'ISO-8859-1', 'UTF8');
			$h = $this->getFontIDHeight($text);
			assert($h * strlen($text) < $height);
			
			$dy = ($height - strlen($text) * $h) / strlen($text);
			for($i=0; $i < strlen($text); $i++) {
				$w = $this->GetStringWidth(substr($text, $i, 1));
				$this->Text(
					$x + ($width-$w)/2,
					$y + ($h + $dy) * ($i+1) - $dy/2,
					substr($text, $i, 1)
				);
			}
		}
		
		public function LineM($x1,$y1,$x2,$y2) {
			$this->Line(
				$x1 - $this->LineWidth/2,
				$y1 - $this->LineWidth/2,
				$x2 - $this->LineWidth/2,
				$y2 - $this->LineWidth/2
			);
		}
		public function LineP($x1,$y1,$x2,$y2) {
			$this->Line(
				$x1 + $this->LineWidth/2,
				$y1 + $this->LineWidth/2,
				$x2 + $this->LineWidth/2,
				$y2 + $this->LineWidth/2
			);
		}
		
		public function  Output($dest='', $name='', $isUTF8=false) {
			parent::Output($dest, $name, $isUTF8);
			
			$i = 0;
			while(file_exists('rgb-'.(++$i).'.jpg'))
				unlink('rgb-'.$i.'.jpg');
			$i = 0;
			while(file_exists('cmyk-'.(++$i).'.jpg'))
				unlink('cmyk-'.$i.'.jpg');
		}
		
		protected function _handlePageEnd() {
			if(!in_array('debug', $this->_flags))
				return;
		}
		
		public function SetImgDPI($v) {
			$this->_imgDPI = $v;
		}
		
		public function Img($img, $x, $y, $t, $s, $cw=100,$ch=100,$cx=0,$cy=0) {
			static $no = 0;
			$no++;
			$type = pathinfo($img, PATHINFO_EXTENSION);
			if( $type == 'jpg' || $type == 'jpeg')
				$i = imagecreatefromjpeg($img);
			elseif($type == 'png')
				$i = imagecreatefrompng($img);
			else {
				echo "Unbekannter Bild-Dateityp: ".$img;
				var_dump($type);
				return;
			}
			
			if(!$i) {
				echo "Bild konnte nicht geöffnet werden: ".$img;
				return;
			}
			
			$sw = round(imagesx($i) * $cw / 100);
			$sh = round(imagesy($i) * $ch / 100);
			if($t == 'W') {
				$tw = round(mm2in($s) * $this->_imgDPI);
				$th = round(mm2in($s) * $this->_imgDPI * $sh / $sw);
			} else {
				$th = round(mm2in($s) * $this->_imgDPI);
				$tw = round(mm2in($s) * $this->_imgDPI * $sw / $sh);
			}
			if($tw > $sw || $th > $sw) {
				echo 'Auflösung von '.$img.' zu gering! ';
				echo 'Aktuell '.imagesx($i).'x'.imagesy($i).', ';
				echo 'sollten mindestens '.ceil($tw*100/$cw).'x'.ceil($th*100/$cw).' sein.'."\n";
			}
			
			$j = imagecreatetruecolor($tw, $th);
			imagecopyresampled(
				$j, $i,
				0, 0, 
				round($cx / 100 * imagesx($i)), round($cy / 100 * imagesy($i)),
				$tw, $th, 
				$sw, $sh
			);
			
			imagejpeg($j, 'rgb-'.$no.'.jpg', JPEG_QUALITY);
			exec('convert rgb-'.$no.'.jpg -colorspace cmyk cmyk-'.$no.'.jpg');
			$this->Image('cmyk-'.$no.'.jpg', $x, $y, ($t=='W'?$s:0), ($t!='W'?$s:0));
			
			if($t == 'H')
				return $s * $sw/$sh; 
			return $s * $sh/$sw;
		}
		
		public function SetBox($box, $w, $h, $x = 0, $y = 0) {
			if(!in_array($box, ['MediaBox', 'TrimBox', 'BleedBox', 'CropBox']))
				$this->Error('Incorrect rotation value: '.$rotation);
			if(!isset($this->PageInfo[$this->page]['box']))
				$this->PageInfo[$this->page]['box'] = [];
			$this->PageInfo[$this->page]['box'][$box] = [
				($x + $w) * $this->k,
				($y + $h) * $this->k,
				$x * $this->k,
				$y * $this->k
			];
		}
		
		/* ************************************** *
		 *   extension for switching visibility   *
		 * ************************************** */
		protected $_angle = 0;
		protected $_extgstates = array();

		// alpha: real value from 0 (transparent) to 1 (opaque)
		// bm:    blend mode, one of the following:
		//          Normal, Multiply, Screen, Overlay, Darken, Lighten, ColorDodge, ColorBurn,
		//          HardLight, SoftLight, Difference, Exclusion, Hue, Saturation, Color, Luminosity
		public function SetAlpha($alpha, $bm='Normal') {
			// set alpha for stroking (CA) and non-stroking (ca) operations
			$gs = $this->AddExtGState(array('ca'=>$alpha, 'CA'=>$alpha, 'BM'=>'/'.$bm));
			$this->SetExtGState($gs);
		}
		
		public function AddExtGState($parms) {
			$n = count($this->_extgstates)+1;
			$this->_extgstates[$n]['parms'] = $parms;
			return $n;
		}
		
		public function SetExtGState($gs) {
			$this->_out(sprintf('/GS%d gs', $gs));
		}
		
		public function Rotate($angle, $x = null, $y = null) {
			if(!$x)
				$x=$this->x;
			if(!$y)
				$y=$this->y;
			if($this->_angle != 0)
				$this->_out('Q');
			$this->_angle=$angle;
			if($angle!=0)
			{
				$angle*=M_PI/180;
				$c=cos($angle);
				$s=sin($angle);
				$cx=$x*$this->k;
				$cy=($this->h-$y)*$this->k;
				$this->_out(sprintf('q %.5f %.5f %.5f %.5f %.2f %.2f cm 1 0 0 1 %.2f %.2f cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
			}
		}
		
		public function SetDash($black=null, $white=null) {
			if($black!==null)
				$s = sprintf('[%.3F %.3F] 0 d', $black*$this->k, $white*$this->k);
			else
				$s = '[] 0 d';
			$this->_out($s);
		}
		
		public function SetDrawColor($r, $g = NULL, $b = NULL) {
			//Set color for all stroking operations
			switch(func_num_args()) {
				case 1:
					$g = func_get_arg(0);
					$this->DrawColor = sprintf('%.3F G', $g / 100);
					break;
				case 3:
					$r = func_get_arg(0);
					$g = func_get_arg(1);
					$b = func_get_arg(2);
					$this->DrawColor = sprintf('%.3F %.3F %.3F RG', $r / 255, $g / 255, $b / 255);
					break;
				case 4:
					$c = func_get_arg(0);
					$m = func_get_arg(1);
					$y = func_get_arg(2);
					$k = func_get_arg(3);
					$this->DrawColor = sprintf('%.3F %.3F %.3F %.3F K', $c / 100, $m / 100, $y / 100, $k / 100);
					break;
				default:
					$this->DrawColor = '0 G';
			}
			if($this->page > 0)
				$this->_out($this->DrawColor);
		}
		
		public function SetFillColor($r, $g = NULL, $b = NULL) {
			//Set color for all filling operations
			switch(func_num_args()) {
				case 1:
					$g = func_get_arg(0);
					$this->FillColor = sprintf('%.3F g', $g / 100);
					break;
				case 3:
					$r = func_get_arg(0);
					$g = func_get_arg(1);
					$b = func_get_arg(2);
					$this->FillColor = sprintf('%.3F %.3F %.3F rg', $r / 255, $g / 255, $b / 255);
					break;
				case 4:
					$c = func_get_arg(0);
					$m = func_get_arg(1);
					$y = func_get_arg(2);
					$k = func_get_arg(3);
					$this->FillColor = sprintf('%.3F %.3F %.3F %.3F k', $c / 100, $m / 100, $y / 100, $k / 100);
					break;
				default:
					$this->FillColor = '0 g';
			}
			$this->ColorFlag = ($this->FillColor != $this->TextColor);
			if($this->page > 0)
				$this->_out($this->FillColor);
		}
		
		public function SetTextColor($r, $g = NULL, $b = NULL) {
			//Set color for text
			switch(func_num_args()) {
				case 1:
					$g = func_get_arg(0);
					$this->TextColor = sprintf('%.3F g', $g / 100);
					break;
				case 3:
					$r = func_get_arg(0);
					$g = func_get_arg(1);
					$b = func_get_arg(2);
					$this->TextColor = sprintf('%.3F %.3F %.3F rg', $r / 255, $g / 255, $b / 255);
					break;
				case 4:
					$c = func_get_arg(0);
					$m = func_get_arg(1);
					$y = func_get_arg(2);
					$k = func_get_arg(3);
					$this->TextColor = sprintf('%.3F %.3F %.3F %.3F k', $c / 100, $m / 100, $y / 100, $k / 100);
					break;
				default:
					$this->TextColor = '0 g';
			}
			$this->ColorFlag = ($this->FillColor != $this->TextColor);
		}
		/* * * * * * * * * * * * * * * * * * * * * * * * * */
		/* * * * * * * * * * * * * * * * * * * * * * * * * */
		/* * * * * * * * * * * * * * * * * * * * * * * * * */
		protected function _endpage() {
			$this->_handlePageEnd();
			if($this->_angle != 0) {
				$this->_angle= 0;
				$this->_out('Q');
			}
			parent::_endpage();
		}
		public function _enddoc() {
			if(!empty($this->_extgstates) && $this->PDFVersion<'1.4')
				$this->PDFVersion='1.4';
			parent::_enddoc();
		}
		protected function _putextgstates() {
			for ($i = 1; $i <= count($this->_extgstates); $i++)
			{
				$this->_newobj();
				$this->_extgstates[$i]['n'] = $this->n;
				$this->_out('<</Type /ExtGState');
				$parms = $this->_extgstates[$i]['parms'];
				$this->_out(sprintf('/ca %.3F', $parms['ca']));
				$this->_out(sprintf('/CA %.3F', $parms['CA']));
				$this->_out('/BM '.$parms['BM']);
				$this->_out('>>');
				$this->_out('endobj');
			}
		}
		protected function _putresourcedict() {
			parent::_putresourcedict();
			$this->_out('/ExtGState <<');
			foreach($this->_extgstates as $k=>$extgstate)
				$this->_out('/GS'.$k.' '.$extgstate['n'].' 0 R');
			$this->_out('>>');
		}
		
		protected function _putresources() {
			$this->_putextgstates();
			parent::_putresources();
		}
	}

?>

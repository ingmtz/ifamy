<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
define('IN_CB', true);
include_once('barcode/include/function.php');

class Barcode{
   function generate($text, $scale=2){
   		$params=array('code'		=> 'BCGcode39',
   					  'filetype'	=> 'PNG',
   					  'dpi'			=> 72,//72
   					  'scale'		=> $scale,//2
   					  'rotation'	=> 0,
   					  'font_family'	=> 'Arial.ttf',
   					  'font_size'	=> 10,
   					  'text'		=> $text,
   					  'thickness'	=> 20,
   					  'checksum'	=> ''
   						);
      
      $requiredKeys = array('code', 'filetype', 'dpi', 'scale', 'rotation', 'font_family', 'font_size', 'text');

		// Check if everything is present in the request
		foreach ($requiredKeys as $key) {
		    if (!isset($params[$key])) {
		        $this->showError();
		    }
		}

		if (!preg_match('/^[A-Za-z0-9]+$/', $params['code'])) {
		    $this->showError();
		}

		$code = $params['code'];

		// Check if the code is valid
		if (!file_exists(getcwd ().'\application\libraries\barcode'. DIRECTORY_SEPARATOR .'config' . DIRECTORY_SEPARATOR . $code . '.php')) {
		    $this->showError();
		}

		include_once(getcwd ().'\application\libraries\barcode'. DIRECTORY_SEPARATOR .'config' . DIRECTORY_SEPARATOR . $code . '.php');

		require_once(getcwd ().'\application\libraries\barcode\class'. DIRECTORY_SEPARATOR .'BCGColor.php');
		require_once(getcwd ().'\application\libraries\barcode\class'. DIRECTORY_SEPARATOR .'BCGBarcode.php');
		require_once(getcwd ().'\application\libraries\barcode\class'. DIRECTORY_SEPARATOR .'BCGDrawing.php');
		require_once(getcwd ().'\application\libraries\barcode\class'. DIRECTORY_SEPARATOR .'BCGFontFile.php');

		if (!include_once(getcwd ().'\application\libraries\barcode\class'. DIRECTORY_SEPARATOR . $classFile)) {
		    $this->showError();
		}

		include_once(getcwd ().'\application\libraries\barcode'. DIRECTORY_SEPARATOR .'config'. DIRECTORY_SEPARATOR . $baseClassFile);

		$filetypes = array('PNG' => BCGDrawing::IMG_FORMAT_PNG, 'JPEG' => BCGDrawing::IMG_FORMAT_JPEG, 'GIF' => BCGDrawing::IMG_FORMAT_GIF);

		$drawException = null;
		try {
		    $color_black = new BCGColor(0, 0, 0);
		    $color_white = new BCGColor(255, 255, 255);

		    $code_generated = new $className();

		    if (function_exists('baseCustomSetup')) {
		        baseCustomSetup($code_generated, $params);
		    }

		    if (function_exists('customSetup')) {
		        customSetup($code_generated, $params);
		    }

		    $code_generated->setScale(max(1, min(4, $params['scale'])));
		    $code_generated->setBackgroundColor($color_white);
		    $code_generated->setForegroundColor($color_black);

		    if ($params['text'] !== '') {
		        $text = convertText($params['text']);
		        $code_generated->parse($text);
		    }
		} catch(Exception $exception) {
		    $drawException = $exception;
		}

		$drawing = new BCGDrawing('', $color_white);
		if($drawException) {
		    $drawing->drawException($drawException);
		} else {
		    $drawing->setBarcode($code_generated);
		    $drawing->setRotationAngle($params['rotation']);
		    $drawing->setDPI($params['dpi'] === 'NULL' ? null : max(72, min(300, intval($params['dpi']))));
		    $drawing->draw();
		}

		switch ($params['filetype']) {
		    case 'PNG':
		        header('Content-Type: image/png');
		        break;
		    case 'JPEG':
		        header('Content-Type: image/jpeg');
		        break;
		    case 'GIF':
		        header('Content-Type: image/gif');
		        break;
		}

		$drawing->finish($filetypes[$params['filetype']]);
   }




   function showErrorfd() {
	    header('Content-Type: image/png');
	    readfile('barcode/error.png');
	    exit;
	}
}
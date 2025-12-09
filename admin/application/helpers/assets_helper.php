<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function add_css($stylesheet) {
	$output = '';
	if(!is_array($stylesheet)) return;
	foreach($stylesheet as $css) {
		$output .= '<link rel="stylesheet" type="text/css" href="'.html_escape($css).'">'.PHP_EOL;
		unset($css);
	}
	return $output;
}

function add_js($javascript) {
	$output = '';
	if(!is_array($javascript)) return;
	// echo json_encode($javascript, true);die;
	foreach($javascript as $js) {
		// if(!$js['is_inline_block']) {
		if(!isset($js['is_inline_block'])) { // tambah isset *fauzi
			$output .= trim('<script src="'.html_escape($js['path']).'"');
			if(isset($js['async']) && $js['async']) // tambah isset *fauzi
				$output .= ' async';
			if(isset($js['defer']) && $js['defer'])
				$output .= ' defer';
			$output .= '></script>';
			$output = trim($output).PHP_EOL;
			unset($js);
		} else {
			$output .= '<script type="application/javascript"';
			// if($js['async'])
			if(isset($js['async']) && $js['async']) // tambah isset *fauzi
				$output .= ' async';
			if(isset($js['defer']) && $js['defer'])
				$output .= ' defer';
			$output .= '>'.PHP_EOL;
			$output .= file_get_contents($js['path']).PHP_EOL; // fixing *fauzi
			$output .= '</script>'.PHP_EOL;
		}
	}
	return $output;
}

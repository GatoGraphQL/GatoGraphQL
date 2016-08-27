<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// class GD_DataLoad_TabIOHandler extends GD_DataLoad_IOHandler {
class GD_DataLoad_TabIOHandler extends GD_DataLoad_BlockIOHandler {

	protected function get_fontawesome() {
		return null;
	}
	protected function get_thumb() {
		return null;
	}
	protected function get_pretitle() {
		return null;
	}


	function get_feedback($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed = null, $atts) {
	
		$ret = parent::get_feedback($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed, $atts);

		$pretitle = $this->get_pretitle();

		if ($fontawesome = $this->get_fontawesome()) {
			$ret['fontawesome'] = $fontawesome;
		}

		// If using pretitle, disregard the thumb
		if (!$pretitle) {
			if ($thumb = $this->get_thumb()) {
				$ret['thumb'] = $thumb;
			}
		}

		if ($title = $this->get_title()) {

			if ($pretitle) {
				
				$title = $pretitle.': '.$title;
			}

			$ret['title'] = $title;
		}
		// if ($title = $this->get_title()) {
		// 	$ret['title'] = $title;

		// 	if ($pretitle = $this->get_pretitle()) {
		// 		// $ret['pretitle'] = $pretitle . ' | ';
		// 		$ret['pretitle'] = $pretitle . ' ';
		// 	}
		// }

		return $ret;
	}
}
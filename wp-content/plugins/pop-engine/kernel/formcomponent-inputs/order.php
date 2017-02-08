<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * GD FormInput OrderProfile
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FormInput_Order extends GD_FormInput_Select {
	
	// function get_default_value($conf) {

	// 	// The default value can be set-up outside, eg: in blocks-contents.php or hierarchy.php (eg: to show newest profiles in homepage)
	// 	$orderby = $conf['orderby'];
	// 	$order = $conf['order'];

	// 	return array('orderby' => $orderby, 'order' => $order);
	// }	

	// function get_output_default_value($conf) {

	// 	// The default value can be set-up outside, eg: in blocks-contents.php or hierarchy.php (eg: to show newest profiles in homepage)
	// 	$orderby = $conf['orderby'];
	// 	$order = $conf['order'];

	// 	return $orderby.'|'.$order;
	// }	

	// function get_default_value(/*$conf*/) {

	// 	// The default value can be set-up outside, eg: in blocks-contents.php or hierarchy.php (eg: to show newest profiles in homepage)
	// 	$orderby = $conf['orderby'];
	// 	$order = $conf['order'];

	// 	return $orderby.'|'.$order;
	// }	

	function get_value(/*$conf, */$output = false) {

		$value = parent::get_value(/*$conf, */$output);
		$values = explode('|', $value);
		$ret = array('orderby' => $values[0], 'order' => $values[1]);

		return $ret;
	}		

	function get_output_value(/*$conf*/) {

		return parent::get_value(/*$conf, */true);
	}
}

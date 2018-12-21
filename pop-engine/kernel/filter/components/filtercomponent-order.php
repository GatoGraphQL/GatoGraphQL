<?php
namespace PoP\Engine;

abstract class FilterComponent_OrderBase extends FilterComponentBase {
	
	function get_order($filter) {
	
		return $this->get_filterinput_value($filter);
	}	
	
}

<?php
namespace PoP\Engine;

abstract class FilterComponent_PostDatesBase extends FilterComponentBase {
	
	function get_postdates($filter) {

		return $this->get_filterinput_value($filter);	
	}	
	
}

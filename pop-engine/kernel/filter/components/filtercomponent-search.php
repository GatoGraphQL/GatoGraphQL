<?php
namespace PoP\Engine;

abstract class FilterComponent_SearchBase extends FilterComponentBase {
	
	function get_search($filter) {
	
		return $this->get_filterinput_value($filter);
	}	
}

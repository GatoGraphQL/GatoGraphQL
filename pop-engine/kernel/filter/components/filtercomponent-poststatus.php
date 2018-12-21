<?php
namespace PoP\Engine;

abstract class FilterComponent_PostStatusBase extends FilterComponentBase {
	
	function get_poststatus($filter) {

		return $this->get_filterinput_value($filter);
	}	
	
}

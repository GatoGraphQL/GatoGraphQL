<?php
namespace PoP\Engine;

abstract class FilterComponent_CategoriesBase extends FilterComponentBase {
	
	function get_categories($filter) {
	
		return $this->get_filterinput_value($filter);
	}	
	
}

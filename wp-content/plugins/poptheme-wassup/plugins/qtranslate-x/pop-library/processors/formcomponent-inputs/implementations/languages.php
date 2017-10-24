<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * qTranslate form input Functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_QT_FormInput_Languages extends GD_FormInput_Select {
	
	function get_all_values($label = null) {

		$values = parent::get_all_values($label);

		// Code copied from wp-content/plugins/qtranslate/qtranslate_widget.php
		global $q_config;
		foreach(qtranxf_getSortedLanguages() as $language) {
			
			$values[$language] = $q_config['language_name'][$language];
		}
		
		return $values;
	}	
	
	function get_default_value($output=false) {
	
		return array(qtranxf_getLanguage());
	}		
}

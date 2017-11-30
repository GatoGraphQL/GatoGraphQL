<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_TYPEAHEAD', PoP_TemplateIDUtils::get_template_definition('typeahead'));
define ('POP_RESOURCELOADER_TYPEAHEADSEARCH', PoP_TemplateIDUtils::get_template_definition('typeahead-search'));
define ('POP_RESOURCELOADER_TYPEAHEADFETCHLINK', PoP_TemplateIDUtils::get_template_definition('typeahead-fetchlink'));
define ('POP_RESOURCELOADER_TYPEAHEADSELECTABLE', PoP_TemplateIDUtils::get_template_definition('typeahead-selectable'));
define ('POP_RESOURCELOADER_TYPEAHEADTRIGGERSELECT', PoP_TemplateIDUtils::get_template_definition('typeahead-triggerselect'));
define ('POP_RESOURCELOADER_TYPEAHEADVALIDATE', PoP_TemplateIDUtils::get_template_definition('typeahead-validate'));
define ('POP_RESOURCELOADER_TYPEAHEADSTORAGE', PoP_TemplateIDUtils::get_template_definition('typeahead-storage'));

class PoP_CoreProcessors_TypeaheadResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_TYPEAHEAD,
			POP_RESOURCELOADER_TYPEAHEADSEARCH,
			POP_RESOURCELOADER_TYPEAHEADFETCHLINK,
			POP_RESOURCELOADER_TYPEAHEADSELECTABLE,
			POP_RESOURCELOADER_TYPEAHEADTRIGGERSELECT,
			POP_RESOURCELOADER_TYPEAHEADVALIDATE,
			POP_RESOURCELOADER_TYPEAHEADSTORAGE,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_TYPEAHEAD => 'typeahead',
			POP_RESOURCELOADER_TYPEAHEADSEARCH => 'typeahead-search',
			POP_RESOURCELOADER_TYPEAHEADFETCHLINK => 'typeahead-fetchlink',
			POP_RESOURCELOADER_TYPEAHEADSELECTABLE => 'typeahead-selectable',
			POP_RESOURCELOADER_TYPEAHEADTRIGGERSELECT => 'typeahead-triggerselect',
			POP_RESOURCELOADER_TYPEAHEADVALIDATE => 'typeahead-validate',
			POP_RESOURCELOADER_TYPEAHEADSTORAGE => 'typeahead-storage',
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_version($resource) {
	
		return POP_COREPROCESSORS_VERSION;
	}
	
	function get_dir($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return POP_COREPROCESSORS_DIR.'/js/'.$subpath.'libraries/3rdparties/typeahead';
	}
	
	function get_asset_path($resource) {

		return POP_COREPROCESSORS_DIR.'/js/libraries/3rdparties/typeahead/'.$this->get_filename($resource).'.js';
	}
	
	function get_path($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return POP_COREPROCESSORS_URI.'/js/'.$subpath.'libraries/3rdparties/typeahead';
	}

	function get_jsobjects($resource) {

		$objects = array(
			POP_RESOURCELOADER_TYPEAHEAD => array(
				'popTypeahead',
			),
			POP_RESOURCELOADER_TYPEAHEADSEARCH => array(
				'popTypeaheadSearch',
			),
			POP_RESOURCELOADER_TYPEAHEADFETCHLINK => array(
				'popTypeaheadFetchLink',
			),
			POP_RESOURCELOADER_TYPEAHEADSELECTABLE => array(
				'popTypeaheadSelectable',
			),
			POP_RESOURCELOADER_TYPEAHEADTRIGGERSELECT => array(
				'popTypeaheadTriggerSelect',
			),
			POP_RESOURCELOADER_TYPEAHEADVALIDATE => array(
				'popTypeaheadValidate',
			),
			POP_RESOURCELOADER_TYPEAHEADSTORAGE => array(
				'popTypeaheadStorage',
			),
		);
		if ($object = $objects[$resource]) {

			return $object;
		}

		return parent::get_jsobjects($resource);
	}
	
	function get_dependencies($resource) {

		$dependencies = parent::get_dependencies($resource);
	
		switch ($resource) {
				
			case POP_RESOURCELOADER_TYPEAHEAD:

				$dependencies[] = POP_RESOURCELOADER_EXTERNAL_TYPEAHEAD;

				// Also add the Handlebar templates needed to render the typeahead views on runtime
				if ($typeahead_layouts = array_unique(apply_filters(
					'PoP_CoreProcessors_ResourceLoaderProcessor:typeahead:templates',
					array()
				))) {
					$dependencies = array_merge(
						$dependencies,
						$typeahead_layouts
					);
				}
				break;
		}

		return $dependencies;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_CoreProcessors_TypeaheadResourceLoaderProcessor();

<?php
namespace PoP\Engine;

class Engine_Hooks {

	function __construct() {

		// Add functions as hooks, so we allow PoP_Application to set the 'global-state' first
		add_action(
			'\PoP\Engine\Engine_Vars:add_vars',
			array($this, 'add_vars'),
			10,
			1
		);
		add_filter(
			'ModelInstanceProcessor:model_instance_components_from_vars',
			array($this, 'get_model_instance_components_from_vars')
		);
	}

	public function add_vars($vars_in_array) {

		// Allow WP API to set the "global-state" first
		// Each page is an independent configuration
		$vars = &$vars_in_array[0];
		if (Server\Utils::enable_api() && $vars['action'] == POP_ACTION_API) {

			$this->add_fields_to_vars($vars);
		}
		elseif ($vars['global-state']['is-page']) {

			$dataquery_manager = DataQuery_Manager_Factory::get_instance();
			$post = $vars['global-state']['queried-object'];

			// Special pages: dataqueries' cacheablepages serve layouts, noncacheable pages serve fields.
			// So the settings for these pages depend on the URL params
			if (in_array($post->ID, $dataquery_manager->get_noncacheablepages())) {

				$this->add_fields_to_vars($vars);
			}
			elseif (in_array($post->ID, $dataquery_manager->get_cacheablepages())) {

				if ($layouts = $_REQUEST[GD_URLPARAM_LAYOUTS]) {
					
					$layouts = is_array($layouts) ? $layouts : array($layouts);
					$vars['layouts'] = $layouts;
				}
			}
		}
	}

	private function add_fields_to_vars(&$vars) {

		if (isset($_REQUEST[GD_URLPARAM_FIELDS])) {

			// The fields param can either be an array or a string
			// If it is a string, split the items with ',', and the inner items with '.'
			if (is_array($_REQUEST[GD_URLPARAM_FIELDS])) {

				$fields = $_REQUEST[GD_URLPARAM_FIELDS];
			}
			else {

				$fields = array();
				$pointer = &$fields;

				foreach (explode(POP_CONSTANT_PARAMVALUE_SEPARATOR, $_REQUEST[GD_URLPARAM_FIELDS]) as $commafields) {

					$dotfields = explode(POP_CONSTANT_DOTSYNTAX_DOT, $commafields);
					for ($i = 0; $i < count($dotfields)-1; $i++) {
						$pointer[$dotfields[$i]] = $pointer[$dotfields[$i]] ?? array();
						$pointer = &$pointer[$dotfields[$i]];
					}

					$pointer[] = $dotfields[count($dotfields)-1];
					$pointer = &$fields;
				}
			}

			$vars['fields'] = $fields;
		}
	}

	private function add_fields_to_components(&$components) {

		$vars = Engine_Vars::get_vars();
		if ($fields = $vars['fields']) {

			// Serialize instead of implode, because $fields can contain $key => $value
			$components[] = __('fields:', 'pop-engine').serialize($fields);
		}
	}

	public function get_model_instance_components_from_vars($components) {

		// Allow WP API to set the "global-state" first
		// Each page is an independent configuration
		$vars = Engine_Vars::get_vars();
		if (Server\Utils::enable_api() && $vars['action'] == POP_ACTION_API) {

			$this->add_fields_to_components($components);
		}
		elseif ($vars['global-state']['is-page']) {

			$dataquery_manager = DataQuery_Manager_Factory::get_instance();
			$post = $vars['global-state']['queried-object'];

			// Special pages: dataqueries' cacheablepages serve layouts, noncacheable pages serve fields.
			// So the settings for these pages depend on the URL params
			if (in_array($post->ID, $dataquery_manager->get_noncacheablepages())) {

				$this->add_fields_to_components($components);
			}
			elseif (in_array($post->ID, $dataquery_manager->get_cacheablepages())) {

				if ($layouts = $vars['layouts']) {

					$components[] = __('layouts:', 'pop-engine').implode('.', $layouts);
				}
			}
		}

		return $components;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Engine_Hooks();


<?php
class PoP_ResourceLoader_ConfigHierarchyFormatCombinationResourcesFileGenerator extends PoP_ResourceLoader_ConfigAddResourcesFileGeneratorBase {

	protected $hierarchies;
	protected $current_hierarchy, $current_format;

	function __construct() {

		global $gd_template_settingsmanager;
		$this->hierarchies = $gd_template_settingsmanager->get_hierarchies();
	}

	function get_dir() {

		return parent::get_dir().'/partials';
	}
	function get_url() {

		return parent::get_url().'/partials';
	}

	// Generate multiple config files (one for each combination of hierarchy and format) instead of just one
	function generate() {

		$renderer = $this->get_renderer();
		$renderer_filereproductions = $renderer->get();
		foreach ($this->hierarchies as $hierarchy) {

			// Assign the hierarchy and format to both the generator, for the filename,
			// and to the filereproductions, to generate the configuration
			$this->current_hierarchy = $hierarchy;
			foreach ($renderer_filereproductions as $filereproduction) {
				$filereproduction->setHierarchy($hierarchy);
			}

			// Obtain the list of formats configured under that hierarchy
			if ($page_formats = PoP_ResourceLoaderProcessorUtils::get_pages_and_formats_added_under_hierarchy($hierarchy)) {
	            
	            // $page_formats is array of ($page_id => array($format))
	            $formats = array_unique(array_flatten(array_values($page_formats)));		            
	            foreach ($formats as $format) {

					$this->current_format = $format;
					foreach ($renderer_filereproductions as $filereproduction) {

						$filereproduction->setFormat($format);
					}

					// Finally, given this combination of hierarchy and format, call the parent generate function
					parent::generate();
				}
			}
		}
	}

	function get_filename() {

		return $this->get_variable_filename($this->current_hierarchy, $this->current_format);
	}

	function get_variable_filename($hierarchy, $format) {

		return sprintf(
			// 'resourceloader-config-resources-%s%s.js',
			'config-resources-%s%s.js',
			$hierarchy,
			$format
		);
	}

	function get_renderer() {

		global $pop_resourceloader_hierarchyformatcombinationresources_configfile_renderer;
		return $pop_resourceloader_hierarchyformatcombinationresources_configfile_renderer;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloader_hierarchyformatcombinationresources_configfile_generator;
$pop_resourceloader_hierarchyformatcombinationresources_configfile_generator = new PoP_ResourceLoader_ConfigHierarchyFormatCombinationResourcesFileGenerator();
<?php
use PoP\FileStore\Facades\JSONFileStoreFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_ResourceLoader_GeneratedResourcesManager {

	public $generatedfiles, $initialized;

	public function __construct() {

		$this->reset();
		$this->initialized = false;

		// If the state changes, save it at the end of the execution
        HooksAPIFacade::getInstance()->addAction(
        	'popcms:shutdown',
        	array($this, 'maybeSave')
        );
	}

	protected function reset() {

		$this->generatedfiles = array(
			'js-bundlegroup-id-by-loading-type' => array(),
			'js-bundle-ids-by-loading-type' => array(),
			'js-resources-by-loading-type' => array(),
			'js-bundlegroup-ids' => array(),
			'js-bundle-ids' => array(),
			'js-resources' => array(),
			'css-bundlegroup-ids' => array(),
			'css-bundle-ids' => array(),
			'css-resources' => array(),
		);
	}

	function init() {

		// Allows lazy init
		if (!$this->initialized) {

			$this->initialized = true;

			// The DB may have been created in STAGING when running /generate-theme/, so make sure it is copied to PROD during deployment
			// Get the inner variable from the cache, if it exists
			global $pop_resourceloader_generatedfilesfilelocation;
			$this->generatedfiles = JSONFileStoreFacade::getInstance()->get($pop_resourceloader_generatedfilesfilelocation);
		}
	}

    function maybeSave() {

        if ($this->modified) {

            $this->save();   
        }
    }

	public function save() {

		global $pop_resourceloader_generatedfilesfilelocation;
		// $pop_resourceloader_generatedfilesfilelocation->save($this->generatedfiles);
		JSONFileStoreFacade::getInstance()->save($pop_resourceloader_generatedfilesfilelocation, $this->generatedfiles);
	}

	public function delete() {

		$this->reset();
		
		global $pop_resourceloader_generatedfilesfilelocation;
		// $pop_resourceloader_generatedfilesfilelocation->delete();
		JSONFileStoreFacade::getInstance()->delete($pop_resourceloader_generatedfilesfilelocation);
	}

	public function getJsBundlegroupIds($model_instance_id, $subtype) {

		// Lazy init
		$this->init();
		return $this->generatedfiles['js-bundlegroup-ids'][$model_instance_id][$subtype];
	}
	public function setJsBundlegroupIds($model_instance_id, $subtype, $bundlegroup_ids) {

		// Lazy init
		$this->init();
		$this->generatedfiles['js-bundlegroup-ids'][$model_instance_id][$subtype] = $bundlegroup_ids;
		$this->modified = true;
	}
	public function getJsBundleIds($model_instance_id, $subtype) {

		// Lazy init
		$this->init();
		return $this->generatedfiles['js-bundle-ids'][$model_instance_id][$subtype];
	}
	public function setJsBundleIds($model_instance_id, $subtype, $bundle_ids) {

		// Lazy init
		$this->init();
		$this->generatedfiles['js-bundle-ids'][$model_instance_id][$subtype] = $bundle_ids;
		$this->modified = true;
	}
	public function getJsResources($model_instance_id, $subtype) {

		// Lazy init
		$this->init();
		return $this->generatedfiles['js-resources'][$model_instance_id][$subtype];
	}
	public function setJsResources($model_instance_id, $subtype, $resources) {

		// Lazy init
		$this->init();
		$this->generatedfiles['js-resources'][$model_instance_id][$subtype] = $resources;
		$this->modified = true;
	}

	public function getJsBundlegroupIdByLoadingType($model_instance_id, $subtype, $loading_type) {

		// Lazy init
		$this->init();
		return $this->generatedfiles['js-bundlegroup-id-by-loading-type'][$model_instance_id][$subtype][$loading_type];
	}
	public function setJsBundlegroupIdByLoadingType($model_instance_id, $subtype, $loading_type, $bundlegroup_id) {

		// Lazy init
		$this->init();
		$this->generatedfiles['js-bundlegroup-id-by-loading-type'][$model_instance_id][$subtype][$loading_type] = $bundlegroup_id;
		$this->modified = true;
	}
	public function getJsBundleIdsByLoadingType($model_instance_id, $subtype, $loading_type) {

		// Lazy init
		$this->init();
		return $this->generatedfiles['js-bundle-ids-by-loading-type'][$model_instance_id][$subtype][$loading_type];
	}
	public function setJsBundleIdsByLoadingType($model_instance_id, $subtype, $loading_type, $bundle_ids) {

		// Lazy init
		$this->init();
		$this->generatedfiles['js-bundle-ids-by-loading-type'][$model_instance_id][$subtype][$loading_type] = $bundle_ids;
		$this->modified = true;
	}
	public function getJsResourcesByLoadingType($model_instance_id, $subtype, $loading_type) {

		// Lazy init
		$this->init();
		return $this->generatedfiles['js-resources-by-loading-type'][$model_instance_id][$subtype][$loading_type];
	}
	public function setJsResourcesByLoadingType($model_instance_id, $subtype, $loading_type, $resources) {

		// Lazy init
		$this->init();
		$this->generatedfiles['js-resources-by-loading-type'][$model_instance_id][$subtype][$loading_type] = $resources;
		$this->modified = true;
	}

	public function getCssBundlegroupIds($model_instance_id, $subtype) {

		// Lazy init
		$this->init();
		return $this->generatedfiles['css-bundlegroup-ids'][$model_instance_id][$subtype];
	}
	public function setCssBundlegroupIds($model_instance_id, $subtype, $bundlegroup_ids) {

		// Lazy init
		$this->init();
		$this->generatedfiles['css-bundlegroup-ids'][$model_instance_id][$subtype] = $bundlegroup_ids;
		$this->modified = true;
	}
	public function getCssBundleIds($model_instance_id, $subtype) {

		// Lazy init
		$this->init();
		return $this->generatedfiles['css-bundle-ids'][$model_instance_id][$subtype];
	}
	public function setCssBundleIds($model_instance_id, $subtype, $bundle_ids) {

		// Lazy init
		$this->init();
		$this->generatedfiles['css-bundle-ids'][$model_instance_id][$subtype] = $bundle_ids;
		$this->modified = true;
	}
	public function getCssResources($model_instance_id, $subtype) {

		// Lazy init
		$this->init();
		return $this->generatedfiles['css-resources'][$model_instance_id][$subtype];
	}
	public function setCssResources($model_instance_id, $subtype, $resources) {

		// Lazy init
		$this->init();
		$this->generatedfiles['css-resources'][$model_instance_id][$subtype] = $resources;
		$this->modified = true;
	}
}

/**
 * Initialization
 */
global $pop_resourceloader_generatedfilesmanager;
$pop_resourceloader_generatedfilesmanager = new PoP_ResourceLoader_GeneratedResourcesManager();

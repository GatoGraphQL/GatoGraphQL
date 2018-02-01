<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Core (reusable) functions of the website
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ResourceLoader_MappingStorageManager {

	public $entries, $initialized;

	function __construct() {

		$this->entries = array();
		$this->initialized = false;
	}

	function init() {

		// Allows lazy init
		if (!$this->initialized) {

			$this->initialized = true;

			// The DB was created in STAGING when running /generate-theme/, so make sure it is copied to PROD during deployment
			if (PoP_Frontend_ServerUtils::use_generatetheme_output_files()) {

				// Get the inner variable from the cache, if it exists
				global $pop_resourceloader_bundlemappingstoragefilelocation, $pop_resourceloader_bundlegroupmappingstoragefilelocation, $pop_resourceloader_keymappingstoragefilelocation, $pop_resourceloader_bundleversionstoragefilelocation, $pop_resourceloader_bundlegroupversionstoragefilelocation;
				$this->entries = array(
					'bundle-ids' => $pop_resourceloader_bundlemappingstoragefilelocation->get(),
					'bundlegroup-ids' => $pop_resourceloader_bundlegroupmappingstoragefilelocation->get(),
					'key-ids' => $pop_resourceloader_keymappingstoragefilelocation->get(),
					'bundle-versions' => $pop_resourceloader_bundleversionstoragefilelocation->get(),
					'bundlegroup-versions' => $pop_resourceloader_bundlegroupversionstoragefilelocation->get(),
				);
			}
		}
	}

	public function save($bundle_ids, $bundlegroup_ids, $key_ids, $bundle_versions, $bundlegroup_versions) {

		global $pop_resourceloader_bundlemappingstoragefilelocation, $pop_resourceloader_bundlegroupmappingstoragefilelocation, $pop_resourceloader_keymappingstoragefilelocation, $pop_resourceloader_bundleversionstoragefilelocation, $pop_resourceloader_bundlegroupversionstoragefilelocation;
		$pop_resourceloader_bundlemappingstoragefilelocation->save($bundle_ids);
		$pop_resourceloader_bundlegroupmappingstoragefilelocation->save($bundlegroup_ids);
		$pop_resourceloader_keymappingstoragefilelocation->save($key_ids);
		$pop_resourceloader_bundleversionstoragefilelocation->save($bundle_versions);
		$pop_resourceloader_bundlegroupversionstoragefilelocation->save($bundlegroup_versions);
	}

	public function delete($across_thememodes) {

		// Delete the "key-ids" mapping always, since it depends on a specific thememode
		global $pop_resourceloader_keymappingstoragefilelocation;
		$pop_resourceloader_keymappingstoragefilelocation->delete();

		// Attention: bundles and bundlegroups are shared across thememodes, so we can't delete them unless explicitly set
		// This is because `system_generate_theme()` is called several times, once for each thememode: sliding, print, embed. And since these shared
		// the generated bundle/bundlegroups, then we can't delete the mapping when running the process for the 2nd, 3rd, etc time
		if ($across_thememodes) {

			global $pop_resourceloader_bundlemappingstoragefilelocation, $pop_resourceloader_bundlegroupmappingstoragefilelocation, $pop_resourceloader_bundleversionstoragefilelocation, $pop_resourceloader_bundlegroupversionstoragefilelocation;
			$pop_resourceloader_bundlemappingstoragefilelocation->delete();
			$pop_resourceloader_bundlegroupmappingstoragefilelocation->delete();
			$pop_resourceloader_bundleversionstoragefilelocation->delete();
			$pop_resourceloader_bundlegroupversionstoragefilelocation->delete();
		}
	}

	public function has_cached_entries() {

		// Lazy init
		$this->init();
		return !empty($this->entries);
	}

	public function get_bundle_ids() {

		// Lazy init
		$this->init();
		return $this->entries['bundle-ids'];
	}

	public function get_bundlegroup_ids() {

		// Lazy init
		$this->init();
		return $this->entries['bundlegroup-ids'];
	}

	public function get_key_ids() {

		// Lazy init
		$this->init();
		return $this->entries['key-ids'];
	}

	public function get_bundle_versions() {

		// Lazy init
		$this->init();
		return $this->entries['bundle-versions'];
	}

	public function get_bundlegroup_versions() {

		// Lazy init
		$this->init();
		return $this->entries['bundlegroup-versions'];
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloader_mappingstoragemanager;
$pop_resourceloader_mappingstoragemanager = new PoP_ResourceLoader_MappingStorageManager();

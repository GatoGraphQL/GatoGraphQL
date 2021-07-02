<?php
use PoP\FileStore\Facades\JSONFileStoreFacade;

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

			$fileStore = JSONFileStoreFacade::getInstance();
			// The DB may have created in STAGING when running /generate-theme/, so make sure it is copied to PROD during deployment
			// Get the inner variable from the cache, if it exists
			global $pop_resourceloader_bundlemappingstoragefilelocation, $pop_resourceloader_bundlegroupmappingstoragefilelocation, $pop_resourceloader_keymappingstoragefilelocation, $pop_resourceloader_bundleversionstoragefilelocation, $pop_resourceloader_bundlegroupversionstoragefilelocation;
			$this->entries = array(
				// 'bundle-ids' => $pop_resourceloader_bundlemappingstoragefilelocation->get(),
				// 'bundlegroup-ids' => $pop_resourceloader_bundlegroupmappingstoragefilelocation->get(),
				// 'key-ids' => $pop_resourceloader_keymappingstoragefilelocation->get(),
				// 'bundle-versions' => $pop_resourceloader_bundleversionstoragefilelocation->get(),
				// 'bundlegroup-versions' => $pop_resourceloader_bundlegroupversionstoragefilelocation->get(),
				'bundle-ids' => $fileStore->get($pop_resourceloader_bundlemappingstoragefilelocation),
				'bundlegroup-ids' => $fileStore->get($pop_resourceloader_bundlegroupmappingstoragefilelocation),
				'key-ids' => $fileStore->get($pop_resourceloader_keymappingstoragefilelocation),
				'bundle-versions' => $fileStore->get($pop_resourceloader_bundleversionstoragefilelocation),
				'bundlegroup-versions' => $fileStore->get($pop_resourceloader_bundlegroupversionstoragefilelocation),
			);
		}
	}

	public function save($bundle_ids, $bundlegroup_ids, $key_ids, $bundle_versions, $bundlegroup_versions) {

		global $pop_resourceloader_bundlemappingstoragefilelocation, $pop_resourceloader_bundlegroupmappingstoragefilelocation, $pop_resourceloader_keymappingstoragefilelocation, $pop_resourceloader_bundleversionstoragefilelocation, $pop_resourceloader_bundlegroupversionstoragefilelocation;
		// $pop_resourceloader_bundlemappingstoragefilelocation->save($bundle_ids);
		// $pop_resourceloader_bundlegroupmappingstoragefilelocation->save($bundlegroup_ids);
		// $pop_resourceloader_keymappingstoragefilelocation->save($key_ids);
		// $pop_resourceloader_bundleversionstoragefilelocation->save($bundle_versions);
		// $pop_resourceloader_bundlegroupversionstoragefilelocation->save($bundlegroup_versions);
		$fileStore = JSONFileStoreFacade::getInstance();
		$fileStore->save($pop_resourceloader_bundlemappingstoragefilelocation, $bundle_ids);
		$fileStore->save($pop_resourceloader_bundlegroupmappingstoragefilelocation, $bundlegroup_ids);
		$fileStore->save($pop_resourceloader_keymappingstoragefilelocation, $key_ids);
		$fileStore->save($pop_resourceloader_bundleversionstoragefilelocation, $bundle_versions);
		$fileStore->save($pop_resourceloader_bundlegroupversionstoragefilelocation, $bundlegroup_versions);
	}

	public function delete($delete_current_mapping)
	{
		$fileStore = JSONFileStoreFacade::getInstance();

		// Delete the "key-ids" mapping always, since it depends on a specific thememode
		global $pop_resourceloader_keymappingstoragefilelocation;
		// $pop_resourceloader_keymappingstoragefilelocation->delete();
		$fileStore->delete($pop_resourceloader_keymappingstoragefilelocation);


		// Attention: bundles and bundlegroups are shared across thememodes, so we can't delete them unless explicitly set
		// This is because `systemGenerateTheme()` is called several times, once for each thememode: sliding, print, embed. And since these shared
		// the generated bundle/bundlegroups, then we can't delete the mapping when running the process for the 2nd, 3rd, etc time
		if ($delete_current_mapping) {

			global $pop_resourceloader_bundlemappingstoragefilelocation, $pop_resourceloader_bundlegroupmappingstoragefilelocation, $pop_resourceloader_bundleversionstoragefilelocation, $pop_resourceloader_bundlegroupversionstoragefilelocation;
			// $pop_resourceloader_bundlemappingstoragefilelocation->delete();
			// $pop_resourceloader_bundlegroupmappingstoragefilelocation->delete();
			// $pop_resourceloader_bundleversionstoragefilelocation->delete();
			// $pop_resourceloader_bundlegroupversionstoragefilelocation->delete();
			$fileStore->delete($pop_resourceloader_bundlemappingstoragefilelocation);
			$fileStore->delete($pop_resourceloader_bundlegroupmappingstoragefilelocation);
			$fileStore->delete($pop_resourceloader_bundleversionstoragefilelocation);
			$fileStore->delete($pop_resourceloader_bundlegroupversionstoragefilelocation);
		}
	}

	public function hasCachedEntries() {

		// Lazy init
		$this->init();
		return !empty(array_filter(array_values($this->entries)));
	}

	public function getBundleIds() {

		// Lazy init
		$this->init();
		return $this->entries['bundle-ids'];
	}

	public function getBundlegroupIds() {

		// Lazy init
		$this->init();
		return $this->entries['bundlegroup-ids'];
	}

	public function getKeyIds() {

		// Lazy init
		$this->init();
		return $this->entries['key-ids'];
	}

	public function getBundleVersions() {

		// Lazy init
		$this->init();
		return $this->entries['bundle-versions'];
	}

	public function getBundlegroupVersions() {

		// Lazy init
		$this->init();
		return $this->entries['bundlegroup-versions'];
	}
}

/**
 * Initialization
 */
global $pop_resourceloader_mappingstoragemanager;
$pop_resourceloader_mappingstoragemanager = new PoP_ResourceLoader_MappingStorageManager();

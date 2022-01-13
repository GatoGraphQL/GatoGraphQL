<?php
use PoP\ComponentModel\Misc\GeneralUtils;

class PoP_ResourceLoaderProcessorState {

    protected $initialized = false;
    protected $saveEntries = false;
    protected $bundle_ids, $bundle_counter, $bundlegroup_ids, $bundlegroup_counter, $key_ids, $key_counter, $bundle_versions, $bundlegroup_versions;

    public function __construct() {

        $this->initialized = $this->saveEntries = false;
        $this->bundle_ids = $this->bundle_counter = $this->bundlegroup_ids = $this->bundlegroup_counter = $this->key_ids = $this->key_counter = $this->bundle_versions = $this->bundlegroup_versions = array();

        // If the state changes, save it at the end of the execution
        \PoP\Root\App::getHookManager()->addAction(
            'popcms:shutdown',
            array($this, 'maybeSaveEntries')
        );
    }

    function resetInitialized() {

        $this->initialized = false;
    }

    function deleteEntries() {

        // Re-initialize the inner variables
        $this->resetInitialized();
        $this->init();

        // Allow to delete the entries (or not!) for pop-cluster-resourceloader
        \PoP\Root\App::getHookManager()->doAction('PoP_ResourceLoaderProcessorUtils:deleteEntries');
    }

    function saveEntries() {

        // Get the already generated entries from the cache
        global $pop_resourceloader_mappingstoragemanager;
        $pop_resourceloader_mappingstoragemanager->save($this->bundle_ids, $this->bundlegroup_ids, $this->key_ids, $this->bundle_versions, $this->bundlegroup_versions);

        // Allow to save the entries for pop-cluster-resourceloader
        \PoP\Root\App::getHookManager()->doAction('PoP_ResourceLoaderProcessorUtils:saveEntries');
    }

    function maybeSaveEntries() {

        if ($this->saveEntries) {

            $this->saveEntries();   
        }
    }

    function init() {

        if (!$this->initialized) {

            $this->initialized = true;

            // Get the already generated entries from the cache
            global $pop_resourceloader_mappingstoragemanager;
            if ($pop_resourceloader_mappingstoragemanager->hasCachedEntries()) {

                $this->bundle_ids = $pop_resourceloader_mappingstoragemanager->getBundleIds();
                $this->bundlegroup_ids = $pop_resourceloader_mappingstoragemanager->getBundlegroupIds();
                $this->key_ids = $pop_resourceloader_mappingstoragemanager->getKeyIds();
                $this->bundle_versions = $pop_resourceloader_mappingstoragemanager->getBundleVersions();
                $this->bundlegroup_versions = $pop_resourceloader_mappingstoragemanager->getBundlegroupVersions();

                // Start the counter in 1 plus than the elements we already have (actually, the counter should not be needed!)
                $this->bundle_counter = count($this->bundle_ids) + 1;
                $this->bundlegroup_counter = count($this->bundlegroup_ids) + 1;
                $this->key_counter = count($this->key_ids) + 1;
            }
            else {

                $this->bundle_ids = $this->bundlegroup_ids = $this->key_ids = $this->bundle_versions = $this->bundlegroup_versions = array();

                // Start the counter in 1, so we have an hashmap instead of an array on the generated file
                $this->bundle_counter = $this->bundlegroup_counter = $this->key_counter = 1;
            }
        }
    }

    function getKeyId($key) {

        $this->init();
        return $this->getStringId($key, $this->key_ids, $this->key_counter);
    }

    protected function getStringId($string, &$string_ids, &$string_counter) {

        // Instead of calculating a hash, simply keep a counter, in order to further reduce the size of the generated file
        $string_id = $string_ids[$string];
        if (!is_null($string_id)) { // It could be 0

            return $string_id;
        }

        // Create a new entry => save file at the end of request
        $this->saveEntries = true;

        // It is not there yet, create a new entry and return it
        $string_id = $string_counter++;
        $string_ids[$string] = $string_id;
        return $string_id;
    }

    function getBundleVersion($bundleId) {

        $this->init();
        return $this->bundle_versions[$bundleId];
    }

    function getBundlegroupVersion($bundleGroupId) {

        $this->init();
        return $this->bundlegroup_versions[$bundleGroupId];
    }

    function setBundleVersion($bundleId, $version) {

        $this->init();
        
        // Create a new entry => save file at the end of request
        $this->saveEntries = true;

        $this->bundle_versions[$bundleId] = $version;
    }

    function setBundlegroupVersion($bundleGroupId, $version) {

        $this->init();

        // Create a new entry => save file at the end of request
        $this->saveEntries = true;
        
        $this->bundlegroup_versions[$bundleGroupId] = $version;
    }

    function getBundleId($resources, $addRandom) {

        $this->init();
        return $this->getSetId('bundle', $resources, $this->bundle_ids, $this->bundle_counter, $addRandom);
    }

    function getBundlegroupId($resourcebundles, $addRandom) {

        $this->init();

        // Flatten the bundles, which is currently an array of arrays, so that array_multisort works fine
        // (otherwise it sorts the outer array, but differently sorted inner elements are not sorted 
        // and so 2 sets with same elements may still be different)
        return $this->getSetId('bundlegroup', arrayFlatten($resourcebundles), $this->bundlegroup_ids, $this->bundlegroup_counter, $addRandom);
    }

    protected function getSetId($handle, $set, &$set_ids, &$set_counter, $addRandom) {

        // Order them, so that 2 sets with the same resources, but on different order, are still considered the same
        array_multisort($set);

        // Transform into a string
        $encoded = json_encode($set);

        // Instead of calculating a hash, simply keep a counter, in order to further reduce the size of the generated file
        $set_id = $set_ids[$encoded];
        if (!is_null($set_id)) { // It could be 0

            return $set_id;
        }

        // Create a new entry => save file at the end of request
        $this->saveEntries = true;

        // It is not there yet, create a new entry and return it
        $set_id = $set_counter++;
        
        // Attach a random string, to avoid to concurrent threads from creating the same ID for 2 different objects
        // With the random string, the file may be duplicated and one of them will never be used, but at least no file will override a different one
        // Needed only for when generating the bundlefiles on runtime, not needed for when generating resources.js with the list of resources to fetch for each route
        if ($addRandom) {
            
            $set_id .= '-'.GeneralUtils::generateRandomString(8, false);
        }

        $set_ids[$encoded] = $set_id;
        return $set_id;
    }
}

/**
 * Initialization
 */
global $pop_resourceloaderprocessor_state;
$pop_resourceloaderprocessor_state = new PoP_ResourceLoaderProcessorState();

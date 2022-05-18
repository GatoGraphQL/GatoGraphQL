<?php

use PoP\ComponentModel\App;
use PoP\ComponentModel\ModuleConfiguration as ComponentModelModuleConfiguration;
use PoP\ComponentModel\ModuleInfo as ComponentModelModuleInfo;
use PoP\ComponentModel\Facades\Cache\PersistentCacheFacade;
use PoP\ComponentModel\Facades\Engine\EngineFacade;
use PoP\ComponentModel\Facades\HelperServices\DataloadHelperServiceFacade;
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeHelpers;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

class PoP_SSR_EngineInitialization_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'webplatform-engine:main_html',
            $this->getMainHtml(...)
        );

        \PoP\Root\App::addFilter(
            'PoPWebPlatform_Initialization:init-scripts',
            $this->initScripts(...)
        );

        \PoP\Root\App::addFilter(
            'PoPWebPlatform_Engine:encoded-data-object',
            $this->getEncodedDataObject(...),
            10,
            2
        );

        \PoP\Root\App::addFilter(
            'add-scripts:where',
            $this->getScriptsWhere(...)
        );
    }

    public function getMainHtml($html)
    {
        $engine = EngineFacade::getInstance();
        $entryComponent = $engine->getEntryComponent();
        return PoP_ServerSideRendering_Utils::renderPagesection($entryComponent);
    }

    public function initScripts($scripts)
    {

        // Comment Leo 10/06/2017: If doing the server-side rendering, then we must print all the generated IDs to run all JS methods,
        // before calling pop.Manager.init()
        if (!PoP_SSR_ServerUtils::disableServerSideRendering()) {
            $popJSRuntimeManager = PoP_ServerSide_LibrariesFactory::getJsruntimeInstance();
            $scripts[] = sprintf(
                'pop.JSRuntimeManager[\'full-session-ids\'] = %s;',
                json_encode($popJSRuntimeManager->getSessionIds('full'))
            );
            // Comment Leo 30/10/2017: when initially loading the website, the full-session-ids and last-session-ids are the same
            // So instead of sending the code below (which could be up to 100kb of code!) simply duplicate the entry above through JS
            // $scripts[] = sprintf(
            //     'pop.JSRuntimeManager[\'last-session-ids\'] = %s;',
            //     json_encode($popJSRuntimeManager->getSessionIds('last'))
            // );
            $scripts[] = 'pop.JSRuntimeManager[\'last-session-ids\'] = jQuery.extend(true, {}, pop.JSRuntimeManager[\'full-session-ids\']);';
        }

        return $scripts;
    }

    public function getEncodedDataObject($data, $engine)
    {

        // Optimizations to be made when first loading the website
        if (RequestUtils::loadingSite()) {
            // If we are using serverside-rendering, and set the config to remove the database code,
            // then do not send the data to the front-end (most likely there is no need, since the HTML has already been produced)
            if (PoP_SSR_ServerUtils::removeDatabasesFromOutput()) {
                // Improvements: remove the database, the dbobjectids, and the configuration
                // Do it in this order, since the dbobjectids is still needed for removing the database
                $this->removeDatabases($data, $engine);
                $this->removeConfiguration($data, $engine);

                // Removing the dbobjectids is too much of a trouble, since pop.Manager currently expects the keys for pageSection/block to be set, so that keeping them,
                // and just removing the IDs, is not worth the trouble. In addition, the IDs may be needed dynamically, eg: in function pop.Manager.processBlock
                // $this->removeDataset($data, $engine);
            }
        }

        return $data;
    }

    // protected function removeDataset(&$data, $engine) {

    //     // Simply set the dbobjectids as empty
    //     $data['dbobjectids'] = array();

    //     // // Set the pageSection => block entries of the dbobjectids as empty (it still expects these entries on the webplatform, so we can't just unset them)
    //     // $dbobjectids = $data['dbobjectids'];
    //     // $data['dbobjectids'] = array();
    //     // foreach ($dbobjectids as $pagesection_settings_id => $block_settings_id_dataset) {

    //     //     $data['dbobjectids'][$pagesection_settings_id] = array();
    //     //     foreach ($block_settings_id_dataset as $block_settings_id => $block_dataset) {

    //     //         $data['dbobjectids'][$pagesection_settings_id][$block_settings_id] = array();
    //     //     }
    //     // }
    // }

    protected function removeConfiguration(&$data, $engine)
    {

        // Iterate all the levels of the configuration, stepping down from module to its submodules,
        // and delete all their properties unless their module has been marked as dynamic, in which case the data will be needed to print their HTML dynamically in the webplatform
    }

    protected function removeDatabases(&$data, $engine)
    {
        if (!$data['dbData']) {
            return;
        }

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        // Swap the DBs: send the ones with the dynamic data-fields instead (those fields which are always needed in the webplatform, as such they can't be removed)
        // Calculate the Dynamic Databases
        $dynamicdatabases = array();

        // From the dbobjectids, we obtain the needed data only for the required IDs and nothing else
        $dbobjectids = $data['datasetcomponentdata']['combinedstate']['dbobjectids'];

        // Calculate the dynamic data settings
        $entryComponent = $engine->getEntryComponent();
        $entryComponentOutputName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($entryComponent);

        // Get the static data properties
        // First check if there's a cache stored
        $cachemanager = null;
        if ($useCache = ComponentModelModuleConfiguration::useComponentModelCache()) {
            $cachemanager = PersistentCacheFacade::getInstance();
        }
        $dynamic_data_properties = null;
        if ($useCache) {
            $dynamic_data_properties = $cachemanager->getCacheByModelInstance(POP_CACHETYPE_DYNAMICDATAPROPERTIES);
        }
        if ($dynamic_data_properties === null) {
            global $pop_component_processordynamicdatadecorator_manager;
            $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
            $engineState = App::getEngineState();
            $entry_model_props = $engineState->model_props;
            $dynamic_data_properties = $pop_component_processordynamicdatadecorator_manager->getProcessorDecorator($componentprocessor_manager->getProcessor($entryComponent))->getDynamicDataFieldsDatasetmoduletree($entryComponent, $entry_model_props);

            if ($useCache) {
                $cachemanager->storeCacheByModelInstance(POP_CACHETYPE_DYNAMICDATAPROPERTIES, $dynamic_data_properties);
            }
        }

        // By now, in object $dynamic_data_properties, we have the configuration of what data-fields are dynamic,
        // on a block by block basis, and also including the subcomponents.
        // Then, simply iterate this information, and build the dynamic database by copying the corresponding data from the database
        foreach ($dynamic_data_properties[$entryComponentOutputName][ComponentModelModuleInfo::get('response-prop-subcomponents')] as $pagesection_settings_id => $pagesection_data_properties) {
            foreach ($pagesection_data_properties[ComponentModelModuleInfo::get('response-prop-subcomponents')] as $block_settings_id => $block_settings_id_data_properties) {
                // If the block has no typeResolver, it will be empty
                if ($block_typeResolver_data_properties = $block_settings_id_data_properties[POP_CONSTANT_DYNAMICDATAPROPERTIES]) {
                    $block_dataset = $dbobjectids[$entryComponentOutputName][$pagesection_settings_id][$block_settings_id];

                    // The data_properties has a unique key as the typeResolver
                    reset($block_typeResolver_data_properties);
                    $block_typeResolver_name = key($block_typeResolver_data_properties);
                    $block_typeResolver = $block_typeResolver_data_properties[$block_typeResolver_name]['resolver'];
                    $block_data_properties = $block_typeResolver_data_properties[$block_typeResolver_name]['properties'];

                    $this->addDynamicDatabaseEntries($data, $dynamicdatabases, $block_dataset, $block_typeResolver, $block_data_properties);
                }
            }
        }

        // Replace the DBs with the ones with dynamic data
        $data['dbData'] = $dynamicdatabases;
    }

    protected function addDynamicDatabaseEntries(&$data, &$dynamicdatabases, $dbobjectids, RelationalTypeResolverInterface $relationalTypeResolver, array $data_properties)
    {
        if ($data_properties['data-fields'] ?? null) {
            // Data to be copied can come from either the database or the userstatedatabase
            $databases = $data['dbData'];

            // Obtain the data from the database, copy it to the dynamic database
            $database_key = $relationalTypeResolver->getTypeOutputDBKey();

            // Allow plugins to split the object into several databases, not just "primary". Eg: "userstate", by PoP User Login
            // The hook below can modify the list of datafields to be added under "primary", and add those fields directly into $databaseitems under another dbname ("userstate")
            $engine = EngineFacade::getInstance();
            $data_fields = $engine->moveEntriesUnderDBName($data_properties['data-fields'], true, $relationalTypeResolver);

            foreach ($dbobjectids as $object_id) {
                // Copy to the dynamic database
                foreach ($databases as $dbname => $database) {
                    foreach ($data_fields[$dbname] as $data_field) {
                        if (isset($database[$database_key][$object_id][$data_field])) {
                            $dynamicdatabases[$dbname][$database_key][$object_id][$data_field] = $database[$database_key][$object_id][$data_field];
                        }
                    }
                }
            }

            // Call recursively to also copy the data from the subcomponents
            if ($subcomponents = $data_properties['subcomponents']) {
                foreach ($subcomponents as $subcomponent_data_field => $subcomponent_data_properties) {
                    // Check if the subcomponent data fields lives under database or userstatedatabase
                    $sourcedb = null;
                    foreach ($data_fields as $dbname => $db_data_fields) {
                        if (in_array($subcomponent_data_field, $db_data_fields)) {
                            $sourcedb = &$databases[$dbname];
                            break;
                        }
                    }
                    // From the $subcomponent_data_field we obtain the subcomponent dbobjectids IDs, fetching the corresponding values from the DB
                    $subcomponent_dataset = array();
                    $objectIDs = array_keys($sourcedb[$database_key]);

                    // If it is a union type data resolver, then we must add the converted type on each ID
                    $dataloadHelperService = DataloadHelperServiceFacade::getInstance();
                    if ($subcomponentTypeResolver = $dataloadHelperService->getTypeResolverFromSubcomponentDataField($relationalTypeResolver, $subcomponent_data_field)) {
                        $typeObjectIDs = $subcomponentTypeResolver->getQualifiedDBObjectIDOrIDs($objectIDs);
                        if (is_null($typeObjectIDs)) {
                            $isUnionType = false;
                            $typeObjectIDs = $objectIDs;
                        } else {
                            $isUnionType = true;
                        }

                        foreach ($typeObjectIDs as $object_id) {
                            if ($isUnionType) {
                                list(
                                    $database_key,
                                    $object_id
                                ) = UnionTypeHelpers::extractDBObjectTypeAndID($object_id);
                            }
                            // This value may be an array (eg: 'locations' => array(123, 343)) or a single value (eg: 'author' => 432)
                            // So convert to array, to deal with all cases
                            $subcomponent_resultitem_ids = $sourcedb[$database_key][$object_id][$subcomponent_data_field];
                            $subcomponent_resultitem_ids = is_array($subcomponent_resultitem_ids) ? $subcomponent_resultitem_ids : array($subcomponent_resultitem_ids);

                            // Add these IDs to the sucomponent's dbobjectids
                            $subcomponent_dataset = array_merge(
                                $subcomponent_dataset,
                                $subcomponent_resultitem_ids
                            );
                        }

                        $this->addDynamicDatabaseEntries($data, $dynamicdatabases, $subcomponent_dataset, $subcomponentTypeResolver, $subcomponent_data_properties);
                    }
                }
            }
        }
    }

    public function getScriptsWhere($where)
    {
        if (PoP_SSR_ServerUtils::includeScriptsAfterHtml()) {
            return 'footer';
        }

        return $where;
    }
}

/**
 * Initialization
 */
new PoP_SSR_EngineInitialization_Hooks();

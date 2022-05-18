<?php
use PoP\ComponentModel\Facades\ComponentFiltering\ComponentFilterManagerFacade;
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\ComponentProcessors\AbstractModuleDecoratorProcessor;

class PoP_DynamicDataModuleDecoratorProcessor extends AbstractModuleDecoratorProcessor
{

    //-------------------------------------------------
    // PROTECTED Functions
    //-------------------------------------------------

    protected function getModuledecoratorprocessorManager()
    {
        global $pop_componentVariation_processordynamicdatadecorator_manager;
        return $pop_componentVariation_processordynamicdatadecorator_manager;
    }

    //-------------------------------------------------
    // PUBLIC Functions
    //-------------------------------------------------

    public function needsDynamicData(array $componentVariation, array &$props)
    {
        $processor = $this->getDecoratedcomponentProcessor($componentVariation);
        $needsDynamicData = $processor->getProp($componentVariation, $props, 'needs-dynamic-data');
        if (!is_null($needsDynamicData)) {
            return $needsDynamicData;
        }

        return false;
    }

    public function getDynamicDataFieldsDatasetmoduletree(array $componentVariation, array &$props)
    {

        // The data-properties start on a dataloading module, and finish on the next dataloding module down the line
        // This way, we can collect all the data-fields that the module will need to load for its dbobjects
        return $this->executeOnSelfAndPropagateToComponentVariations('getDynamicDataFieldsDatasetmoduletreeFullsection', __FUNCTION__, $componentVariation, $props);
    }

    public function getDynamicDataFieldsDatasetmoduletreeFullsection(array $componentVariation, array &$props)
    {
        $ret = array();

        // Only if this module loads data
        $processor = $this->getDecoratedcomponentProcessor($componentVariation);
        if ($relationalTypeResolver = $processor->getRelationalTypeResolver($componentVariation)) {
            if ($properties = $this->getDynamicDatasetmoduletreeSectionFlattenedDataFields($componentVariation, $props)) {
                $ret[POP_CONSTANT_DYNAMICDATAPROPERTIES] = array(
                    $relationalTypeResolver->getTypeOutputDBKey() => [
                        'resolver' => $relationalTypeResolver,
                        'properties' => $properties,
                    ],
                );
            }
        }

        return $ret;
    }

    public function getDynamicDatasetmoduletreeSectionFlattenedDataFields(array $componentVariation, array &$props)
    {

        // If it needs dynamic data then that's it, simply return the data properties
        if ($this->needsDynamicData($componentVariation, $props)) {
            $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
            return $componentprocessor_manager->getProcessor($componentVariation)->getDatasetmoduletreeSectionFlattenedDataFields($componentVariation, $props);
        }

        // Otherwise, propagate to the modules and submodules
        $ret = array();

        // Propagate down to the components
        $this->flattenDatasetmoduletreeDataProperties(__FUNCTION__, $ret, $componentVariation, $props);

        // Propagate down to the subcomponent modules
        $this->flattenRelationaldbobjectDataProperties(__FUNCTION__, $ret, $componentVariation, $props);

        return $ret;
    }

    // function getMutableonrequestDynamicDataPropertiesDatasetmoduletree(array $componentVariation, array &$props) {

    //     // The data-properties start on a dataloading module, and finish on the next dataloding module down the line
    //     // This way, we can collect all the data-fields that the module will need to load for its dbobjects
    //     return $this->executeOnSelfAndPropagateToComponentVariations('getMutableonrequestDynamicDataPropertiesDatasetmoduletreeFullsection', __FUNCTION__, $componentVariation, $props);
    // }

    // function getMutableonrequestDynamicDataPropertiesDatasetmoduletreeFullsection(array $componentVariation, array &$props) {

    //     $ret = array();

    //     // Only if this module has a typeResolver
    //     $processor = $this->getDecoratedcomponentProcessor($componentVariation);
    //     if ($relationalTypeResolver = $processor->getRelationalTypeResolver($componentVariation)) {

    //         if ($properties = $this->getMutableonrequestDynamicDataPropertiesDatasetmoduletreeSection($componentVariation, $props)) {
    //             $ret[POP_CONSTANT_DYNAMICDATAPROPERTIES] = array(
    //                 $typeResolver->getTypeOutputDBKey() => $properties,
    //             );
    //         }
    //     }

    //     return $ret;
    // }

    // function getMutableonrequestDynamicDataPropertiesDatasetmoduletreeSection(array $componentVariation, array &$props) {

    //     // If it needs dynamic data then that's it, simply return the data properties
    //     if ($this->needsDynamicData($componentVariation, $props)) {

    //         $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
    //         return $componentprocessor_manager->getProcessor($componentVariation)->get_mutableonrequest_data_properties_datasetmoduletree_section($componentVariation, $props);
    //     }

    //     // Otherwise, propagate to the modules and submodules
    //     $ret = array();

    //     // Propagate down to the components
    //     $this->flattenDatasetmoduletreeDataProperties(__FUNCTION__, $ret, $componentVariation, $props);

    //     // Propagate down to the subcomponent modules
    //     $this->flattenRelationaldbobjectDataProperties(__FUNCTION__, $ret, $componentVariation, $props);

    //     return $ret;
    // }

    protected function flattenDatasetmoduletreeDataProperties($propagate_fn, &$ret, array $componentVariation, array &$props)
    {
        global $pop_componentVariation_processordynamicdatadecorator_manager;
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $moduleFullName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($componentVariation);

        // Exclude the subcomponent modules here
        $processor = $this->getDecoratedcomponentProcessor($componentVariation);
        $modulefilter_manager = ComponentFilterManagerFacade::getInstance();
        $modulefilter_manager->prepareForPropagation($componentVariation, $props);
        if ($subComponentVariations = $processor->getModulesToPropagateDataProperties($componentVariation)) {
            foreach ($subComponentVariations as $subComponentVariation) {
                $submodule_processor = $componentprocessor_manager->getProcessor($subComponentVariation);

                // Propagate only if the submodule start a new dataloading section. If it does, this is the end of the data line
                if (!$submodule_processor->startDataloadingSection($subComponentVariation, $props[$moduleFullName][\PoP\ComponentModel\Constants\Props::SUBMODULES])) {
                    if ($submodule_ret = $pop_componentVariation_processordynamicdatadecorator_manager->getProcessorDecorator($componentprocessor_manager->getProcessor($subComponentVariation))->$propagate_fn($subComponentVariation, $props[$moduleFullName][\PoP\ComponentModel\Constants\Props::SUBMODULES])) {
                        // array_merge_recursive => data-fields from different sidebar-components can be integrated all together
                        $ret = array_merge_recursive(
                            $ret,
                            $submodule_ret
                        );
                    }
                }
            }

            // Array Merge appends values when under numeric keys, so we gotta filter duplicates out
            if ($ret['data-fields'] ?? null) {
                $ret['data-fields'] = array_values(array_unique($ret['data-fields']));
            }
        }
        $modulefilter_manager->restoreFromPropagation($componentVariation, $props);
    }

    protected function flattenRelationaldbobjectDataProperties($propagate_fn, &$ret, array $componentVariation, array &$props)
    {
        global $pop_componentVariation_processordynamicdatadecorator_manager;
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $moduleFullName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($componentVariation);

        // If it has subcomponent modules, integrate them under 'subcomponents'
        $processor = $this->getDecoratedcomponentProcessor($componentVariation);
        $modulefilter_manager = ComponentFilterManagerFacade::getInstance();
        $modulefilter_manager->prepareForPropagation($componentVariation, $props);
        foreach ($processor->getRelationalSubmodules($componentVariation) as $relationalModuleField) {
            // @todo Pass the ModuleField directly, do not convert to string first
            $subcomponent_data_field = $relationalModuleField->asFieldOutputQueryString();
            $subcomponent_componentVariations_data_properties = array(
                'data-fields' => array(),
                'subcomponents' => array()
            );
            foreach ($relationalModuleField->getNestedComponentVariations() as $subcomponent_componentVariation) {
                if ($subcomponent_componentVariation_data_properties = $pop_componentVariation_processordynamicdatadecorator_manager->getProcessorDecorator($componentprocessor_manager->getProcessor($subcomponent_componentVariation))->$propagate_fn($subcomponent_componentVariation, $props[$moduleFullName][\PoP\ComponentModel\Constants\Props::SUBMODULES])) {
                    $subcomponent_componentVariations_data_properties = array_merge_recursive(
                        $subcomponent_componentVariations_data_properties,
                        $subcomponent_componentVariation_data_properties
                    );
                }
            }

            $ret['subcomponents'][$subcomponent_data_field] = $ret['subcomponents'][$subcomponent_data_field] ?? array();
            if ($subcomponent_componentVariations_data_properties['data-fields'] ?? null) {
                $subcomponent_componentVariations_data_properties['data-fields'] = array_unique($subcomponent_componentVariations_data_properties['data-fields']);

                $ret['subcomponents'][$subcomponent_data_field]['data-fields'] = $ret['subcomponents'][$subcomponent_data_field]['data-fields'] ?? array();
                $ret['subcomponents'][$subcomponent_data_field]['data-fields'] = array_unique(
                    array_merge(
                        $ret['subcomponents'][$subcomponent_data_field]['data-fields'],
                        $subcomponent_componentVariations_data_properties['data-fields']
                    )
                );
            }
            if ($subcomponent_componentVariations_data_properties['subcomponents'] ?? null) {
                $ret['subcomponents'][$subcomponent_data_field]['subcomponents'] = $ret['subcomponents'][$subcomponent_data_field]['subcomponents'] ?? array();
                $ret['subcomponents'][$subcomponent_data_field]['subcomponents'] = array_merge_recursive(
                    $ret['subcomponents'][$subcomponent_data_field]['subcomponents'],
                    $subcomponent_componentVariations_data_properties['subcomponents']
                );
            }
        }
        $modulefilter_manager->restoreFromPropagation($componentVariation, $props);
    }

    // protected function removeEmptyEntries(&$ret) {

    //     // If after the propagation, we have entries of 'subcomponents' empty, then remove them
    //     if ($ret['subcomponents'] ?? null) {

    //         // Iterate through all the data_field => dataloaders
    //         $subcomponent_data_fields = array_keys($ret['subcomponents']);
    //         foreach ($subcomponent_data_fields as $subcomponent_data_field) {

    //             $subcomponent_typeResolvers = array_keys($ret['subcomponents'][$subcomponent_data_field]);
    //             foreach ($subcomponent_typeResolvers as $subcomponent_typeResolver) {
    //                 if (empty($ret['subcomponents'][$subcomponent_data_field][$subcomponent_typeResolver])) {

    //                     unset($ret['subcomponents'][$subcomponent_data_field][$subcomponent_typeResolver]);
    //                 }
    //             }

    //             if (empty($ret['subcomponents'][$subcomponent_data_field])) {

    //                 unset($ret['subcomponents'][$subcomponent_data_field]);
    //             }
    //         }

    //         if (empty($ret['subcomponents'])) {

    //             unset($ret['subcomponents']);
    //         }
    //     }

    //     return $ret;
    // }
}

/**
 * Settings Initialization
 */
global $pop_componentVariation_processordynamicdatadecorator_manager;
$pop_componentVariation_processordynamicdatadecorator_manager->add(PoP_WebPlatformQueryDataComponentProcessorBase::class, PoP_DynamicDataModuleDecoratorProcessor::class);
// $pop_componentVariation_processordynamicdatadecorator_manager->add(PoP_Module_ProcessorBaseWrapper::class, PoP_DynamicDataModuleDecoratorProcessor::class);

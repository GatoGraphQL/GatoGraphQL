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
        global $pop_component_processordynamicdatadecorator_manager;
        return $pop_component_processordynamicdatadecorator_manager;
    }

    //-------------------------------------------------
    // PUBLIC Functions
    //-------------------------------------------------

    public function needsDynamicData(array $component, array &$props)
    {
        $processor = $this->getDecoratedcomponentProcessor($component);
        $needsDynamicData = $processor->getProp($component, $props, 'needs-dynamic-data');
        if (!is_null($needsDynamicData)) {
            return $needsDynamicData;
        }

        return false;
    }

    public function getDynamicDataFieldsDatasetmoduletree(array $component, array &$props)
    {

        // The data-properties start on a dataloading module, and finish on the next dataloding module down the line
        // This way, we can collect all the data-fields that the module will need to load for its dbobjects
        return $this->executeOnSelfAndPropagateToComponents('getDynamicDataFieldsDatasetmoduletreeFullsection', __FUNCTION__, $component, $props);
    }

    public function getDynamicDataFieldsDatasetmoduletreeFullsection(array $component, array &$props)
    {
        $ret = array();

        // Only if this module loads data
        $processor = $this->getDecoratedcomponentProcessor($component);
        if ($relationalTypeResolver = $processor->getRelationalTypeResolver($component)) {
            if ($properties = $this->getDynamicDatasetmoduletreeSectionFlattenedDataFields($component, $props)) {
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

    public function getDynamicDatasetmoduletreeSectionFlattenedDataFields(array $component, array &$props)
    {

        // If it needs dynamic data then that's it, simply return the data properties
        if ($this->needsDynamicData($component, $props)) {
            $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
            return $componentprocessor_manager->getProcessor($component)->getDatasetmoduletreeSectionFlattenedDataFields($component, $props);
        }

        // Otherwise, propagate to the modules and subcomponents
        $ret = array();

        // Propagate down to the components
        $this->flattenDatasetmoduletreeDataProperties(__FUNCTION__, $ret, $component, $props);

        // Propagate down to the subcomponent modules
        $this->flattenRelationaldbobjectDataProperties(__FUNCTION__, $ret, $component, $props);

        return $ret;
    }

    // function getMutableonrequestDynamicDataPropertiesDatasetmoduletree(array $component, array &$props) {

    //     // The data-properties start on a dataloading module, and finish on the next dataloding module down the line
    //     // This way, we can collect all the data-fields that the module will need to load for its dbobjects
    //     return $this->executeOnSelfAndPropagateToComponents('getMutableonrequestDynamicDataPropertiesDatasetmoduletreeFullsection', __FUNCTION__, $component, $props);
    // }

    // function getMutableonrequestDynamicDataPropertiesDatasetmoduletreeFullsection(array $component, array &$props) {

    //     $ret = array();

    //     // Only if this module has a typeResolver
    //     $processor = $this->getDecoratedcomponentProcessor($component);
    //     if ($relationalTypeResolver = $processor->getRelationalTypeResolver($component)) {

    //         if ($properties = $this->getMutableonrequestDynamicDataPropertiesDatasetmoduletreeSection($component, $props)) {
    //             $ret[POP_CONSTANT_DYNAMICDATAPROPERTIES] = array(
    //                 $typeResolver->getTypeOutputDBKey() => $properties,
    //             );
    //         }
    //     }

    //     return $ret;
    // }

    // function getMutableonrequestDynamicDataPropertiesDatasetmoduletreeSection(array $component, array &$props) {

    //     // If it needs dynamic data then that's it, simply return the data properties
    //     if ($this->needsDynamicData($component, $props)) {

    //         $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
    //         return $componentprocessor_manager->getProcessor($component)->get_mutableonrequest_data_properties_datasetmoduletree_section($component, $props);
    //     }

    //     // Otherwise, propagate to the modules and subcomponents
    //     $ret = array();

    //     // Propagate down to the components
    //     $this->flattenDatasetmoduletreeDataProperties(__FUNCTION__, $ret, $component, $props);

    //     // Propagate down to the subcomponent modules
    //     $this->flattenRelationaldbobjectDataProperties(__FUNCTION__, $ret, $component, $props);

    //     return $ret;
    // }

    protected function flattenDatasetmoduletreeDataProperties($propagate_fn, &$ret, array $component, array &$props)
    {
        global $pop_component_processordynamicdatadecorator_manager;
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $componentFullName = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentFullName($component);

        // Exclude the subcomponent modules here
        $processor = $this->getDecoratedcomponentProcessor($component);
        $modulefilter_manager = ComponentFilterManagerFacade::getInstance();
        $modulefilter_manager->prepareForPropagation($component, $props);
        if ($subComponents = $processor->getModulesToPropagateDataProperties($component)) {
            foreach ($subComponents as $subComponent) {
                $subcomponent_processor = $componentprocessor_manager->getProcessor($subComponent);

                // Propagate only if the subcomponent start a new dataloading section. If it does, this is the end of the data line
                if (!$subcomponent_processor->startDataloadingSection($subComponent, $props[$componentFullName][\PoP\ComponentModel\Constants\Props::SUBCOMPONENTS])) {
                    if ($subcomponent_ret = $pop_component_processordynamicdatadecorator_manager->getProcessorDecorator($componentprocessor_manager->getProcessor($subComponent))->$propagate_fn($subComponent, $props[$componentFullName][\PoP\ComponentModel\Constants\Props::SUBCOMPONENTS])) {
                        // array_merge_recursive => data-fields from different sidebar-components can be integrated all together
                        $ret = array_merge_recursive(
                            $ret,
                            $subcomponent_ret
                        );
                    }
                }
            }

            // Array Merge appends values when under numeric keys, so we gotta filter duplicates out
            if ($ret['data-fields'] ?? null) {
                $ret['data-fields'] = array_values(array_unique($ret['data-fields']));
            }
        }
        $modulefilter_manager->restoreFromPropagation($component, $props);
    }

    protected function flattenRelationaldbobjectDataProperties($propagate_fn, &$ret, array $component, array &$props)
    {
        global $pop_component_processordynamicdatadecorator_manager;
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $componentFullName = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentFullName($component);

        // If it has subcomponent modules, integrate them under 'subcomponents'
        $processor = $this->getDecoratedcomponentProcessor($component);
        $modulefilter_manager = ComponentFilterManagerFacade::getInstance();
        $modulefilter_manager->prepareForPropagation($component, $props);
        foreach ($processor->getRelationalSubcomponents($component) as $relationalModuleField) {
            // @todo Pass the ModuleField directly, do not convert to string first
            $subcomponent_data_field = $relationalModuleField->asFieldOutputQueryString();
            $subcomponent_components_data_properties = array(
                'data-fields' => array(),
                'subcomponents' => array()
            );
            foreach ($relationalModuleField->getNestedComponents() as $subcomponent_component) {
                if ($subcomponent_component_data_properties = $pop_component_processordynamicdatadecorator_manager->getProcessorDecorator($componentprocessor_manager->getProcessor($subcomponent_component))->$propagate_fn($subcomponent_component, $props[$componentFullName][\PoP\ComponentModel\Constants\Props::SUBCOMPONENTS])) {
                    $subcomponent_components_data_properties = array_merge_recursive(
                        $subcomponent_components_data_properties,
                        $subcomponent_component_data_properties
                    );
                }
            }

            $ret['subcomponents'][$subcomponent_data_field] = $ret['subcomponents'][$subcomponent_data_field] ?? array();
            if ($subcomponent_components_data_properties['data-fields'] ?? null) {
                $subcomponent_components_data_properties['data-fields'] = array_unique($subcomponent_components_data_properties['data-fields']);

                $ret['subcomponents'][$subcomponent_data_field]['data-fields'] = $ret['subcomponents'][$subcomponent_data_field]['data-fields'] ?? array();
                $ret['subcomponents'][$subcomponent_data_field]['data-fields'] = array_unique(
                    array_merge(
                        $ret['subcomponents'][$subcomponent_data_field]['data-fields'],
                        $subcomponent_components_data_properties['data-fields']
                    )
                );
            }
            if ($subcomponent_components_data_properties['subcomponents'] ?? null) {
                $ret['subcomponents'][$subcomponent_data_field]['subcomponents'] = $ret['subcomponents'][$subcomponent_data_field]['subcomponents'] ?? array();
                $ret['subcomponents'][$subcomponent_data_field]['subcomponents'] = array_merge_recursive(
                    $ret['subcomponents'][$subcomponent_data_field]['subcomponents'],
                    $subcomponent_components_data_properties['subcomponents']
                );
            }
        }
        $modulefilter_manager->restoreFromPropagation($component, $props);
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
global $pop_component_processordynamicdatadecorator_manager;
$pop_component_processordynamicdatadecorator_manager->add(PoP_WebPlatformQueryDataComponentProcessorBase::class, PoP_DynamicDataModuleDecoratorProcessor::class);
// $pop_component_processordynamicdatadecorator_manager->add(PoP_Module_ProcessorBaseWrapper::class, PoP_DynamicDataModuleDecoratorProcessor::class);

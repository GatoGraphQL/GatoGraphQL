<?php
use PoP\ComponentModel\ComponentProcessors\AbstractModuleDecoratorProcessor;
use PoP\ComponentModel\Constants\DataProperties;
use PoP\ComponentModel\Facades\ComponentFiltering\ComponentFilterManagerFacade;
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

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

    public function needsDynamicData(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $processor = $this->getDecoratedcomponentProcessor($component);
        $needsDynamicData = $processor->getProp($component, $props, 'needs-dynamic-data');
        if (!is_null($needsDynamicData)) {
            return $needsDynamicData;
        }

        return false;
    }

    public function getDynamicDataFieldsDatasetcomponentTree(\PoP\ComponentModel\Component\Component $component, array &$props)
    {

        // The data-properties start on a dataloading module, and finish on the next dataloding module down the line
        // This way, we can collect all the data-fields that the module will need to load for its dbobjects
        return $this->executeOnSelfAndPropagateToComponents('getDynamicDataFieldsDatasetcomponentTreeFullsection', __FUNCTION__, $component, $props);
    }

    public function getDynamicDataFieldsDatasetcomponentTreeFullsection(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = array();

        // Only if this module loads data
        $processor = $this->getDecoratedcomponentProcessor($component);
        if ($relationalTypeResolver = $processor->getRelationalTypeResolver($component)) {
            if ($properties = $this->getDynamicDatasetcomponentTreeSectionFlattenedDataFields($component, $props)) {
                $ret[POP_CONSTANT_DYNAMICDATAPROPERTIES] = array(
                    $relationalTypeResolver->getTypeOutputKey() => [
                        'resolver' => $relationalTypeResolver,
                        'properties' => $properties,
                    ],
                );
            }
        }

        return $ret;
    }

    public function getDynamicDatasetcomponentTreeSectionFlattenedDataFields(\PoP\ComponentModel\Component\Component $component, array &$props)
    {

        // If it needs dynamic data then that's it, simply return the data properties
        if ($this->needsDynamicData($component, $props)) {
            $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
            return $componentprocessor_manager->getComponentProcessor($component)->getDatasetComponentTreeSectionFlattenedDataProperties($component, $props);
        }

        // Otherwise, propagate to the modules and subcomponents
        $ret = array();

        // Propagate down to the components
        $this->flattenDatasetcomponentTreeDataProperties(__FUNCTION__, $ret, $component, $props);

        // Propagate down to the subcomponent modules
        $this->flattenRelationaldbobjectDataProperties(__FUNCTION__, $ret, $component, $props);

        return $ret;
    }

    // function getMutableonrequestDynamicDataPropertiesDatasetcomponentTree(\PoP\ComponentModel\Component\Component $component, array &$props) {

    //     // The data-properties start on a dataloading module, and finish on the next dataloding module down the line
    //     // This way, we can collect all the data-fields that the module will need to load for its dbobjects
    //     return $this->executeOnSelfAndPropagateToComponents('getMutableonrequestDynamicDataPropertiesDatasetcomponentTreeFullsection', __FUNCTION__, $component, $props);
    // }

    // function getMutableonrequestDynamicDataPropertiesDatasetcomponentTreeFullsection(\PoP\ComponentModel\Component\Component $component, array &$props) {

    //     $ret = array();

    //     // Only if this module has a typeResolver
    //     $processor = $this->getDecoratedcomponentProcessor($component);
    //     if ($relationalTypeResolver = $processor->getRelationalTypeResolver($component)) {

    //         if ($properties = $this->getMutableonrequestDynamicDataPropertiesDatasetcomponentTreeSection($component, $props)) {
    //             $ret[POP_CONSTANT_DYNAMICDATAPROPERTIES] = array(
    //                 $typeResolver->getTypeOutputKey() => $properties,
    //             );
    //         }
    //     }

    //     return $ret;
    // }

    // function getMutableonrequestDynamicDataPropertiesDatasetcomponentTreeSection(\PoP\ComponentModel\Component\Component $component, array &$props) {

    //     // If it needs dynamic data then that's it, simply return the data properties
    //     if ($this->needsDynamicData($component, $props)) {

    //         $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
    //         return $componentprocessor_manager->getComponentProcessor($component)->get_mutableonrequest_data_properties_datasetcomponentTree_section($component, $props);
    //     }

    //     // Otherwise, propagate to the modules and subcomponents
    //     $ret = array();

    //     // Propagate down to the components
    //     $this->flattenDatasetcomponentTreeDataProperties(__FUNCTION__, $ret, $component, $props);

    //     // Propagate down to the subcomponent modules
    //     $this->flattenRelationaldbobjectDataProperties(__FUNCTION__, $ret, $component, $props);

    //     return $ret;
    // }

    protected function flattenDatasetcomponentTreeDataProperties(string $propagate_fn, array &$ret, \PoP\ComponentModel\Component\Component $component, array &$props)
    {
        global $pop_component_processordynamicdatadecorator_manager;
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $componentFullName = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentFullName($component);

        // Exclude the subcomponent modules here
        $processor = $this->getDecoratedcomponentProcessor($component);
        $modulefilter_manager = ComponentFilterManagerFacade::getInstance();
        $modulefilter_manager->prepareForPropagation($component, $props);
        if ($subcomponents = $processor->getComponentsToPropagateDataProperties($component)) {
            foreach ($subcomponents as $subcomponent) {
                $subcomponent_processor = $componentprocessor_manager->getComponentProcessor($subcomponent);

                // Propagate only if the subcomponent start a new dataloading section. If it does, this is the end of the data line
                if (!$subcomponent_processor->startDataloadingSection($subcomponent, $props[$componentFullName][\PoP\ComponentModel\Constants\Props::SUBCOMPONENTS])) {
                    if ($subcomponent_ret = $pop_component_processordynamicdatadecorator_manager->getProcessorDecorator($componentprocessor_manager->getComponentProcessor($subcomponent))->$propagate_fn($subcomponent, $props[$componentFullName][\PoP\ComponentModel\Constants\Props::SUBCOMPONENTS])) {
                        // array_merge_recursive => data-fields from different sidebar-components can be integrated all together
                        $ret = array_merge_recursive(
                            $ret,
                            $subcomponent_ret
                        );
                    }
                }
            }

            // Array Merge appends values when under numeric keys, so we gotta filter duplicates out
            if ($ret[DataProperties::DIRECT_COMPONENT_FIELD_NODES] ?? null) {
                $ret[DataProperties::DIRECT_COMPONENT_FIELD_NODES] = array_values(array_unique($ret[DataProperties::DIRECT_COMPONENT_FIELD_NODES]));
            }
        }
        $modulefilter_manager->restoreFromPropagation($component, $props);
    }

    protected function flattenRelationaldbobjectDataProperties(string $propagate_fn, array &$ret, \PoP\ComponentModel\Component\Component $component, array &$props)
    {
        global $pop_component_processordynamicdatadecorator_manager;
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $componentFullName = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentFullName($component);

        // If it has subcomponent modules, integrate them under 'subcomponents'
        $processor = $this->getDecoratedcomponentProcessor($component);
        $modulefilter_manager = ComponentFilterManagerFacade::getInstance();
        $modulefilter_manager->prepareForPropagation($component, $props);
        foreach ($processor->getRelationalComponentFieldNodes($component) as $relationalComponentFieldNode) {
            $subcomponent_data_field = $relationalComponentFieldNode->getField()->asFieldOutputQueryString();
            $subcomponent_components_data_properties = array(
                DataProperties::DIRECT_COMPONENT_FIELD_NODES => array(),
                // @todo Migrate 'subcomponents' from array to SplObjectStorage
                DataProperties::SUBCOMPONENTS => array(),
            );
            foreach ($relationalComponentFieldNode->getNestedComponents() as $subcomponent_component) {
                if ($subcomponent_component_data_properties = $pop_component_processordynamicdatadecorator_manager->getProcessorDecorator($componentprocessor_manager->getComponentProcessor($subcomponent_component))->$propagate_fn($subcomponent_component, $props[$componentFullName][\PoP\ComponentModel\Constants\Props::SUBCOMPONENTS])) {
                    $subcomponent_components_data_properties = array_merge_recursive(
                        $subcomponent_components_data_properties,
                        $subcomponent_component_data_properties
                    );
                }
            }

            // @todo Must assign the SplObjectStorage to a variable, operate there, and then re-assign at the end
            // @see https://stackoverflow.com/questions/20053269/indirect-modification-of-overloaded-element-of-splfixedarray-has-no-effect
            $ret[DataProperties::SUBCOMPONENTS][$subcomponent_data_field] = $ret[DataProperties::SUBCOMPONENTS][$subcomponent_data_field] ?? array();
            if ($subcomponent_components_data_properties[DataProperties::DIRECT_COMPONENT_FIELD_NODES] ?? null) {
                $subcomponent_components_data_properties[DataProperties::DIRECT_COMPONENT_FIELD_NODES] = array_unique($subcomponent_components_data_properties[DataProperties::DIRECT_COMPONENT_FIELD_NODES]);

                $ret[DataProperties::SUBCOMPONENTS][$subcomponent_data_field][DataProperties::DIRECT_COMPONENT_FIELD_NODES] = $ret[DataProperties::SUBCOMPONENTS][$subcomponent_data_field][DataProperties::DIRECT_COMPONENT_FIELD_NODES] ?? array();
                $ret[DataProperties::SUBCOMPONENTS][$subcomponent_data_field][DataProperties::DIRECT_COMPONENT_FIELD_NODES] = array_unique(
                    array_merge(
                        $ret[DataProperties::SUBCOMPONENTS][$subcomponent_data_field][DataProperties::DIRECT_COMPONENT_FIELD_NODES],
                        $subcomponent_components_data_properties[DataProperties::DIRECT_COMPONENT_FIELD_NODES]
                    )
                );
            }
            if ($subcomponent_components_data_properties[DataProperties::SUBCOMPONENTS] ?? null) {
                $ret[DataProperties::SUBCOMPONENTS][$subcomponent_data_field][DataProperties::SUBCOMPONENTS] = $ret[DataProperties::SUBCOMPONENTS][$subcomponent_data_field][DataProperties::SUBCOMPONENTS] ?? array();
                $ret[DataProperties::SUBCOMPONENTS][$subcomponent_data_field][DataProperties::SUBCOMPONENTS] = array_merge_recursive(
                    $ret[DataProperties::SUBCOMPONENTS][$subcomponent_data_field][DataProperties::SUBCOMPONENTS],
                    $subcomponent_components_data_properties[DataProperties::SUBCOMPONENTS]
                );
            }
        }
        $modulefilter_manager->restoreFromPropagation($component, $props);
    }

    // protected function removeEmptyEntries(&$ret) {

    //     // If after the propagation, we have entries of 'subcomponents' empty, then remove them
    //     if ($ret[DataProperties::SUBCOMPONENTS] ?? null) {

    //         // Iterate through all the data_field => dataloaders
    //         $subcomponent_direct_fields = array_keys($ret[DataProperties::SUBCOMPONENTS]);
    //         foreach ($subcomponent_direct_fields as $subcomponent_data_field) {

    //             $subcomponent_typeResolvers = array_keys($ret[DataProperties::SUBCOMPONENTS][$subcomponent_data_field]);
    //             foreach ($subcomponent_typeResolvers as $subcomponent_typeResolver) {
    //                 if (empty($ret[DataProperties::SUBCOMPONENTS][$subcomponent_data_field][$subcomponent_typeResolver])) {

    //                     unset($ret[DataProperties::SUBCOMPONENTS][$subcomponent_data_field][$subcomponent_typeResolver]);
    //                 }
    //             }

    //             if (empty($ret[DataProperties::SUBCOMPONENTS][$subcomponent_data_field])) {

    //                 unset($ret[DataProperties::SUBCOMPONENTS][$subcomponent_data_field]);
    //             }
    //         }

    //         if (empty($ret[DataProperties::SUBCOMPONENTS])) {

    //             unset($ret[DataProperties::SUBCOMPONENTS]);
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

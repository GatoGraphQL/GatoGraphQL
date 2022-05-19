<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ConditionalLeafModuleField;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ConditionalRelationalModuleField;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalModuleField;
use PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Root\Feedback\FeedbackItemResolution;

interface ComponentProcessorInterface
{
    public function getComponentsToProcess(): array;
    public function getSubcomponents(array $component): array;
    public function getAllSubcomponents(array $component): array;
    public function executeInitPropsModuletree(callable $eval_self_fn, callable $get_props_for_descendant_components_fn, callable $get_props_for_descendant_datasetcomponents_fn, string $propagate_fn, array $component, array &$props, $wildcard_props_to_propagate, $targetted_props_to_propagate): void;
    public function initModelPropsModuletree(array $component, array &$props, array $wildcard_props_to_propagate, array $targetted_props_to_propagate): void;
    public function getModelPropsForDescendantComponents(array $component, array &$props): array;
    public function getModelPropsForDescendantDatasetmodules(array $component, array &$props): array;
    public function initModelProps(array $component, array &$props): void;
    public function initRequestPropsModuletree(array $component, array &$props, array $wildcard_props_to_propagate, array $targetted_props_to_propagate): void;
    public function getRequestPropsForDescendantComponents(array $component, array &$props): array;
    public function getRequestPropsForDescendantDatasetmodules(array $component, array &$props): array;
    public function initRequestProps(array $component, array &$props): void;
    public function setProp(array $component_or_componentPath, array &$props, string $field, $value, array $starting_from_componentPath = array()): void;
    public function appendGroupProp(string $group, array $component_or_componentPath, array &$props, string $field, $value, array $starting_from_componentPath = array()): void;
    public function appendProp(array $component_or_componentPath, array &$props, string $field, $value, array $starting_from_componentPath = array()): void;
    public function mergeGroupProp(string $group, array $component_or_componentPath, array &$props, string $field, $value, array $starting_from_componentPath = array()): void;
    public function mergeProp(array $component_or_componentPath, array &$props, string $field, $value, array $starting_from_componentPath = array()): void;
    public function getGroupProp(string $group, array $component, array &$props, string $field, array $starting_from_componentPath = array()): mixed;
    public function getProp(array $component, array &$props, string $field, array $starting_from_componentPath = array()): mixed;
    public function mergeGroupIterateKeyProp(string $group, array $component_or_componentPath, array &$props, string $field, $value, array $starting_from_componentPath = array()): void;
    public function mergeIterateKeyProp(array $component_or_componentPath, array &$props, string $field, $value, array $starting_from_componentPath = array()): void;
    public function pushProp(string $group, array $component_or_componentPath, array &$props, string $field, $value, array $starting_from_componentPath = array()): void;
    public function getDatabaseKeys(array $component, array &$props): array;
    public function getImmutableSettingsDatasetmoduletree(array $component, array &$props): array;
    public function getImmutableDatasetsettings(array $component, array &$props): array;
    public function getDatasetDatabaseKeys(array $component, array &$props): array;
    public function getDatasource(array $component, array &$props): string;
    public function getObjectIDOrIDs(array $component, array &$props, &$data_properties): string | int | array | null;
    public function getRelationalTypeResolver(array $component): ?RelationalTypeResolverInterface;
    public function getComponentMutationResolverBridge(array $component): ?ComponentMutationResolverBridgeInterface;
    public function prepareDataPropertiesAfterMutationExecution(array $component, array &$props, array &$data_properties): void;
    /**
     * @return LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array;
    /**
     * @return RelationalModuleField[]
     */
    public function getRelationalSubcomponents(array $component): array;
    /**
     * @return ConditionalLeafModuleField[]
     */
    public function getConditionalOnDataFieldSubcomponents(array $component): array;
    /**
     * @return ConditionalRelationalModuleField[]
     */
    public function getConditionalOnDataFieldRelationalSubcomponents(array $component): array;
    public function getImmutableDataPropertiesDatasetmoduletree(array $component, array &$props): array;
    public function getImmutableDataPropertiesDatasetmoduletreeFullsection(array $component, array &$props): array;
    public function getDatasetmoduletreeSectionFlattenedDataFields(array $component, array &$props): array;
    public function getDatasetmoduletreeSectionFlattenedComponents(array $component): array;
    public function getImmutableHeaddatasetmoduleDataProperties(array $component, array &$props): array;
    public function getMutableonmodelDataPropertiesDatasetmoduletree(array $component, array &$props): array;
    public function getMutableonmodelDataPropertiesDatasetmoduletreeFullsection(array $component, array &$props): array;
    public function getMutableonmodelHeaddatasetmoduleDataProperties(array $component, array &$props): array;
    public function getMutableonrequestDataPropertiesDatasetmoduletree(array $component, array &$props): array;
    public function getMutableonrequestDataPropertiesDatasetmoduletreeFullsection(array $component, array &$props): array;
    public function getMutableonrequestHeaddatasetmoduleDataProperties(array $component, array &$props): array;
    public function getDataFeedbackDatasetmoduletree(array $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array;
    public function getDataFeedbackModuletree(array $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array;
    public function getDataFeedback(array $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array;
    public function getDataFeedbackInterreferencedComponentPath(array $component, array &$props): ?array;
    public function getBackgroundurlsMergeddatasetmoduletree(array $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $objectIDs): array;
    public function getBackgroundurls(array $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $objectIDs): array;
    public function getDatasetmeta(array $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbObjectIDOrIDs): array;
    public function getDataAccessCheckpoints(array $component, array &$props): array;
    public function getActionExecutionCheckpoints(array $component, array &$props): array;
    public function shouldExecuteMutation(array $component, array &$props): bool;
    public function getModulesToPropagateDataProperties(array $component): array;
    public function getModelSupplementaryDBObjectDataModuletree(array $component, array &$props): array;
    public function getModelSupplementaryDBObjectData(array $component, array &$props): array;
    public function getMutableonrequestSupplementaryDBObjectDataModuletree(array $component, array &$props): array;
    public function getMutableonrequestSupplementaryDbobjectdata(array $component, array &$props): array;
    public function moduleLoadsData(array $component): bool;
    public function startDataloadingSection(array $component): bool;
    public function addToDatasetDatabaseKeys(array $component, array &$props, array $path, array &$ret): void;
    public function addDatasetmoduletreeSectionFlattenedComponents(&$ret, array $component): void;
}

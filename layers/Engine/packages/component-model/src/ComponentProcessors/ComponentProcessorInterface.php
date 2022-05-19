<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ConditionalLeafComponentField;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ConditionalRelationalComponentField;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentField;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalComponentField;
use PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Root\Feedback\FeedbackItemResolution;

interface ComponentProcessorInterface
{
    public function getComponentsToProcess(): array;
    public function getSubcomponents(array $component): array;
    public function getAllSubcomponents(array $component): array;
    public function executeInitPropsComponentTree(callable $eval_self_fn, callable $get_props_for_descendant_components_fn, callable $get_props_for_descendant_datasetcomponents_fn, string $propagate_fn, array $component, array &$props, $wildcard_props_to_propagate, $targetted_props_to_propagate): void;
    public function initModelPropsComponentTree(array $component, array &$props, array $wildcard_props_to_propagate, array $targetted_props_to_propagate): void;
    public function getModelPropsForDescendantComponents(array $component, array &$props): array;
    public function getModelPropsForDescendantDatasetComponents(array $component, array &$props): array;
    public function initModelProps(array $component, array &$props): void;
    public function initRequestPropsComponentTree(array $component, array &$props, array $wildcard_props_to_propagate, array $targetted_props_to_propagate): void;
    public function getRequestPropsForDescendantComponents(array $component, array &$props): array;
    public function getRequestPropsForDescendantDatasetComponents(array $component, array &$props): array;
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
    public function getImmutableSettingsDatasetcomponentTree(array $component, array &$props): array;
    public function getImmutableDatasetsettings(array $component, array &$props): array;
    public function getDatasetDatabaseKeys(array $component, array &$props): array;
    public function getDatasource(array $component, array &$props): string;
    public function getObjectIDOrIDs(array $component, array &$props, &$data_properties): string | int | array | null;
    public function getRelationalTypeResolver(array $component): ?RelationalTypeResolverInterface;
    public function getComponentMutationResolverBridge(array $component): ?ComponentMutationResolverBridgeInterface;
    public function prepareDataPropertiesAfterMutationExecution(array $component, array &$props, array &$data_properties): void;
    /**
     * @return LeafComponentField[]
     */
    public function getLeafComponentFields(array $component, array &$props): array;
    /**
     * @return RelationalComponentField[]
     */
    public function getRelationalComponentFields(array $component): array;
    /**
     * @return ConditionalLeafComponentField[]
     */
    public function getConditionalLeafComponentFields(array $component): array;
    /**
     * @return ConditionalRelationalComponentField[]
     */
    public function getConditionalRelationalComponentFields(array $component): array;
    public function getImmutableDataPropertiesDatasetcomponentTree(array $component, array &$props): array;
    public function getImmutableDataPropertiesDatasetcomponentTreeFullsection(array $component, array &$props): array;
    public function getDatasetcomponentTreeSectionFlattenedDataFields(array $component, array &$props): array;
    public function getDatasetcomponentTreeSectionFlattenedComponents(array $component): array;
    public function getImmutableHeaddatasetcomponentDataProperties(array $component, array &$props): array;
    public function getMutableonmodelDataPropertiesDatasetcomponentTree(array $component, array &$props): array;
    public function getMutableonmodelDataPropertiesDatasetcomponentTreeFullsection(array $component, array &$props): array;
    public function getMutableonmodelHeaddatasetcomponentDataProperties(array $component, array &$props): array;
    public function getMutableonrequestDataPropertiesDatasetcomponentTree(array $component, array &$props): array;
    public function getMutableonrequestDataPropertiesDatasetcomponentTreeFullsection(array $component, array &$props): array;
    public function getMutableonrequestHeaddatasetcomponentDataProperties(array $component, array &$props): array;
    public function getDataFeedbackDatasetcomponentTree(array $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array;
    public function getDataFeedbackComponentTree(array $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array;
    public function getDataFeedback(array $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array;
    public function getDataFeedbackInterreferencedComponentPath(array $component, array &$props): ?array;
    public function getBackgroundurlsMergeddatasetcomponentTree(array $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $objectIDs): array;
    public function getBackgroundurls(array $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $objectIDs): array;
    public function getDatasetmeta(array $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbObjectIDOrIDs): array;
    public function getDataAccessCheckpoints(array $component, array &$props): array;
    public function getActionExecutionCheckpoints(array $component, array &$props): array;
    public function shouldExecuteMutation(array $component, array &$props): bool;
    public function getComponentsToPropagateDataProperties(array $component): array;
    public function getModelSupplementaryDBObjectDataComponentTree(array $component, array &$props): array;
    public function getModelSupplementaryDBObjectData(array $component, array &$props): array;
    public function getMutableonrequestSupplementaryDBObjectDataComponentTree(array $component, array &$props): array;
    public function getMutableonrequestSupplementaryDbobjectdata(array $component, array &$props): array;
    public function doesComponentLoadData(array $component): bool;
    public function startDataloadingSection(array $component): bool;
    public function addToDatasetDatabaseKeys(array $component, array &$props, array $path, array &$ret): void;
    public function addDatasetcomponentTreeSectionFlattenedComponents(&$ret, array $component): void;
}

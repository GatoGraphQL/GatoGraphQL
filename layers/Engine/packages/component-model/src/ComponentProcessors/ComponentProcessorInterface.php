<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Checkpoints\CheckpointInterface;
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
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array;
    public function getAllSubcomponents(\PoP\ComponentModel\Component\Component $component): array;
    public function executeInitPropsComponentTree(callable $eval_self_fn, callable $get_props_for_descendant_components_fn, callable $get_props_for_descendant_datasetcomponents_fn, string $propagate_fn, \PoP\ComponentModel\Component\Component $component, array &$props, $wildcard_props_to_propagate, $targetted_props_to_propagate): void;
    public function initModelPropsComponentTree(\PoP\ComponentModel\Component\Component $component, array &$props, array $wildcard_props_to_propagate, array $targetted_props_to_propagate): void;
    public function getModelPropsForDescendantComponents(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function getModelPropsForDescendantDatasetComponents(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void;
    public function initRequestPropsComponentTree(\PoP\ComponentModel\Component\Component $component, array &$props, array $wildcard_props_to_propagate, array $targetted_props_to_propagate): void;
    public function getRequestPropsForDescendantComponents(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function getRequestPropsForDescendantDatasetComponents(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function initRequestProps(\PoP\ComponentModel\Component\Component $component, array &$props): void;
    public function setProp(array $component_or_componentPath, array &$props, string $field, $value, array $starting_from_componentPath = array()): void;
    public function appendGroupProp(string $group, array $component_or_componentPath, array &$props, string $field, $value, array $starting_from_componentPath = array()): void;
    public function appendProp(array $component_or_componentPath, array &$props, string $field, $value, array $starting_from_componentPath = array()): void;
    public function mergeGroupProp(string $group, array $component_or_componentPath, array &$props, string $field, $value, array $starting_from_componentPath = array()): void;
    public function mergeProp(array $component_or_componentPath, array &$props, string $field, $value, array $starting_from_componentPath = array()): void;
    public function getGroupProp(string $group, \PoP\ComponentModel\Component\Component $component, array &$props, string $field, array $starting_from_componentPath = array()): mixed;
    public function getProp(\PoP\ComponentModel\Component\Component $component, array &$props, string $field, array $starting_from_componentPath = array()): mixed;
    public function mergeGroupIterateKeyProp(string $group, array $component_or_componentPath, array &$props, string $field, $value, array $starting_from_componentPath = array()): void;
    public function mergeIterateKeyProp(array $component_or_componentPath, array &$props, string $field, $value, array $starting_from_componentPath = array()): void;
    public function pushProp(string $group, array $component_or_componentPath, array &$props, string $field, $value, array $starting_from_componentPath = array()): void;
    public function getDatabaseKeys(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function getImmutableSettingsDatasetcomponentTree(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function getImmutableDatasetsettings(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function getDatasetDatabaseKeys(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function getDatasource(\PoP\ComponentModel\Component\Component $component, array &$props): string;
    public function getObjectIDOrIDs(\PoP\ComponentModel\Component\Component $component, array &$props, &$data_properties): string | int | array | null;
    public function getRelationalTypeResolver(\PoP\ComponentModel\Component\Component $component): ?RelationalTypeResolverInterface;
    public function getComponentMutationResolverBridge(\PoP\ComponentModel\Component\Component $component): ?ComponentMutationResolverBridgeInterface;
    public function prepareDataPropertiesAfterMutationExecution(\PoP\ComponentModel\Component\Component $component, array &$props, array &$data_properties): void;
    /**
     * @return LeafComponentField[]
     */
    public function getLeafComponentFields(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    /**
     * @return RelationalComponentField[]
     */
    public function getRelationalComponentFields(\PoP\ComponentModel\Component\Component $component): array;
    /**
     * @return ConditionalLeafComponentField[]
     */
    public function getConditionalLeafComponentFields(\PoP\ComponentModel\Component\Component $component): array;
    /**
     * @return ConditionalRelationalComponentField[]
     */
    public function getConditionalRelationalComponentFields(\PoP\ComponentModel\Component\Component $component): array;
    public function getImmutableDataPropertiesDatasetcomponentTree(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function getImmutableDataPropertiesDatasetcomponentTreeFullsection(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function getDatasetcomponentTreeSectionFlattenedDataFields(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function getDatasetcomponentTreeSectionFlattenedComponents(\PoP\ComponentModel\Component\Component $component): array;
    public function getImmutableHeaddatasetcomponentDataProperties(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function getMutableonmodelDataPropertiesDatasetcomponentTree(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function getMutableonmodelDataPropertiesDatasetcomponentTreeFullsection(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function getMutableonmodelHeaddatasetcomponentDataProperties(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function getMutableonrequestDataPropertiesDatasetcomponentTree(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function getMutableonrequestDataPropertiesDatasetcomponentTreeFullsection(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function getMutableonrequestHeaddatasetcomponentDataProperties(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function getDataFeedbackDatasetcomponentTree(\PoP\ComponentModel\Component\Component $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array;
    public function getDataFeedbackComponentTree(\PoP\ComponentModel\Component\Component $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array;
    public function getDataFeedback(\PoP\ComponentModel\Component\Component $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array;
    public function getDataFeedbackInterreferencedComponentPath(\PoP\ComponentModel\Component\Component $component, array &$props): ?array;
    public function getBackgroundurlsMergeddatasetcomponentTree(\PoP\ComponentModel\Component\Component $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $objectIDs): array;
    public function getBackgroundurls(\PoP\ComponentModel\Component\Component $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $objectIDs): array;
    public function getDatasetmeta(\PoP\ComponentModel\Component\Component $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbObjectIDOrIDs): array;
    /**
     * @return CheckpointInterface[]
     */
    public function getDataAccessCheckpoints(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function getActionExecutionCheckpoints(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function shouldExecuteMutation(\PoP\ComponentModel\Component\Component $component, array &$props): bool;
    public function getComponentsToPropagateDataProperties(\PoP\ComponentModel\Component\Component $component): array;
    public function getModelSupplementaryDBObjectDataComponentTree(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function getModelSupplementaryDBObjectData(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function getMutableonrequestSupplementaryDBObjectDataComponentTree(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function getMutableonrequestSupplementaryDbobjectdata(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function doesComponentLoadData(\PoP\ComponentModel\Component\Component $component): bool;
    public function startDataloadingSection(\PoP\ComponentModel\Component\Component $component): bool;
    public function addToDatasetDatabaseKeys(\PoP\ComponentModel\Component\Component $component, array &$props, array $path, array &$ret): void;
    public function addDatasetcomponentTreeSectionFlattenedComponents(&$ret, \PoP\ComponentModel\Component\Component $component): void;
}

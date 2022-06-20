<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ConditionalLeafComponentFieldNode;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ConditionalRelationalComponentFieldNode;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentFieldNode;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalComponentFieldNode;
use PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Root\Feedback\FeedbackItemResolution;

interface ComponentProcessorInterface
{
    public function getComponentNamesToProcess(): array;
    /**
     * @return Component[]
     */
    public function getSubcomponents(Component $component): array;
    /**
     * @return Component[]
     */
    public function getAllSubcomponents(Component $component): array;
    public function executeInitPropsComponentTree(callable $eval_self_fn, callable $get_props_for_descendant_components_fn, callable $get_props_for_descendant_datasetcomponents_fn, string $propagate_fn, Component $component, array &$props, $wildcard_props_to_propagate, $targetted_props_to_propagate): void;
    public function initModelPropsComponentTree(Component $component, array &$props, array $wildcard_props_to_propagate, array $targetted_props_to_propagate): void;
    /**
     * @return array<string,mixed>
     */
    public function getModelPropsForDescendantComponents(Component $component, array &$props): array;
    /**
     * @return array<string,mixed>
     */
    public function getModelPropsForDescendantDatasetComponents(Component $component, array &$props): array;
    public function initModelProps(Component $component, array &$props): void;
    public function initRequestPropsComponentTree(Component $component, array &$props, array $wildcard_props_to_propagate, array $targetted_props_to_propagate): void;
    /**
     * @return array<string,mixed>
     */
    public function getRequestPropsForDescendantComponents(Component $component, array &$props): array;
    /**
     * @return array<string,mixed>
     */
    public function getRequestPropsForDescendantDatasetComponents(Component $component, array &$props): array;
    public function initRequestProps(Component $component, array &$props): void;
    /**
     * @param Component[]|Component $component_or_componentPath
     */
    public function setProp(array|Component $component_or_componentPath, array &$props, string $property, mixed $value, array $starting_from_componentPath = array()): void;
    /**
     * @param Component[]|Component $component_or_componentPath
     */
    public function appendGroupProp(string $group, array|Component $component_or_componentPath, array &$props, string $property, mixed $value, array $starting_from_componentPath = array()): void;
    /**
     * @param Component[]|Component $component_or_componentPath
     */
    public function appendProp(array|Component $component_or_componentPath, array &$props, string $property, mixed $value, array $starting_from_componentPath = array()): void;
    /**
     * @param Component[]|Component $component_or_componentPath
     */
    public function mergeGroupProp(string $group, array|Component $component_or_componentPath, array &$props, string $property, mixed $value, array $starting_from_componentPath = array()): void;
    /**
     * @param Component[]|Component $component_or_componentPath
     */
    public function mergeProp(array|Component $component_or_componentPath, array &$props, string $property, mixed $value, array $starting_from_componentPath = array()): void;
    public function getGroupProp(string $group, Component $component, array &$props, string $property, array $starting_from_componentPath = array()): mixed;
    public function getProp(Component $component, array &$props, string $property, array $starting_from_componentPath = array()): mixed;
    /**
     * @param Component[]|Component $component_or_componentPath
     */
    public function mergeGroupIterateKeyProp(string $group, array|Component $component_or_componentPath, array &$props, string $property, mixed $value, array $starting_from_componentPath = array()): void;
    /**
     * @param Component[]|Component $component_or_componentPath
     */
    public function mergeIterateKeyProp(array|Component $component_or_componentPath, array &$props, string $property, mixed $value, array $starting_from_componentPath = array()): void;
    /**
     * @param Component[]|Component $component_or_componentPath
     */
    public function pushProp(string $group, array|Component $component_or_componentPath, array &$props, string $property, mixed $value, array $starting_from_componentPath = array()): void;
    public function getOutputKeys(Component $component, array &$props): array;
    public function getImmutableSettingsDatasetcomponentTree(Component $component, array &$props): array;
    public function getImmutableDatasetsettings(Component $component, array &$props): array;
    public function getDatasetDatabaseKeys(Component $component, array &$props): array;
    public function getDatasource(Component $component, array &$props): string;
    public function getObjectIDOrIDs(Component $component, array &$props, &$data_properties): string | int | array | null;
    public function getRelationalTypeResolver(Component $component): ?RelationalTypeResolverInterface;
    public function getComponentMutationResolverBridge(Component $component): ?ComponentMutationResolverBridgeInterface;
    public function prepareDataPropertiesAfterMutationExecution(Component $component, array &$props, array &$data_properties): void;
    /**
     * @return LeafComponentFieldNode[]
     */
    public function getLeafComponentFieldNodes(Component $component, array &$props): array;
    /**
     * @return RelationalComponentFieldNode[]
     */
    public function getRelationalComponentFieldNodes(Component $component): array;
    /**
     * @return ConditionalLeafComponentFieldNode[]
     */
    public function getConditionalLeafComponentFieldNodes(Component $component): array;
    /**
     * @return ConditionalRelationalComponentFieldNode[]
     */
    public function getConditionalRelationalComponentFieldNodes(Component $component): array;
    public function getImmutableDataPropertiesDatasetcomponentTree(Component $component, array &$props): array;
    public function getImmutableDataPropertiesDatasetcomponentTreeFullsection(Component $component, array &$props): array;
    public function getDatasetComponentTreeSectionFlattenedDataProperties(Component $component, array &$props): array;
    /**
     * @return Component[]
     */
    public function getDatasetcomponentTreeSectionFlattenedComponents(Component $component): array;
    public function getImmutableHeaddatasetcomponentDataProperties(Component $component, array &$props): array;
    public function getMutableonmodelDataPropertiesDatasetcomponentTree(Component $component, array &$props): array;
    public function getMutableonmodelDataPropertiesDatasetcomponentTreeFullsection(Component $component, array &$props): array;
    public function getMutableonmodelHeaddatasetcomponentDataProperties(Component $component, array &$props): array;
    public function getMutableonrequestDataPropertiesDatasetcomponentTree(Component $component, array &$props): array;
    public function getMutableonrequestDataPropertiesDatasetcomponentTreeFullsection(Component $component, array &$props): array;
    public function getMutableonrequestHeaddatasetcomponentDataProperties(Component $component, array &$props): array;
    public function getDataFeedbackDatasetcomponentTree(Component $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array;
    public function getDataFeedbackComponentTree(Component $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array;
    public function getDataFeedback(Component $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array;
    public function getDataFeedbackInterreferencedComponentPath(Component $component, array &$props): ?array;
    public function getBackgroundurlsMergeddatasetcomponentTree(Component $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $objectIDs): array;
    public function getBackgroundurls(Component $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $objectIDs): array;
    public function getDatasetmeta(Component $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbObjectIDOrIDs): array;
    /**
     * @return CheckpointInterface[]
     */
    public function getDataAccessCheckpoints(Component $component, array &$props): array;
    public function getActionExecutionCheckpoints(Component $component, array &$props): array;
    public function shouldExecuteMutation(Component $component, array &$props): bool;
    /**
     * @return Component[]
     */
    public function getComponentsToPropagateDataProperties(Component $component): array;
    public function getModelSupplementaryDBObjectDataComponentTree(Component $component, array &$props): array;
    public function getModelSupplementaryDBObjectData(Component $component, array &$props): array;
    public function getMutableonrequestSupplementaryDBObjectDataComponentTree(Component $component, array &$props): array;
    public function getMutableonrequestSupplementaryDbobjectdata(Component $component, array &$props): array;
    public function doesComponentLoadData(Component $component): bool;
    public function startDataloadingSection(Component $component): bool;
    public function addToDatasetDatabaseKeys(Component $component, array &$props, array $path, array &$ret): void;
    public function addDatasetcomponentTreeSectionFlattenedComponents(array &$ret, Component $component): void;
}

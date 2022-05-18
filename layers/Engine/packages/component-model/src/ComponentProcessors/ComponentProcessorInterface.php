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
    public function getComponentVariationsToProcess(): array;
    public function getSubComponentVariations(array $componentVariation): array;
    public function getAllSubmodules(array $componentVariation): array;
    public function executeInitPropsModuletree(callable $eval_self_fn, callable $get_props_for_descendant_modules_fn, callable $get_props_for_descendant_datasetmodules_fn, string $propagate_fn, array $componentVariation, array &$props, $wildcard_props_to_propagate, $targetted_props_to_propagate): void;
    public function initModelPropsModuletree(array $componentVariation, array &$props, array $wildcard_props_to_propagate, array $targetted_props_to_propagate): void;
    public function getModelPropsForDescendantComponentVariations(array $componentVariation, array &$props): array;
    public function getModelPropsForDescendantDatasetmodules(array $componentVariation, array &$props): array;
    public function initModelProps(array $componentVariation, array &$props): void;
    public function initRequestPropsModuletree(array $componentVariation, array &$props, array $wildcard_props_to_propagate, array $targetted_props_to_propagate): void;
    public function getRequestPropsForDescendantComponentVariations(array $componentVariation, array &$props): array;
    public function getRequestPropsForDescendantDatasetmodules(array $componentVariation, array &$props): array;
    public function initRequestProps(array $componentVariation, array &$props): void;
    public function setProp(array $module_or_modulepath, array &$props, string $field, $value, array $starting_from_modulepath = array()): void;
    public function appendGroupProp(string $group, array $module_or_modulepath, array &$props, string $field, $value, array $starting_from_modulepath = array()): void;
    public function appendProp(array $module_or_modulepath, array &$props, string $field, $value, array $starting_from_modulepath = array()): void;
    public function mergeGroupProp(string $group, array $module_or_modulepath, array &$props, string $field, $value, array $starting_from_modulepath = array()): void;
    public function mergeProp(array $module_or_modulepath, array &$props, string $field, $value, array $starting_from_modulepath = array()): void;
    public function getGroupProp(string $group, array $componentVariation, array &$props, string $field, array $starting_from_modulepath = array()): mixed;
    public function getProp(array $componentVariation, array &$props, string $field, array $starting_from_modulepath = array()): mixed;
    public function mergeGroupIterateKeyProp(string $group, array $module_or_modulepath, array &$props, string $field, $value, array $starting_from_modulepath = array()): void;
    public function mergeIterateKeyProp(array $module_or_modulepath, array &$props, string $field, $value, array $starting_from_modulepath = array()): void;
    public function pushProp(string $group, array $module_or_modulepath, array &$props, string $field, $value, array $starting_from_modulepath = array()): void;
    public function getDatabaseKeys(array $componentVariation, array &$props): array;
    public function getImmutableSettingsDatasetmoduletree(array $componentVariation, array &$props): array;
    public function getImmutableDatasetsettings(array $componentVariation, array &$props): array;
    public function getDatasetDatabaseKeys(array $componentVariation, array &$props): array;
    public function getDatasource(array $componentVariation, array &$props): string;
    public function getObjectIDOrIDs(array $componentVariation, array &$props, &$data_properties): string | int | array | null;
    public function getRelationalTypeResolver(array $componentVariation): ?RelationalTypeResolverInterface;
    public function getComponentMutationResolverBridge(array $componentVariation): ?ComponentMutationResolverBridgeInterface;
    public function prepareDataPropertiesAfterMutationExecution(array $componentVariation, array &$props, array &$data_properties): void;
    /**
     * @return LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array;
    /**
     * @return RelationalModuleField[]
     */
    public function getRelationalSubmodules(array $componentVariation): array;
    /**
     * @return ConditionalLeafModuleField[]
     */
    public function getConditionalOnDataFieldSubmodules(array $componentVariation): array;
    /**
     * @return ConditionalRelationalModuleField[]
     */
    public function getConditionalOnDataFieldRelationalSubmodules(array $componentVariation): array;
    public function getImmutableDataPropertiesDatasetmoduletree(array $componentVariation, array &$props): array;
    public function getImmutableDataPropertiesDatasetmoduletreeFullsection(array $componentVariation, array &$props): array;
    public function getDatasetmoduletreeSectionFlattenedDataFields(array $componentVariation, array &$props): array;
    public function getDatasetmoduletreeSectionFlattenedComponentVariations(array $componentVariation): array;
    public function getImmutableHeaddatasetmoduleDataProperties(array $componentVariation, array &$props): array;
    public function getMutableonmodelDataPropertiesDatasetmoduletree(array $componentVariation, array &$props): array;
    public function getMutableonmodelDataPropertiesDatasetmoduletreeFullsection(array $componentVariation, array &$props): array;
    public function getMutableonmodelHeaddatasetmoduleDataProperties(array $componentVariation, array &$props): array;
    public function getMutableonrequestDataPropertiesDatasetmoduletree(array $componentVariation, array &$props): array;
    public function getMutableonrequestDataPropertiesDatasetmoduletreeFullsection(array $componentVariation, array &$props): array;
    public function getMutableonrequestHeaddatasetmoduleDataProperties(array $componentVariation, array &$props): array;
    public function getDataFeedbackDatasetmoduletree(array $componentVariation, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array;
    public function getDataFeedbackModuletree(array $componentVariation, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array;
    public function getDataFeedback(array $componentVariation, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array;
    public function getDataFeedbackInterreferencedComponentVariationPath(array $componentVariation, array &$props): ?array;
    public function getBackgroundurlsMergeddatasetmoduletree(array $componentVariation, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $objectIDs): array;
    public function getBackgroundurls(array $componentVariation, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $objectIDs): array;
    public function getDatasetmeta(array $componentVariation, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbObjectIDOrIDs): array;
    public function getDataAccessCheckpoints(array $componentVariation, array &$props): array;
    public function getActionExecutionCheckpoints(array $componentVariation, array &$props): array;
    public function shouldExecuteMutation(array $componentVariation, array &$props): bool;
    public function getModulesToPropagateDataProperties(array $componentVariation): array;
    public function getModelSupplementaryDBObjectDataModuletree(array $componentVariation, array &$props): array;
    public function getModelSupplementaryDBObjectData(array $componentVariation, array &$props): array;
    public function getMutableonrequestSupplementaryDBObjectDataModuletree(array $componentVariation, array &$props): array;
    public function getMutableonrequestSupplementaryDbobjectdata(array $componentVariation, array &$props): array;
    public function moduleLoadsData(array $componentVariation): bool;
    public function startDataloadingSection(array $componentVariation): bool;
    public function addToDatasetDatabaseKeys(array $componentVariation, array &$props, array $path, array &$ret): void;
    public function addDatasetmoduletreeSectionFlattenedComponentVariations(&$ret, array $componentVariation): void;
}

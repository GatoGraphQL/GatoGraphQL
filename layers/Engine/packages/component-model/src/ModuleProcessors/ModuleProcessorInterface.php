<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

interface ModuleProcessorInterface
{
    public function getModulesToProcess(): array;
    public function getSubmodules(array $module): array;
    public function getAllSubmodules(array $module): array;
    public function executeInitPropsModuletree(callable $eval_self_fn, callable $get_props_for_descendant_modules_fn, callable $get_props_for_descendant_datasetmodules_fn, string $propagate_fn, array $module, array &$props, $wildcard_props_to_propagate, $targetted_props_to_propagate): void;
    public function initModelPropsModuletree(array $module, array &$props, array $wildcard_props_to_propagate, array $targetted_props_to_propagate): void;
    public function getModelPropsForDescendantModules(array $module, array &$props): array;
    public function getModelPropsForDescendantDatasetmodules(array $module, array &$props): array;
    public function initModelProps(array $module, array &$props): void;
    public function initRequestPropsModuletree(array $module, array &$props, array $wildcard_props_to_propagate, array $targetted_props_to_propagate): void;
    public function getRequestPropsForDescendantModules(array $module, array &$props): array;
    public function getRequestPropsForDescendantDatasetmodules(array $module, array &$props): array;
    public function initRequestProps(array $module, array &$props): void;
    public function setProp(array $module_or_modulepath, array &$props, string $field, $value, array $starting_from_modulepath = array()): void;
    public function appendGroupProp(string $group, array $module_or_modulepath, array &$props, string $field, $value, array $starting_from_modulepath = array()): void;
    public function appendProp(array $module_or_modulepath, array &$props, string $field, $value, array $starting_from_modulepath = array()): void;
    public function mergeGroupProp(string $group, array $module_or_modulepath, array &$props, string $field, $value, array $starting_from_modulepath = array()): void;
    public function mergeProp(array $module_or_modulepath, array &$props, string $field, $value, array $starting_from_modulepath = array()): void;
    public function getGroupProp(string $group, array $module, array &$props, string $field, array $starting_from_modulepath = array()): mixed;
    public function getProp(array $module, array &$props, string $field, array $starting_from_modulepath = array()): mixed;
    public function mergeGroupIterateKeyProp(string $group, array $module_or_modulepath, array &$props, string $field, $value, array $starting_from_modulepath = array()): void;
    public function mergeIterateKeyProp(array $module_or_modulepath, array &$props, string $field, $value, array $starting_from_modulepath = array()): void;
    public function pushProp(string $group, array $module_or_modulepath, array &$props, string $field, $value, array $starting_from_modulepath = array()): void;
    public function getDatabaseKeys(array $module, array &$props): array;
    public function getImmutableSettingsDatasetmoduletree(array $module, array &$props): array;
    public function getImmutableDatasetsettings(array $module, array &$props): array;
    public function getDatasetDatabaseKeys(array $module, array &$props): array;
    public function getDatasource(array $module, array &$props): string;
    public function getDBObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array;
    public function getTypeResolverClass(array $module): ?string;
    public function getComponentMutationResolverBridgeClass(array $module): ?string;
    public function prepareDataPropertiesAfterMutationExecution(array $module, array &$props, array &$data_properties): void;
    public function getDataFields(array $module, array &$props): array;
    public function getDomainSwitchingSubmodules(array $module): array;
    public function getConditionalOnDataFieldSubmodules(array $module): array;
    public function getConditionalOnDataFieldDomainSwitchingSubmodules(array $module): array;
    public function getImmutableDataPropertiesDatasetmoduletree(array $module, array &$props): array;
    public function getImmutableDataPropertiesDatasetmoduletreeFullsection(array $module, array &$props): array;
    public function getDatasetmoduletreeSectionFlattenedDataFields(array $module, array &$props): array;
    public function getDatasetmoduletreeSectionFlattenedModules(array $module): array;
    public function getImmutableHeaddatasetmoduleDataProperties(array $module, array &$props): array;
    public function getMutableonmodelDataPropertiesDatasetmoduletree(array $module, array &$props): array;
    public function getMutableonmodelDataPropertiesDatasetmoduletreeFullsection(array $module, array &$props): array;
    public function getMutableonmodelHeaddatasetmoduleDataProperties(array $module, array &$props): array;
    public function getMutableonrequestDataPropertiesDatasetmoduletree(array $module, array &$props): array;
    public function getMutableonrequestDataPropertiesDatasetmoduletreeFullsection(array $module, array &$props): array;
    public function getMutableonrequestHeaddatasetmoduleDataProperties(array $module, array &$props): array;
    public function getDataFeedbackDatasetmoduletree(array $module, array &$props, array $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids): array;
    public function getDataFeedbackModuletree(array $module, array &$props, array $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids): array;
    public function getDataFeedback(array $module, array &$props, array $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids): array;
    public function getDataFeedbackInterreferencedModulepath(array $module, array &$props): ?array;
    public function getBackgroundurlsMergeddatasetmoduletree(array $module, array &$props, array $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDs): array;
    public function getBackgroundurls(array $module, array &$props, array $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDs): array;
    public function getDatasetmeta(array $module, array &$props, array $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs): array;
    public function getRelevantRoute(array $module, array &$props): ?string;
    public function getRelevantRouteCheckpointTarget(array $module, array &$props): string;
    public function getDataaccessCheckpoints(array $module, array &$props): array;
    public function getActionexecutionCheckpoints(array $module, array &$props): array;
    public function shouldExecuteMutation(array $module, array &$props): bool;
    public function getDataloadSource(array $module, array &$props): string;
    public function getModulesToPropagateDataProperties(array $module): array;
    public function getModelSupplementaryDbobjectdataModuletree(array $module, array &$props): array;
    public function getModelSupplementaryDbobjectdata(array $module, array &$props): array;
    public function getMutableonrequestSupplementaryDbobjectdataModuletree(array $module, array &$props): array;
    public function getMutableonrequestSupplementaryDbobjectdata(array $module, array &$props): array;
}

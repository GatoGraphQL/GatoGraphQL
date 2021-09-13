<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

use PoP\ComponentModel\Constants\DataLoading;
use PoP\ComponentModel\Constants\DataSources;
use PoP\ComponentModel\Constants\Params;
use PoP\ComponentModel\Constants\Props;
use PoP\ComponentModel\HelperServices\DataloadHelperServiceInterface;
use PoP\ComponentModel\HelperServices\RequestHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\ModuleFiltering\ModuleFilterManager;
use PoP\ComponentModel\ModuleFiltering\ModuleFilterManagerInterface;
use PoP\ComponentModel\ModuleFilters\ModulePaths;
use PoP\ComponentModel\ModulePath\ModulePathHelpersInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadingConstants;
use PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface;
use PoP\ComponentModel\Modules\ModuleUtils;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Definitions\Configuration\Request;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;

abstract class AbstractModuleProcessor implements ModuleProcessorInterface
{
    use ModulePathProcessorTrait;

    public const HOOK_INIT_MODEL_PROPS = __CLASS__ . ':initModelProps';
    public const HOOK_INIT_REQUEST_PROPS = __CLASS__ . ':initRequestProps';
    public const HOOK_ADD_HEADDATASETMODULE_DATAPROPERTIES = __CLASS__ . ':addHeaddatasetmoduleDataProperties';

    protected const MODULECOMPONENT_SUBMODULES = 'submodules';
    protected const MODULECOMPONENT_DOMAINSWITCHINGSUBMODULES = 'domain-switching-submodules';
    protected const MODULECOMPONENT_CONDITIONALONDATAFIELDSUBMODULES = 'conditional-on-data-field-submodules';
    protected const MODULECOMPONENT_CONDITIONALONDATAFIELDDOMAINSWITCHINGSUBMODULES = 'conditional-on-data-field-domain-switching-submodules';

    public function __construct(
        protected TranslationAPIInterface $translationAPI,
        protected HooksAPIInterface $hooksAPI,
        protected InstanceManagerInterface $instanceManager,
        protected FieldQueryInterpreterInterface $fieldQueryInterpreter,
        protected ModulePathHelpersInterface $modulePathHelpers,
        protected ModuleFilterManagerInterface $moduleFilterManager,
        protected ModuleProcessorManagerInterface $moduleProcessorManager,
        protected CMSServiceInterface $cmsService,
        protected NameResolverInterface $nameResolver,
        protected DataloadHelperServiceInterface $dataloadHelperService,
        protected RequestHelperServiceInterface $requestHelperService,
    ) {
    }

    public function getSubmodules(array $module): array
    {
        return array();
    }

    final public function getAllSubmodules(array $module): array
    {
        return $this->getSubmodulesByGroup($module);
    }

    // public function getNature(array $module)
    // {
    //     return null;
    // }

    //-------------------------------------------------
    // New PUBLIC Functions: Atts
    //-------------------------------------------------

    public function executeInitPropsModuletree(callable $eval_self_fn, callable $get_props_for_descendant_modules_fn, callable $get_props_for_descendant_datasetmodules_fn, string $propagate_fn, array $module, array &$props, $wildcard_props_to_propagate, $targetted_props_to_propagate): void
    {
        // Convert the module to its string representation to access it in the array
        $moduleFullName = ModuleUtils::getModuleFullName($module);

        // Initialize. If this module had been added props, then use them already
        // 1st element to merge: the general props for this module passed down the line
        // 2nd element to merge: the props set exactly to the path. They have more priority, that's why they are 2nd
        // It may contain more than one group (\PoP\ComponentModel\Constants\Props::ATTRIBUTES). Eg: maybe also POP_PROPS_JSMETHODS
        $props[$moduleFullName] = array_merge_recursive(
            $targetted_props_to_propagate[$moduleFullName] ?? array(),
            $props[$moduleFullName] ?? array()
        );

        // The module must be at the head of the $props array passed to all `initModelProps`, so that function `getPathHeadModule` can work
        $module_props = array(
            $moduleFullName => &$props[$moduleFullName],
        );

        // If ancestor modules set general props, or props targetted at this current module, then add them to the current module props
        foreach ($wildcard_props_to_propagate as $key => $value) {
            $this->setProp($module, $module_props, $key, $value);
        }

        // Before initiating the current level, set the children attributes on the array, so that doing ->setProp, ->appendProp, etc, keeps working
        $module_props[$moduleFullName][Props::DESCENDANT_ATTRIBUTES] = array_merge(
            $module_props[$moduleFullName][Props::DESCENDANT_ATTRIBUTES] ?? array(),
            $targetted_props_to_propagate ?? array()
        );

        // Initiate the current level.
        $eval_self_fn($module, $module_props);

        // Immediately after initiating the current level, extract all child attributes out from the $props, and place it on the other variable
        $targetted_props_to_propagate = $module_props[$moduleFullName][Props::DESCENDANT_ATTRIBUTES];
        unset($module_props[$moduleFullName][Props::DESCENDANT_ATTRIBUTES]);

        // But because modules can't repeat themselves down the line (or it would generate an infinite loop), then can remove the current module from the targeted props
        unset($targetted_props_to_propagate[$moduleFullName]);

        // Allow the $module to add general props for all its descendant modules
        $wildcard_props_to_propagate = array_merge(
            $wildcard_props_to_propagate,
            $get_props_for_descendant_modules_fn($module, $module_props)
        );

        // Propagate
        $submodules = $this->getAllSubmodules($module);
        $submodules = $this->moduleFilterManager->removeExcludedSubmodules($module, $submodules);

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the module has no submodules
        $this->moduleFilterManager->prepareForPropagation($module, $props);
        if ($submodules) {
            $props[$moduleFullName][Props::SUBMODULES] = $props[$moduleFullName][Props::SUBMODULES] ?? array();
            foreach ($submodules as $submodule) {
                $submodule_processor = $this->moduleProcessorManager->getProcessor($submodule);
                $submodule_wildcard_props_to_propagate = $wildcard_props_to_propagate;

                // If the submodule belongs to the same dataset, then set the shared attributies for the same-dataset modules
                if (!$submodule_processor->startDataloadingSection($submodule)) {
                    $submodule_wildcard_props_to_propagate = array_merge(
                        $submodule_wildcard_props_to_propagate,
                        $get_props_for_descendant_datasetmodules_fn($module, $module_props)
                    );
                }

                $submodule_processor->$propagate_fn($submodule, $props[$moduleFullName][Props::SUBMODULES], $submodule_wildcard_props_to_propagate, $targetted_props_to_propagate);
            }
        }
        $this->moduleFilterManager->restoreFromPropagation($module, $props);
    }

    public function initModelPropsModuletree(array $module, array &$props, array $wildcard_props_to_propagate, array $targetted_props_to_propagate): void
    {
        $this->executeInitPropsModuletree([$this, 'initModelProps'], [$this, 'getModelPropsForDescendantModules'], [$this, 'getModelPropsForDescendantDatasetmodules'], __FUNCTION__, $module, $props, $wildcard_props_to_propagate, $targetted_props_to_propagate);
    }

    public function getModelPropsForDescendantModules(array $module, array &$props): array
    {
        $ret = array();

        // If we set property 'skip-data-load' on any module, not just dataset, spread it down to its children so it reaches its contained dataset submodules
        $skip_data_load = $this->getProp($module, $props, 'skip-data-load');
        if (!is_null($skip_data_load)) {
            $ret['skip-data-load'] = $skip_data_load;
        }

        // Property 'ignore-request-params' => true makes a dataloading module not get values from $_REQUEST
        $ignore_params_from_request = $this->getProp($module, $props, 'ignore-request-params');
        if (!is_null($ignore_params_from_request)) {
            $ret['ignore-request-params'] = $ignore_params_from_request;
        }

        return $ret;
    }

    public function getModelPropsForDescendantDatasetmodules(array $module, array &$props): array
    {
        return [];
    }

    public function initModelProps(array $module, array &$props): void
    {
        // Set property "succeeding-typeResolver" on every module, so they know which is their typeResolver, needed to calculate the subcomponent data-fields when using typeResolver "*"
        if ($typeResolver_class = $this->getRelationalTypeResolverClass($module)) {
            $this->setProp($module, $props, 'succeeding-typeResolver', $typeResolver_class);
        } else {
            // Get the prop assigned to the module by its ancestor
            $typeResolver_class = $this->getProp($module, $props, 'succeeding-typeResolver');
        }
        if ($typeResolver_class) {
            // Set the property "succeeding-typeResolver" on all descendants: the same typeResolver for all submodules, and the explicit one (or get the default one for "*") for relational objects
            foreach ($this->getSubmodules($module) as $submodule) {
                $this->setProp($submodule, $props, 'succeeding-typeResolver', $typeResolver_class);
            }
            /**
             * @var RelationalTypeResolverInterface
             */
            $relationalTypeResolver = $this->instanceManager->getInstance($typeResolver_class);
            foreach ($this->getDomainSwitchingSubmodules($module) as $subcomponent_data_field => $subcomponent_modules) {
                if ($subcomponent_typeResolver_class = $this->dataloadHelperService->getTypeResolverClassFromSubcomponentDataField($relationalTypeResolver, $subcomponent_data_field)) {
                    foreach ($subcomponent_modules as $subcomponent_module) {
                        $this->setProp($subcomponent_module, $props, 'succeeding-typeResolver', $subcomponent_typeResolver_class);
                    }
                }
            }
            foreach ($this->getConditionalOnDataFieldSubmodules($module) as $conditionDataField => $conditionalSubmodules) {
                foreach ($conditionalSubmodules as $conditionalSubmodule) {
                    $this->setProp($conditionalSubmodule, $props, 'succeeding-typeResolver', $typeResolver_class);
                }
            }
            foreach ($this->getConditionalOnDataFieldDomainSwitchingSubmodules($module) as $conditionDataField => $dataFieldTypeResolverOptionsConditionalSubmodules) {
                foreach ($dataFieldTypeResolverOptionsConditionalSubmodules as $conditionalDataField => $conditionalSubmodules) {
                    if ($subcomponentTypeResolverClass = $this->dataloadHelperService->getTypeResolverClassFromSubcomponentDataField($relationalTypeResolver, $conditionalDataField)) {
                        foreach ($conditionalSubmodules as $conditionalSubmodule) {
                            $this->setProp($conditionalSubmodule, $props, 'succeeding-typeResolver', $subcomponentTypeResolverClass);
                        }
                    }
                }
            }
        }

        /**
         * Allow to add more stuff
         */
        $this->hooksAPI->doAction(
            self::HOOK_INIT_MODEL_PROPS,
            array(&$props),
            $module,
            $this
        );
    }

    public function initRequestPropsModuletree(array $module, array &$props, array $wildcard_props_to_propagate, array $targetted_props_to_propagate): void
    {
        $this->executeInitPropsModuletree([$this, 'initRequestProps'], [$this, 'getRequestPropsForDescendantModules'], [$this, 'getRequestPropsForDescendantDatasetmodules'], __FUNCTION__, $module, $props, $wildcard_props_to_propagate, $targetted_props_to_propagate);
    }

    public function getRequestPropsForDescendantModules(array $module, array &$props): array
    {
        return array();
    }

    public function getRequestPropsForDescendantDatasetmodules(array $module, array &$props): array
    {
        return array();
    }

    public function initRequestProps(array $module, array &$props): void
    {
        /**
         * Allow to add more stuff
         */
        $this->hooksAPI->doAction(
            self::HOOK_INIT_REQUEST_PROPS,
            array(&$props),
            $module,
            $this
        );
    }

    //-------------------------------------------------
    // PRIVATE Functions: Atts
    //-------------------------------------------------

    private function getPathHeadModule(array &$props): string
    {
        // From the root of the $props we obtain the current module
        reset($props);
        return (string)key($props);
    }

    private function isModulePath(array $module_or_modulepath): bool
    {
        // $module_or_modulepath can be either a single module (the current one, or its descendant), or a targetted path of modules
        // Because a module is itself represented as an array, to know which is the case, we must ask if it is:
        // - an array => single module
        // - an array of arrays (module path)
        return is_array($module_or_modulepath[0]);
    }

    private function isDescendantModule(array $module_or_modulepath, array &$props): bool
    {
        // If it is not an array of arrays, then this array is directly the module, or the descendant module on which to set the property
        if (!$this->isModulePath($module_or_modulepath)) {
            // From the root of the $props we obtain the current module
            $moduleFullName = $this->getPathHeadModule($props);

            // If the module were we are adding the att, is this same module, then we are already at the path
            // If it is not, then go down one level to that module
            return ($moduleFullName !== ModuleUtils::getModuleFullName($module_or_modulepath));
        }

        return false;
    }

    protected function getModulepath(array $module_or_modulepath, array &$props): array
    {
        // This function is used to get the path to the current module, or to a module path
        // It is not used for getting the path to a single module which is not the current one (since we do not know its path)
        if (!$props) {
            return array();
        }

        // From the root of the $props we obtain the current module
        $moduleFullName = $this->getPathHeadModule($props);

        // Calculate the path to iterate down. It always starts with the current module
        $ret = array($moduleFullName);

        // If it is an array, then we're passing the path to find the module to which to add the att
        if ($this->isModulePath($module_or_modulepath)) {
            $ret = array_merge(
                $ret,
                array_map(
                    [ModuleUtils::class, 'getModuleFullName'],
                    $module_or_modulepath
                )
            );
        }

        return $ret;
    }

    protected function addPropGroupField(string $group, array $module_or_modulepath, array &$props, $field, $value, array $starting_from_modulepath = array(), array $options = array()): void
    {
        // Iterate down to the submodule, which must be an array of modules
        if ($starting_from_modulepath) {
            // Convert it to string
            $startingFromModulepathFullNames = array_map(
                [ModuleUtils::class, 'getModuleFullName'],
                $starting_from_modulepath
            );

            // Attach the current module, which is not included on "starting_from", to step down this level too
            $moduleFullName = $this->getPathHeadModule($props);
            array_unshift($startingFromModulepathFullNames, $moduleFullName);

            // Descend into the path to find the module for which to add the att
            $module_props = &$props;
            foreach ($startingFromModulepathFullNames as $pathlevelModuleFullName) {
                $last_module_props = &$module_props;
                $lastModuleFullName = $pathlevelModuleFullName;

                $module_props[$pathlevelModuleFullName][Props::SUBMODULES] = $module_props[$pathlevelModuleFullName][Props::SUBMODULES] ?? array();
                $module_props = &$module_props[$pathlevelModuleFullName][Props::SUBMODULES];
            }

            // This is the new $props, so it starts from here
            // Save the current $props, and restore later, to make sure this array has only one key, otherwise it will not work
            $current_props = $props;
            $props = array(
                $lastModuleFullName => &$last_module_props[$lastModuleFullName]
            );
        }

        // If the module is a string, there are 2 possibilities: either it is the current module or not
        // If it is not, then it is a descendant module, which will appear at some point down the path.
        // For that case, simply save it under some other entry, from where it will propagate the props later on in `initModelPropsModuletree`
        if ($this->isDescendantModule($module_or_modulepath, $props)) {
            // It is a child module
            $att_module = $module_or_modulepath;
            $attModuleFullName = ModuleUtils::getModuleFullName($att_module);

            // From the root of the $props we obtain the current module
            $moduleFullName = $this->getPathHeadModule($props);

            // Set the child attributes under a different entry
            $props[$moduleFullName][Props::DESCENDANT_ATTRIBUTES] = $props[$moduleFullName][Props::DESCENDANT_ATTRIBUTES] ?? array();
            $module_props = &$props[$moduleFullName][Props::DESCENDANT_ATTRIBUTES];
        } else {
            // Calculate the path to iterate down
            $modulepath = $this->getModulepath($module_or_modulepath, $props);

            // Extract the lastlevel, that's the module to with to add the att
            $attModuleFullName = array_pop($modulepath);

            // Descend into the path to find the module for which to add the att
            $module_props = &$props;
            foreach ($modulepath as $pathlevelFullName) {
                $module_props[$pathlevelFullName][Props::SUBMODULES] = $module_props[$pathlevelFullName][Props::SUBMODULES] ?? array();
                $module_props = &$module_props[$pathlevelFullName][Props::SUBMODULES];
            }
        }

        // Now can proceed to add the att
        $module_props[$attModuleFullName][$group] = $module_props[$attModuleFullName][$group] ?? array();

        if ($options['append'] ?? null) {
            $module_props[$attModuleFullName][$group][$field] = $module_props[$attModuleFullName][$group][$field] ?? '';
            $module_props[$attModuleFullName][$group][$field] .= ' ' . $value;
        } elseif ($options['array'] ?? null) {
            $module_props[$attModuleFullName][$group][$field] = $module_props[$attModuleFullName][$group][$field] ?? array();
            if ($options['merge'] ?? null) {
                $module_props[$attModuleFullName][$group][$field] = array_merge(
                    $module_props[$attModuleFullName][$group][$field],
                    $value
                );
            } elseif ($options['merge-iterate-key'] ?? null) {
                foreach ($value as $value_key => $value_value) {
                    if (!$module_props[$attModuleFullName][$group][$field][$value_key]) {
                        $module_props[$attModuleFullName][$group][$field][$value_key] = array();
                    }
                    // Doing array_unique, because in the NotificationPreviewLayout, different layouts might impose a JS down the road, many times, and these get duplicated
                    $module_props[$attModuleFullName][$group][$field][$value_key] = array_unique(
                        array_merge(
                            $module_props[$attModuleFullName][$group][$field][$value_key],
                            $value_value
                        )
                    );
                }
            } elseif ($options['push'] ?? null) {
                array_push($module_props[$attModuleFullName][$group][$field], $value);
            }
        } else {
            // If already set, then do nothing
            if (!isset($module_props[$attModuleFullName][$group][$field])) {
                $module_props[$attModuleFullName][$group][$field] = $value;
            }
        }

        // Restore original $props
        if ($starting_from_modulepath) {
            $props = $current_props;
        }
    }
    protected function getPropGroupField(string $group, array $module, array &$props, string $field, array $starting_from_modulepath = array()): mixed
    {
        $group = $this->getPropGroup($group, $module, $props, $starting_from_modulepath);
        return $group[$field] ?? null;
    }
    protected function getPropGroup(string $group, array $module, array &$props, array $starting_from_modulepath = array()): array
    {
        if (!$props) {
            return array();
        }

        $module_props = &$props;
        foreach ($starting_from_modulepath as $pathlevelModule) {
            $pathlevelModuleFullName = ModuleUtils::getModuleFullName($pathlevelModule);
            $module_props = &$module_props[$pathlevelModuleFullName][Props::SUBMODULES];
        }

        $moduleFullName = ModuleUtils::getModuleFullName($module);
        return $module_props[$moduleFullName][$group] ?? array();
    }
    protected function addGroupProp(string $group, array $module_or_modulepath, array &$props, string $field, $value, array $starting_from_modulepath = array()): void
    {
        $this->addPropGroupField($group, $module_or_modulepath, $props, $field, $value, $starting_from_modulepath);
    }
    public function setProp(array $module_or_modulepath, array &$props, string $field, $value, array $starting_from_modulepath = array()): void
    {
        $this->addGroupProp(Props::ATTRIBUTES, $module_or_modulepath, $props, $field, $value, $starting_from_modulepath);
    }
    public function appendGroupProp(string $group, array $module_or_modulepath, array &$props, string $field, $value, array $starting_from_modulepath = array()): void
    {
        $this->addPropGroupField($group, $module_or_modulepath, $props, $field, $value, $starting_from_modulepath, array('append' => true));
    }
    public function appendProp(array $module_or_modulepath, array &$props, string $field, $value, array $starting_from_modulepath = array()): void
    {
        $this->appendGroupProp(Props::ATTRIBUTES, $module_or_modulepath, $props, $field, $value, $starting_from_modulepath);
    }
    public function mergeGroupProp(string $group, array $module_or_modulepath, array &$props, string $field, $value, array $starting_from_modulepath = array()): void
    {
        $this->addPropGroupField($group, $module_or_modulepath, $props, $field, $value, $starting_from_modulepath, array('array' => true, 'merge' => true));
    }
    public function mergeProp(array $module_or_modulepath, array &$props, string $field, $value, array $starting_from_modulepath = array()): void
    {
        $this->mergeGroupProp(Props::ATTRIBUTES, $module_or_modulepath, $props, $field, $value, $starting_from_modulepath);
    }
    public function getGroupProp(string $group, array $module, array &$props, string $field, array $starting_from_modulepath = array()): mixed
    {
        return $this->getPropGroupField($group, $module, $props, $field, $starting_from_modulepath);
    }
    public function getProp(array $module, array &$props, string $field, array $starting_from_modulepath = array()): mixed
    {
        return $this->getGroupProp(Props::ATTRIBUTES, $module, $props, $field, $starting_from_modulepath);
    }
    public function mergeGroupIterateKeyProp(string $group, array $module_or_modulepath, array &$props, string $field, $value, array $starting_from_modulepath = array()): void
    {
        $this->addPropGroupField($group, $module_or_modulepath, $props, $field, $value, $starting_from_modulepath, array('array' => true, 'merge-iterate-key' => true));
    }
    public function mergeIterateKeyProp(array $module_or_modulepath, array &$props, string $field, $value, array $starting_from_modulepath = array()): void
    {
        $this->mergeGroupIterateKeyProp(Props::ATTRIBUTES, $module_or_modulepath, $props, $field, $value, $starting_from_modulepath);
    }
    public function pushProp(string $group, array $module_or_modulepath, array &$props, string $field, $value, array $starting_from_modulepath = array()): void
    {
        $this->addPropGroupField($group, $module_or_modulepath, $props, $field, $value, $starting_from_modulepath, array('array' => true, 'push' => true));
    }

    //-------------------------------------------------
    // New PUBLIC Functions: Model Static Settings
    //-------------------------------------------------

    public function getDatabaseKeys(array $module, array &$props): array
    {
        $ret = array();
        if ($typeResolver_class = $this->getRelationalTypeResolverClass($module)) {
            /**
             * @var RelationalTypeResolverInterface
             */
            $relationalTypeResolver = $this->instanceManager->getInstance((string)$typeResolver_class);
            if ($dbkey = $relationalTypeResolver->getTypeOutputName()) {
                // Place it under "id" because it is for fetching the current object from the DB, which is found through dbObject.id
                $ret['id'] = $dbkey;
            }
        }

        // This prop is set for both dataloading and non-dataloading modules
        if ($typeResolver_class = $this->getProp($module, $props, 'succeeding-typeResolver')) {
            /**
             * @var RelationalTypeResolverInterface
             */
            $relationalTypeResolver = $this->instanceManager->getInstance($typeResolver_class);
            foreach (array_keys($this->getDomainSwitchingSubmodules($module)) as $subcomponent_data_field) {
                // If passing a subcomponent fieldname that doesn't exist to the API, then $subcomponent_typeResolver_class will be empty
                if ($subcomponent_typeResolver_class = $this->dataloadHelperService->getTypeResolverClassFromSubcomponentDataField($relationalTypeResolver, $subcomponent_data_field)) {
                    $subcomponent_typeResolver = $this->instanceManager->getInstance($subcomponent_typeResolver_class);
                    // If there is an alias, store the results under this. Otherwise, on the fieldName+fieldArgs
                    $subcomponent_data_field_outputkey = $this->fieldQueryInterpreter->getUniqueFieldOutputKey($relationalTypeResolver, $subcomponent_data_field);
                    $ret[$subcomponent_data_field_outputkey] = $subcomponent_typeResolver->getTypeOutputName();
                }
            }
            foreach ($this->getConditionalOnDataFieldDomainSwitchingSubmodules($module) as $conditionDataField => $dataFieldTypeResolverOptionsConditionalSubmodules) {
                foreach (array_keys($dataFieldTypeResolverOptionsConditionalSubmodules) as $conditionalDataField) {
                    // If passing a subcomponent fieldname that doesn't exist to the API, then $subcomponentTypeResolverClass will be empty
                    if ($subcomponentTypeResolverClass = $this->dataloadHelperService->getTypeResolverClassFromSubcomponentDataField($relationalTypeResolver, $conditionalDataField)) {
                        $subcomponent_typeResolver = $this->instanceManager->getInstance($subcomponentTypeResolverClass);
                        // If there is an alias, store the results under this. Otherwise, on the fieldName+fieldArgs
                        $subcomponent_data_field_outputkey = $this->fieldQueryInterpreter->getUniqueFieldOutputKey($relationalTypeResolver, $conditionalDataField);
                        $ret[$subcomponent_data_field_outputkey] = $subcomponent_typeResolver->getTypeOutputName();
                    }
                }
            }
        }

        return $ret;
    }

    //-------------------------------------------------
    // New PUBLIC Functions: Model Static Settings
    //-------------------------------------------------

    public function getImmutableSettingsDatasetmoduletree(array $module, array &$props): array
    {
        $options = array(
            'only-execute-on-dataloading-modules' => true,
        );
        return $this->executeOnSelfAndPropagateToModules('getImmutableDatasetsettings', __FUNCTION__, $module, $props, true, $options);
    }

    public function getImmutableDatasetsettings(array $module, array &$props): array
    {
        $ret = array();

        if ($database_keys = $this->getDatasetDatabaseKeys($module, $props)) {
            $ret['dbkeys'] = $database_keys;
        }

        return $ret;
    }

    protected function addToDatasetDatabaseKeys(array $module, array &$props, $path, &$ret)
    {
        // Add the current module's dbkeys
        if ($typeResolver_class = $this->getRelationalTypeResolverClass($module)) {
            /**
             * @var RelationalTypeResolverInterface
             */
            $relationalTypeResolver = $this->instanceManager->getInstance((string)$typeResolver_class);
            $dbkeys = $this->getDatabaseKeys($module, $props);
            foreach ($dbkeys as $field => $dbkey) {
                $field_outputkey = $this->fieldQueryInterpreter->getUniqueFieldOutputKey($relationalTypeResolver, $field);
                $ret[implode('.', array_merge($path, [$field_outputkey]))] = $dbkey;
            }
        }

        // Propagate to all submodules which have no typeResolver
        $moduleFullName = ModuleUtils::getModuleFullName($module);

        if ($typeResolver_class = $this->getProp($module, $props, 'succeeding-typeResolver')) {
            /**
             * @var RelationalTypeResolverInterface
             */
            $relationalTypeResolver = $this->instanceManager->getInstance($typeResolver_class);

            $this->moduleFilterManager->prepareForPropagation($module, $props);
            foreach ($this->getDomainSwitchingSubmodules($module) as $subcomponent_data_field => $subcomponent_modules) {
                $subcomponent_data_field_outputkey = $this->fieldQueryInterpreter->getUniqueFieldOutputKey($relationalTypeResolver, $subcomponent_data_field);
                // Only modules which do not load data
                $subcomponent_modules = array_filter($subcomponent_modules, function ($submodule) {
                    return !$this->moduleProcessorManager->getProcessor($submodule)->startDataloadingSection($submodule);
                });
                foreach ($subcomponent_modules as $subcomponent_module) {
                    $this->moduleProcessorManager->getProcessor($subcomponent_module)->addToDatasetDatabaseKeys($subcomponent_module, $props[$moduleFullName][Props::SUBMODULES], array_merge($path, [$subcomponent_data_field_outputkey]), $ret);
                }
            }
            foreach ($this->getConditionalOnDataFieldDomainSwitchingSubmodules($module) as $conditionDataField => $dataFieldTypeResolverOptionsConditionalSubmodules) {
                foreach ($dataFieldTypeResolverOptionsConditionalSubmodules as $conditionalDataField => $subcomponent_modules) {
                    $subcomponent_data_field_outputkey = $this->fieldQueryInterpreter->getUniqueFieldOutputKey($relationalTypeResolver, $conditionalDataField);
                    // Only modules which do not load data
                    $subcomponent_modules = array_filter($subcomponent_modules, function ($submodule) {
                        return !$this->moduleProcessorManager->getProcessor($submodule)->startDataloadingSection($submodule);
                    });
                    foreach ($subcomponent_modules as $subcomponent_module) {
                        $this->moduleProcessorManager->getProcessor($subcomponent_module)->addToDatasetDatabaseKeys($subcomponent_module, $props[$moduleFullName][Props::SUBMODULES], array_merge($path, [$subcomponent_data_field_outputkey]), $ret);
                    }
                }
            }

            // Only modules which do not load data
            $submodules = array_filter($this->getSubmodules($module), function ($submodule) {
                return !$this->moduleProcessorManager->getProcessor($submodule)->startDataloadingSection($submodule);
            });
            foreach ($submodules as $submodule) {
                $this->moduleProcessorManager->getProcessor($submodule)->addToDatasetDatabaseKeys($submodule, $props[$moduleFullName][Props::SUBMODULES], $path, $ret);
            }
            $this->moduleFilterManager->restoreFromPropagation($module, $props);
        }
    }

    public function getDatasetDatabaseKeys(array $module, array &$props): array
    {
        $ret = array();
        $this->addToDatasetDatabaseKeys($module, $props, array(), $ret);
        return $ret;
    }

    //-------------------------------------------------
    // New PUBLIC Functions: Static + Stateful Data
    //-------------------------------------------------

    public function getDatasource(array $module, array &$props): string
    {
        // Each module can only return one piece of data, and it must be indicated if it static or mutableonrequest
        // Retrieving only 1 piece is needed so that its children do not get confused what data their getDataFields applies to
        return DataSources::MUTABLEONREQUEST;
    }

    public function getObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array
    {
        return array();
    }

    public function getRelationalTypeResolverClass(array $module): ?string
    {
        return null;
    }

    public function moduleLoadsData(array $module): bool
    {
        return !is_null($this->getRelationalTypeResolverClass($module));
    }

    public function startDataloadingSection(array $module): bool
    {
        return $this->moduleLoadsData($module);
    }

    public function getComponentMutationResolverBridgeClass(array $module): ?string
    {
        return null;
    }

    public function prepareDataPropertiesAfterMutationExecution(array $module, array &$props, array &$data_properties): void
    {
        // Do nothing
    }

    public function getDataFields(array $module, array &$props): array
    {
        return array();
    }

    public function getDomainSwitchingSubmodules(array $module): array
    {
        return array();
    }

    public function getConditionalOnDataFieldSubmodules(array $module): array
    {
        return array();
    }

    public function getConditionalOnDataFieldDomainSwitchingSubmodules(array $module): array
    {
        return array();
    }

    //-------------------------------------------------
    // New PUBLIC Functions: Data Properties
    //-------------------------------------------------

    public function getImmutableDataPropertiesDatasetmoduletree(array $module, array &$props): array
    {
        // The data-properties start on a dataloading module, and finish on the next dataloding module down the line
        // This way, we can collect all the data-fields that the module will need to load for its dbobjects
        return $this->executeOnSelfAndPropagateToModules('getImmutableDataPropertiesDatasetmoduletreeFullsection', __FUNCTION__, $module, $props, false);
    }

    public function getImmutableDataPropertiesDatasetmoduletreeFullsection(array $module, array &$props): array
    {
        $ret = array();

        // Only if this module loads data => We are at the head nodule of the dataset section
        if ($this->moduleLoadsData($module)) {
            // Load the data-fields from all modules inside this section
            // And then, only for the top node, add its extra properties
            $properties = array_merge(
                $this->getDatasetmoduletreeSectionFlattenedDataFields($module, $props),
                $this->getImmutableHeaddatasetmoduleDataProperties($module, $props)
            );

            if ($properties) {
                $ret[DataLoading::DATA_PROPERTIES] = $properties;
            }
        }

        return $ret;
    }

    public function getDatasetmoduletreeSectionFlattenedDataFields(array $module, array &$props): array
    {
        $ret = array();

        // Calculate the data-fields from merging them with the subcomponent modules' keys, which are data-fields too
        if (
            $data_fields = array_unique(
                array_merge(
                    $this->getDataFields($module, $props),
                    array_keys($this->getDomainSwitchingSubmodules($module)),
                    array_keys($this->getConditionalOnDataFieldSubmodules($module)),
                    array_keys($this->getConditionalOnDataFieldDomainSwitchingSubmodules($module))
                )
            )
        ) {
            $ret['data-fields'] = $data_fields;
        }

        // Propagate down to the components
        $this->flattenDatasetmoduletreeDataProperties(__FUNCTION__, $ret, $module, $props);

        // Propagate down to the subcomponent modules
        $this->flattenRelationalDBObjectDataProperties(__FUNCTION__, $ret, $module, $props);

        return $ret;
    }

    public function getDatasetmoduletreeSectionFlattenedModules(array $module): array
    {
        $ret = [];

        $this->addDatasetmoduletreeSectionFlattenedModules($ret, $module);

        return array_values(
            array_unique(
                $ret,
                SORT_REGULAR
            )
        );
    }

    protected function addDatasetmoduletreeSectionFlattenedModules(&$ret, array $module)
    {
        $ret[] = $module;

        // Propagate down to the components
        // $this->flattenDatasetmoduletreeModules(__FUNCTION__, $ret, $module);
        // Exclude the subcomponent modules here
        if ($submodules = $this->getModulesToPropagateDataProperties($module)) {
            foreach ($submodules as $submodule) {
                $submodule_processor = $this->moduleProcessorManager->getProcessor($submodule);

                // Propagate only if the submodule doesn't load data. If it does, this is the end of the data line, and the submodule is the beginning of a new datasetmoduletree
                if (!$submodule_processor->startDataloadingSection($submodule)) {
                    $submodule_processor->addDatasetmoduletreeSectionFlattenedModules($ret, $submodule);
                }
            }
        }
    }

    // protected function flattenDatasetmoduletreeModules($propagate_fn, &$ret, array $module)
    // {
    //     // Exclude the subcomponent modules here
    //     if ($submodules = $this->getModulesToPropagateDataProperties($module)) {
    //         foreach ($submodules as $submodule) {
    //             $submodule_processor = $this->moduleProcessorManager->getProcessor($submodule);

    //             // Propagate only if the submodule doesn't have a typeResolver. If it does, this is the end of the data line, and the submodule is the beginning of a new datasetmoduletree
    //             if (!$submodule_processor->startDataloadingSection($submodule)) {
    //                 if ($submodule_ret = $submodule_processor->$propagate_fn($submodule)) {
    //                     $ret = array_merge(
    //                         $ret,
    //                         $submodule_ret
    //                     );
    //                 }
    //             }
    //         }
    //     }
    // }

    public function getImmutableHeaddatasetmoduleDataProperties(array $module, array &$props): array
    {
        // By default return nothing at the last level
        $ret = array();

        // From the State property we find out if it's Static of Stateful
        $datasource = $this->getDatasource($module, $props);
        $ret[DataloadingConstants::DATASOURCE] = $datasource;

        // Add the properties below either as static or mutableonrequest
        if ($datasource == DataSources::IMMUTABLE) {
            $this->addHeaddatasetmoduleDataProperties($ret, $module, $props);
        }

        return $ret;
    }

    protected function addHeaddatasetmoduleDataProperties(&$ret, array $module, array &$props)
    {
        /**
         * Allow to add more stuff
         */
        $this->hooksAPI->doAction(
            self::HOOK_ADD_HEADDATASETMODULE_DATAPROPERTIES,
            array(&$ret),
            $module,
            array(&$props),
            $this
        );
    }

    public function getMutableonmodelDataPropertiesDatasetmoduletree(array $module, array &$props): array
    {
        return $this->executeOnSelfAndPropagateToModules('getMutableonmodelDataPropertiesDatasetmoduletreeFullsection', __FUNCTION__, $module, $props, false);
    }

    public function getMutableonmodelDataPropertiesDatasetmoduletreeFullsection(array $module, array &$props): array
    {
        $ret = array();

        // Only if this module loads data
        if ($this->moduleLoadsData($module)) {
            $properties = $this->getMutableonmodelHeaddatasetmoduleDataProperties($module, $props);
            if ($properties) {
                $ret[DataLoading::DATA_PROPERTIES] = $properties;
            }
        }

        return $ret;
    }

    public function getMutableonmodelHeaddatasetmoduleDataProperties(array $module, array &$props): array
    {
        $ret = array();

        // Add the properties below either as static or mutableonrequest
        $datasource = $this->getDatasource($module, $props);
        if ($datasource == DataSources::MUTABLEONMODEL) {
            $this->addHeaddatasetmoduleDataProperties($ret, $module, $props);
        }

        // Fetch params from request?
        $ignore_params_from_request = $this->getProp($module, $props, 'ignore-request-params');
        if (!is_null($ignore_params_from_request)) {
            $ret[DataloadingConstants::IGNOREREQUESTPARAMS] = $ignore_params_from_request;
        }

        return $ret;
    }

    public function getMutableonrequestDataPropertiesDatasetmoduletree(array $module, array &$props): array
    {
        return $this->executeOnSelfAndPropagateToModules('getMutableonrequestDataPropertiesDatasetmoduletreeFullsection', __FUNCTION__, $module, $props, false);
    }

    public function getMutableonrequestDataPropertiesDatasetmoduletreeFullsection(array $module, array &$props): array
    {
        $ret = array();

        // Only if this module loads data
        if ($this->moduleLoadsData($module)) {
            // // Load the data-fields from all modules inside this section
            // // And then, only for the top node, add its extra properties
            // $properties = array_merge(
            //     $this->get_mutableonrequest_data_properties_datasetmoduletree_section($module, $props),
            //     $this->getMutableonrequestHeaddatasetmoduleDataProperties($module, $props)
            // );
            $properties = $this->getMutableonrequestHeaddatasetmoduleDataProperties($module, $props);

            if ($properties) {
                $ret[DataLoading::DATA_PROPERTIES] = $properties;
            }
        }

        return $ret;
    }

    public function getMutableonrequestHeaddatasetmoduleDataProperties(array $module, array &$props): array
    {
        $ret = array();

        // Add the properties below either as static or mutableonrequest
        $datasource = $this->getDatasource($module, $props);
        if ($datasource == DataSources::MUTABLEONREQUEST) {
            $this->addHeaddatasetmoduleDataProperties($ret, $module, $props);
        }

        if ($dataload_source = $this->getDataloadSource($module, $props)) {
            $ret[DataloadingConstants::SOURCE] = $dataload_source;
        }

        // When loading data or execution an action, check if to validate checkpoints?
        // This is in MUTABLEONREQUEST instead of STATIC because the checkpoints can change depending on doingPost()
        // (such as done to set-up checkpoint configuration for POP_USERSTANCE_ROUTE_ADDOREDITSTANCE, or within POPUSERLOGIN_CHECKPOINTCONFIGURATION_REQUIREUSERSTATEONDOINGPOST)
        if ($checkpoints = $this->getDataAccessCheckpoints($module, $props)) {
            $ret[DataLoading::DATA_ACCESS_CHECKPOINTS] = $checkpoints;
        }

        // To trigger the actionexecuter, its own checkpoints must be successful
        if ($checkpoints = $this->getActionExecutionCheckpoints($module, $props)) {
            $ret[DataLoading::ACTION_EXECUTION_CHECKPOINTS] = $checkpoints;
        }

        return $ret;
    }

    //-------------------------------------------------
    // New PUBLIC Functions: Data Feedback
    //-------------------------------------------------

    public function getDataFeedbackDatasetmoduletree(array $module, array &$props, array $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids): array
    {
        return $this->executeOnSelfAndPropagateToDatasetmodules('getDataFeedbackModuletree', __FUNCTION__, $module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);
    }

    public function getDataFeedbackModuletree(array $module, array &$props, array $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids): array
    {
        $ret = array();

        if ($feedback = $this->getDataFeedback($module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)) {
            $ret[DataLoading::FEEDBACK] = $feedback;
        }

        return $ret;
    }

    public function getDataFeedback(array $module, array &$props, array $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids): array
    {
        return array();
    }

    public function getDataFeedbackInterreferencedModulepath(array $module, array &$props): ?array
    {
        return null;
    }

    //-------------------------------------------------
    // Background URLs
    //-------------------------------------------------

    public function getBackgroundurlsMergeddatasetmoduletree(array $module, array &$props, array $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDs): array
    {
        return $this->executeOnSelfAndMergeWithDatasetmodules('getBackgroundurls', __FUNCTION__, $module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDs);
    }

    public function getBackgroundurls(array $module, array &$props, array $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDs): array
    {
        return array();
    }

    //-------------------------------------------------
    // Dataset Meta
    //-------------------------------------------------

    public function getDatasetmeta(array $module, array &$props, array $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs): array
    {
        $ret = array();

        if ($dataload_source = $data_properties[DataloadingConstants::SOURCE] ?? null) {
            $ret['dataloadsource'] = $dataload_source;
        }

        return $ret;
    }

    //-------------------------------------------------
    // Others
    //-------------------------------------------------

    public function getDataAccessCheckpoints(array $module, array &$props): array
    {
        return [];
    }

    public function getActionExecutionCheckpoints(array $module, array &$props): array
    {
        return [];
    }

    public function shouldExecuteMutation(array $module, array &$props): bool
    {
        // By default, execute only if the module is targeted for execution and doing POST
        $vars = ApplicationState::getVars();
        return 'POST' == $_SERVER['REQUEST_METHOD'] && $vars['actionpath'] == $this->modulePathHelpers->getStringifiedModulePropagationCurrentPath($module);
    }

    public function getDataloadSource(array $module, array &$props): string
    {
        /** @var ModulePaths */
        $modulePaths = $this->instanceManager->getInstance(ModulePaths::class);
        // Because a component can interact with itself by adding ?modulepaths=...,
        // then, by default, we simply set the dataload source to point to itself!
        $stringified_module_propagation_current_path = $this->modulePathHelpers->getStringifiedModulePropagationCurrentPath($module);
        $ret = GeneralUtils::addQueryArgs(
            [
                ModuleFilterManager::URLPARAM_MODULEFILTER => $modulePaths->getName(),
                ModulePaths::URLPARAM_MODULEPATHS . '[]' => $stringified_module_propagation_current_path,
            ],
            $this->requestHelperService->getCurrentURL()
        );

        // If we are in the API currently, stay in the API
        $vars = ApplicationState::getVars();
        if ($scheme = $vars['scheme']) {
            $ret = GeneralUtils::addQueryArgs([
                Params::SCHEME => $scheme,
            ], $ret);
        }

        // Allow to add extra modulepaths set from above
        if ($extra_module_paths = $this->getProp($module, $props, 'dataload-source-add-modulepaths')) {
            foreach ($extra_module_paths as $modulepath) {
                $ret = GeneralUtils::addQueryArgs([
                    ModulePaths::URLPARAM_MODULEPATHS . '[]' => $this->modulePathHelpers->stringifyModulePath($modulepath),
                ], $ret);
            }
        }

        // Add the actionpath too
        if ($this->getComponentMutationResolverBridgeClass($module)) {
            $ret = GeneralUtils::addQueryArgs([
                Params::ACTION_PATH => $stringified_module_propagation_current_path,
            ], $ret);
        }

        // Add the format to the query url
        if ($this instanceof FormattableModuleInterface) {
            if ($format = $this->getFormat($module)) {
                $ret = GeneralUtils::addQueryArgs([
                    Params::FORMAT => $format,
                ], $ret);
            }
        }

        // If mangled, make it mandle
        if ($mangled = $vars['mangled']) {
            $ret = GeneralUtils::addQueryArgs([
                Request::URLPARAM_MANGLED => $mangled,
            ], $ret);
        }

        return $ret;
    }

    public function getModulesToPropagateDataProperties(array $module): array
    {
        return $this->getSubmodulesByGroup(
            $module,
            array(
                self::MODULECOMPONENT_SUBMODULES,
                self::MODULECOMPONENT_CONDITIONALONDATAFIELDSUBMODULES,
            )
        );
    }

    protected function flattenDatasetmoduletreeDataProperties($propagate_fn, &$ret, array $module, array &$props)
    {
        $moduleFullName = ModuleUtils::getModuleFullName($module);

        // Exclude the subcomponent modules here
        $this->moduleFilterManager->prepareForPropagation($module, $props);
        if ($submodules = $this->getModulesToPropagateDataProperties($module)) {
            // Calculate in 2 steps:
            // First step: The conditional-on-data-field-submodules must have their data-fields added under entry "conditional-data-fields"
            if ($conditionalOnDataFieldSubmodules = $this->getConditionalOnDataFieldSubmodules($module)) {
                $directSubmodules = $this->getSubmodules($module);
                // Instead of assigning to $ret, first assign it to a temporary variable, so we can then replace 'data-fields' with 'conditional-data-fields' before merging to $ret
                foreach ($conditionalOnDataFieldSubmodules as $conditionDataField => $conditionalSubmodules) {
                    // Calculate those fields which are certainly to be propagated, and not part of the direct submodules
                    // Using this really ugly way because, for comparing modules, using `array_diff` and `intersect` fail
                    for ($i = count($conditionalSubmodules) - 1; $i >= 0; $i--) {
                        // If this submodule is also in the direct ones, then it's not conditional anymore
                        if (in_array($conditionalSubmodules[$i], $directSubmodules)) {
                            array_splice($conditionalSubmodules, $i, 1);
                        }
                    }
                    foreach ($conditionalSubmodules as $submodule) {
                        $submodule_processor = $this->moduleProcessorManager->getProcessor($submodule);

                        // Propagate only if the submodule doesn't load data. If it does, this is the end of the data line, and the submodule is the beginning of a new datasetmoduletree
                        if (!$submodule_processor->startDataloadingSection($submodule, $props[$moduleFullName][Props::SUBMODULES])) {
                            if ($submodule_ret = $submodule_processor->$propagate_fn($submodule, $props[$moduleFullName][Props::SUBMODULES])) {
                                // Chain the "data-fields" from the sublevels under the current "conditional-data-fields"
                                // Move from "data-fields" to "conditional-data-fields"
                                if ($submodule_ret['data-fields'] ?? null) {
                                    foreach ($submodule_ret['data-fields'] as $submodule_data_field) {
                                        $ret['conditional-data-fields'][$conditionDataField][$submodule_data_field] = [];
                                    }
                                    unset($submodule_ret['data-fields']);
                                }
                                // Chain the conditional-data-fields at the end of the one from this module
                                if ($submodule_ret['conditional-data-fields'] ?? null) {
                                    foreach ($submodule_ret['conditional-data-fields'] as $submodule_condition_data_field => $submodule_conditional_data_fields) {
                                        $ret['conditional-data-fields'][$conditionDataField][$submodule_condition_data_field] = array_merge(
                                            $ret['conditional-data-fields'][$conditionDataField][$submodule_condition_data_field] ?? [],
                                            $submodule_conditional_data_fields
                                        );
                                    }
                                    unset($submodule_ret['conditional-data-fields']);
                                }

                                // array_merge_recursive => data-fields from different sidebar-components can be integrated all together
                                $ret = array_merge_recursive(
                                    $ret,
                                    $submodule_ret
                                );
                            }
                        }
                    }

                    // Extract the conditional submodules from the rest of the submodules, which will be processed below
                    foreach ($conditionalSubmodules as $conditionalSubmodule) {
                        $pos = array_search($conditionalSubmodule, $submodules);
                        if ($pos !== false) {
                            array_splice($submodules, $pos, 1);
                        }
                    }
                }
            }

            // Second step: all the other submodules can be calculated directly
            foreach ($submodules as $submodule) {
                $submodule_processor = $this->moduleProcessorManager->getProcessor($submodule);

                // Propagate only if the submodule doesn't load data. If it does, this is the end of the data line, and the submodule is the beginning of a new datasetmoduletree
                if (!$submodule_processor->startDataloadingSection($submodule, $props[$moduleFullName][Props::SUBMODULES])) {
                    if ($submodule_ret = $submodule_processor->$propagate_fn($submodule, $props[$moduleFullName][Props::SUBMODULES])) {
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
        $this->moduleFilterManager->restoreFromPropagation($module, $props);
    }

    protected function flattenRelationalDBObjectDataProperties($propagate_fn, &$ret, array $module, array &$props)
    {
        $moduleFullName = ModuleUtils::getModuleFullName($module);

        // Combine the direct and conditionalOnDataField modules all together to iterate below
        $domainSwitchingSubmodules = $this->getDomainSwitchingSubmodules($module);
        foreach ($this->getConditionalOnDataFieldDomainSwitchingSubmodules($module) as $conditionDataField => $dataFieldTypeResolverOptionsConditionalSubmodules) {
            foreach ($dataFieldTypeResolverOptionsConditionalSubmodules as $conditionalDataField => $conditionalSubmodules) {
                $domainSwitchingSubmodules[$conditionalDataField] = array_values(array_unique(array_merge(
                    $conditionalDataField[$conditionalDataField] ?? [],
                    $conditionalSubmodules
                )));
            }
        }

        // If it has subcomponent modules, integrate them under 'subcomponents'
        $this->moduleFilterManager->prepareForPropagation($module, $props);
        foreach ($domainSwitchingSubmodules as $subcomponent_data_field => $subcomponent_modules) {
            $subcomponent_modules_data_properties = array(
                'data-fields' => array(),
                'conditional-data-fields' => array(),
                'subcomponents' => array()
            );
            foreach ($subcomponent_modules as $subcomponent_module) {
                $subcomponent_processor = $this->moduleProcessorManager->getProcessor($subcomponent_module);
                if ($subcomponent_module_data_properties = $subcomponent_processor->$propagate_fn($subcomponent_module, $props[$moduleFullName][Props::SUBMODULES])) {
                    $subcomponent_modules_data_properties = array_merge_recursive(
                        $subcomponent_modules_data_properties,
                        $subcomponent_module_data_properties
                    );
                }
            }

            $ret['subcomponents'][$subcomponent_data_field] = $ret['subcomponents'][$subcomponent_data_field] ?? array();
            if ($subcomponent_modules_data_properties['data-fields'] ?? null) {
                $subcomponent_modules_data_properties['data-fields'] = array_unique($subcomponent_modules_data_properties['data-fields']);
                $ret['subcomponents'][$subcomponent_data_field]['data-fields'] = array_values(array_unique(array_merge(
                    $ret['subcomponents'][$subcomponent_data_field]['data-fields'] ?? [],
                    $subcomponent_modules_data_properties['data-fields']
                )));
            }
            if ($subcomponent_modules_data_properties['conditional-data-fields'] ?? null) {
                $ret['subcomponents'][$subcomponent_data_field]['conditional-data-fields'] = $ret['subcomponents'][$subcomponent_data_field]['conditional-data-fields'] ?? [];
                foreach ($subcomponent_modules_data_properties['conditional-data-fields'] as $conditionDataField => $conditionalDataFields) {
                    $ret['subcomponents'][$subcomponent_data_field]['conditional-data-fields'][$conditionDataField] = array_merge_recursive(
                        $ret['subcomponents'][$subcomponent_data_field]['conditional-data-fields'][$conditionDataField] ?? [],
                        $conditionalDataFields
                    );
                }
            }

            if ($subcomponent_modules_data_properties['subcomponents'] ?? null) {
                $ret['subcomponents'][$subcomponent_data_field]['subcomponents'] = $ret['subcomponents'][$subcomponent_data_field]['subcomponents'] ?? array();
                $ret['subcomponents'][$subcomponent_data_field]['subcomponents'] = array_merge_recursive(
                    $ret['subcomponents'][$subcomponent_data_field]['subcomponents'],
                    $subcomponent_modules_data_properties['subcomponents']
                );
            }
        }
        $this->moduleFilterManager->restoreFromPropagation($module, $props);
    }


    //-------------------------------------------------
    // New PUBLIC Functions: Static Data
    //-------------------------------------------------

    public function getModelSupplementaryDbobjectdataModuletree(array $module, array &$props): array
    {
        return $this->executeOnSelfAndMergeWithModules('getModelSupplementaryDbobjectdata', __FUNCTION__, $module, $props);
    }

    public function getModelSupplementaryDbobjectdata(array $module, array &$props): array
    {
        return array();
    }

    //-------------------------------------------------
    // New PUBLIC Functions: Stateful Data
    //-------------------------------------------------

    public function getMutableonrequestSupplementaryDbobjectdataModuletree(array $module, array &$props): array
    {
        return $this->executeOnSelfAndMergeWithModules('getMutableonrequestSupplementaryDbobjectdata', __FUNCTION__, $module, $props);
    }

    public function getMutableonrequestSupplementaryDbobjectdata(array $module, array &$props): array
    {
        return array();
    }

    private function getSubmodulesByGroup(array $module, $components = array()): array
    {
        if (empty($components)) {
            $components = array(
                self::MODULECOMPONENT_SUBMODULES,
                self::MODULECOMPONENT_DOMAINSWITCHINGSUBMODULES,
                self::MODULECOMPONENT_CONDITIONALONDATAFIELDSUBMODULES,
                self::MODULECOMPONENT_CONDITIONALONDATAFIELDDOMAINSWITCHINGSUBMODULES,
            );
        }

        $ret = array();

        if (in_array(self::MODULECOMPONENT_SUBMODULES, $components)) {
            // Modules are arrays, comparing them through the default SORT_STRING fails
            $ret = array_unique(
                $this->getSubmodules($module),
                SORT_REGULAR
            );
        }

        if (in_array(self::MODULECOMPONENT_DOMAINSWITCHINGSUBMODULES, $components)) {
            foreach ($this->getDomainSwitchingSubmodules($module) as $subcomponent_data_field => $subcomponent_modules) {
                $ret = array_values(
                    array_unique(
                        array_merge(
                            $subcomponent_modules,
                            $ret
                        ),
                        SORT_REGULAR
                    )
                );
            }
        }

        if (in_array(self::MODULECOMPONENT_CONDITIONALONDATAFIELDSUBMODULES, $components)) {
            // Modules are arrays, comparing them through the default SORT_STRING fails
            foreach ($this->getConditionalOnDataFieldSubmodules($module) as $data_field => $submodules) {
                $ret = array_unique(
                    array_merge(
                        $ret,
                        $submodules
                    ),
                    SORT_REGULAR
                );
            }
        }

        if (in_array(self::MODULECOMPONENT_CONDITIONALONDATAFIELDDOMAINSWITCHINGSUBMODULES, $components)) {
            foreach ($this->getConditionalOnDataFieldDomainSwitchingSubmodules($module) as $conditionDataField => $dataFieldTypeResolverOptionsConditionalSubmodules) {
                foreach ($dataFieldTypeResolverOptionsConditionalSubmodules as $conditionalDataField => $subcomponentModules) {
                    $ret = array_values(
                        array_unique(
                            array_merge(
                                $subcomponentModules,
                                $ret
                            ),
                            SORT_REGULAR
                        )
                    );
                }
            }
        }

        return $ret;
    }
}

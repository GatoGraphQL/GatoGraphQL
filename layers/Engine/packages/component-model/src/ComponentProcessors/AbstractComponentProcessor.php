<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Constants\DataLoading;
use PoP\ComponentModel\Constants\DataSources;
use PoP\ComponentModel\Constants\Props;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ConditionalLeafComponentField;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ConditionalRelationalComponentField;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentField;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ComponentFieldInterface;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalComponentField;
use PoP\ComponentModel\HelperServices\DataloadHelperServiceInterface;
use PoP\ComponentModel\HelperServices\RequestHelperServiceInterface;
use PoP\ComponentModel\ComponentFiltering\ComponentFilterManagerInterface;
use PoP\ComponentModel\ComponentFilters\ComponentPaths;
use PoP\ComponentModel\ComponentPath\ComponentPathHelpersInterface;
use PoP\ComponentModel\Modules\ComponentHelpersInterface;
use PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Root\App;
use PoP\Root\Module as RootModule;
use PoP\Root\ModuleConfiguration as RootModuleConfiguration;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractComponentProcessor implements ComponentProcessorInterface
{
    use ComponentPathProcessorTrait;
    use BasicServiceTrait;

    public final const HOOK_INIT_MODEL_PROPS = __CLASS__ . ':initModelProps';
    public final const HOOK_INIT_REQUEST_PROPS = __CLASS__ . ':initRequestProps';
    public final const HOOK_ADD_HEADDATASETCOMPONENT_DATAPROPERTIES = __CLASS__ . ':addHeaddatasetcomponentDataProperties';

    protected const COMPONENTELEMENT_SUBCOMPONENTS = 'subcomponents';
    protected const COMPONENTELEMENT_RELATIONALSUBCOMPONENTS = 'relational-subcomponents';
    protected const COMPONENTELEMENT_CONDITIONALONDATAFIELDSUBCOMPONENTS = 'conditional-on-data-field-subcomponents';
    protected const COMPONENTELEMENT_CONDITIONALONDATAFIELDRELATIONALSUBCOMPONENTS = 'conditional-on-data-field-relational-subcomponents';

    private ?FieldQueryInterpreterInterface $fieldQueryInterpreter = null;
    private ?ComponentPathHelpersInterface $componentPathHelpers = null;
    private ?ComponentFilterManagerInterface $componentFilterManager = null;
    private ?ComponentProcessorManagerInterface $componentProcessorManager = null;
    private ?NameResolverInterface $nameResolver = null;
    private ?DataloadHelperServiceInterface $dataloadHelperService = null;
    private ?RequestHelperServiceInterface $requestHelperService = null;
    private ?ComponentPaths $componentPaths = null;
    private ?ComponentHelpersInterface $componentHelpers = null;

    final public function setFieldQueryInterpreter(FieldQueryInterpreterInterface $fieldQueryInterpreter): void
    {
        $this->fieldQueryInterpreter = $fieldQueryInterpreter;
    }
    final protected function getFieldQueryInterpreter(): FieldQueryInterpreterInterface
    {
        return $this->fieldQueryInterpreter ??= $this->instanceManager->getInstance(FieldQueryInterpreterInterface::class);
    }
    final public function setComponentPathHelpers(ComponentPathHelpersInterface $componentPathHelpers): void
    {
        $this->componentPathHelpers = $componentPathHelpers;
    }
    final protected function getComponentPathHelpers(): ComponentPathHelpersInterface
    {
        return $this->componentPathHelpers ??= $this->instanceManager->getInstance(ComponentPathHelpersInterface::class);
    }
    final public function setComponentFilterManager(ComponentFilterManagerInterface $componentFilterManager): void
    {
        $this->componentFilterManager = $componentFilterManager;
    }
    final protected function getComponentFilterManager(): ComponentFilterManagerInterface
    {
        return $this->componentFilterManager ??= $this->instanceManager->getInstance(ComponentFilterManagerInterface::class);
    }
    final public function setComponentProcessorManager(ComponentProcessorManagerInterface $componentProcessorManager): void
    {
        $this->componentProcessorManager = $componentProcessorManager;
    }
    final protected function getComponentProcessorManager(): ComponentProcessorManagerInterface
    {
        return $this->componentProcessorManager ??= $this->instanceManager->getInstance(ComponentProcessorManagerInterface::class);
    }
    final public function setNameResolver(NameResolverInterface $nameResolver): void
    {
        $this->nameResolver = $nameResolver;
    }
    final protected function getNameResolver(): NameResolverInterface
    {
        return $this->nameResolver ??= $this->instanceManager->getInstance(NameResolverInterface::class);
    }
    final public function setDataloadHelperService(DataloadHelperServiceInterface $dataloadHelperService): void
    {
        $this->dataloadHelperService = $dataloadHelperService;
    }
    final protected function getDataloadHelperService(): DataloadHelperServiceInterface
    {
        return $this->dataloadHelperService ??= $this->instanceManager->getInstance(DataloadHelperServiceInterface::class);
    }
    final public function setRequestHelperService(RequestHelperServiceInterface $requestHelperService): void
    {
        $this->requestHelperService = $requestHelperService;
    }
    final protected function getRequestHelperService(): RequestHelperServiceInterface
    {
        return $this->requestHelperService ??= $this->instanceManager->getInstance(RequestHelperServiceInterface::class);
    }
    final public function setComponentPaths(ComponentPaths $componentPaths): void
    {
        $this->componentPaths = $componentPaths;
    }
    final protected function getComponentPaths(): ComponentPaths
    {
        return $this->componentPaths ??= $this->instanceManager->getInstance(ComponentPaths::class);
    }
    final public function setComponentHelpers(ComponentHelpersInterface $componentHelpers): void
    {
        $this->componentHelpers = $componentHelpers;
    }
    final protected function getComponentHelpers(): ComponentHelpersInterface
    {
        return $this->componentHelpers ??= $this->instanceManager->getInstance(ComponentHelpersInterface::class);
    }

    public function getSubcomponents(array $component): array
    {
        return [];
    }

    final public function getAllSubcomponents(array $component): array
    {
        return $this->getSubcomponentsByGroup($component);
    }

    // public function getNature(array $component)
    // {
    //     return null;
    // }

    //-------------------------------------------------
    // New PUBLIC Functions: Atts
    //-------------------------------------------------

    public function executeInitPropsComponentTree(callable $eval_self_fn, callable $get_props_for_descendant_components_fn, callable $get_props_for_descendant_datasetcomponents_fn, string $propagate_fn, array $component, array &$props, $wildcard_props_to_propagate, $targetted_props_to_propagate): void
    {
        // Convert the component to its string representation to access it in the array
        $componentFullName = $this->getComponentHelpers()->getComponentFullName($component);

        // Initialize. If this component had been added props, then use them already
        // 1st element to merge: the general props for this component passed down the line
        // 2nd element to merge: the props set exactly to the path. They have more priority, that's why they are 2nd
        // It may contain more than one group (\PoP\ComponentModel\Constants\Props::ATTRIBUTES). Eg: maybe also POP_PROPS_JSMETHODS
        $props[$componentFullName] = array_merge_recursive(
            $targetted_props_to_propagate[$componentFullName] ?? array(),
            $props[$componentFullName] ?? array()
        );

        // The component must be at the head of the $props array passed to all `initModelProps`, so that function `getPathHeadComponent` can work
        $component_props = array(
            $componentFullName => &$props[$componentFullName],
        );

        // If ancestor components set general props, or props targetted at this current component, then add them to the current component props
        foreach ($wildcard_props_to_propagate as $key => $value) {
            $this->setProp($component, $component_props, $key, $value);
        }

        // Before initiating the current level, set the children attributes on the array, so that doing ->setProp, ->appendProp, etc, keeps working
        $component_props[$componentFullName][Props::DESCENDANT_ATTRIBUTES] = array_merge(
            $component_props[$componentFullName][Props::DESCENDANT_ATTRIBUTES] ?? array(),
            $targetted_props_to_propagate ?? array()
        );

        // Initiate the current level.
        $eval_self_fn($component, $component_props);

        // Immediately after initiating the current level, extract all child attributes out from the $props, and place it on the other variable
        $targetted_props_to_propagate = $component_props[$componentFullName][Props::DESCENDANT_ATTRIBUTES];
        unset($component_props[$componentFullName][Props::DESCENDANT_ATTRIBUTES]);

        // But because components can't repeat themselves down the line (or it would generate an infinite loop), then can remove the current component from the targeted props
        unset($targetted_props_to_propagate[$componentFullName]);

        // Allow the $component to add general props for all its descendant components
        $wildcard_props_to_propagate = array_merge(
            $wildcard_props_to_propagate,
            $get_props_for_descendant_components_fn($component, $component_props)
        );

        // Propagate
        $subComponents = $this->getAllSubcomponents($component);
        $subComponents = $this->getComponentFilterManager()->removeExcludedSubcomponents($component, $subComponents);

        // This function must be called always, to register matching components into requestmeta.filtercomponents even when the component has no subcomponents
        $this->getComponentFilterManager()->prepareForPropagation($component, $props);
        if ($subComponents) {
            $props[$componentFullName][Props::SUBCOMPONENTS] = $props[$componentFullName][Props::SUBCOMPONENTS] ?? array();
            foreach ($subComponents as $subComponent) {
                $subcomponent_processor = $this->getComponentProcessorManager()->getProcessor($subComponent);
                $subcomponent_wildcard_props_to_propagate = $wildcard_props_to_propagate;

                // If the subcomponent belongs to the same dataset, then set the shared attributies for the same-dataset components
                if (!$subcomponent_processor->startDataloadingSection($subComponent)) {
                    $subcomponent_wildcard_props_to_propagate = array_merge(
                        $subcomponent_wildcard_props_to_propagate,
                        $get_props_for_descendant_datasetcomponents_fn($component, $component_props)
                    );
                }

                $subcomponent_processor->$propagate_fn($subComponent, $props[$componentFullName][Props::SUBCOMPONENTS], $subcomponent_wildcard_props_to_propagate, $targetted_props_to_propagate);
            }
        }
        $this->getComponentFilterManager()->restoreFromPropagation($component, $props);
    }

    public function initModelPropsComponentTree(array $component, array &$props, array $wildcard_props_to_propagate, array $targetted_props_to_propagate): void
    {
        $this->executeInitPropsComponentTree($this->initModelProps(...), $this->getModelPropsForDescendantComponents(...), $this->getModelPropsForDescendantDatasetComponents(...), __FUNCTION__, $component, $props, $wildcard_props_to_propagate, $targetted_props_to_propagate);
    }

    public function getModelPropsForDescendantComponents(array $component, array &$props): array
    {
        $ret = array();

        // If we set property 'skip-data-load' on any component, not just dataset, spread it down to its children so it reaches its contained dataset subcomponents
        $skip_data_load = $this->getProp($component, $props, 'skip-data-load');
        if (!is_null($skip_data_load)) {
            $ret['skip-data-load'] = $skip_data_load;
        }

        // Property 'ignore-request-params' => true makes a dataloading component not get values from the request
        $ignore_params_from_request = $this->getProp($component, $props, 'ignore-request-params');
        if (!is_null($ignore_params_from_request)) {
            $ret['ignore-request-params'] = $ignore_params_from_request;
        }

        return $ret;
    }

    public function getModelPropsForDescendantDatasetComponents(array $component, array &$props): array
    {
        return [];
    }

    public function initModelProps(array $component, array &$props): void
    {
        // Set property "succeeding-typeResolver" on every component, so they know which is their typeResolver, needed to calculate the subcomponent data-fields when using typeResolver "*"
        if ($relationalTypeResolver = $this->getRelationalTypeResolver($component)) {
            $this->setProp($component, $props, 'succeeding-typeResolver', $relationalTypeResolver);
        } else {
            // Get the prop assigned to the component by its ancestor
            $relationalTypeResolver = $this->getProp($component, $props, 'succeeding-typeResolver');
        }
        if ($relationalTypeResolver !== null) {
            // Set the property "succeeding-typeResolver" on all descendants: the same typeResolver for all subcomponents, and the explicit one (or get the default one for "*") for relational objects
            foreach ($this->getSubcomponents($component) as $subComponent) {
                $this->setProp($subComponent, $props, 'succeeding-typeResolver', $relationalTypeResolver);
            }
            foreach ($this->getRelationalComponentFields($component) as $relationalComponentField) {
                // @todo Pass the ComponentField directly, do not convert to string first
                $subcomponent_data_field = $relationalComponentField->asFieldOutputQueryString();
                if ($subcomponent_typeResolver = $this->getDataloadHelperService()->getTypeResolverFromSubcomponentDataField($relationalTypeResolver, $subcomponent_data_field)) {
                    foreach ($relationalComponentField->getNestedComponents() as $subcomponent_component) {
                        $this->setProp($subcomponent_component, $props, 'succeeding-typeResolver', $subcomponent_typeResolver);
                    }
                }
            }
            foreach ($this->getConditionalLeafComponentFields($component) as $conditionalLeafComponentField) {
                foreach ($conditionalLeafComponentField->getConditionalNestedComponents() as $conditionalSubcomponent) {
                    $this->setProp($conditionalSubcomponent, $props, 'succeeding-typeResolver', $relationalTypeResolver);
                }
            }
            foreach ($this->getConditionalRelationalComponentFields($component) as $conditionalRelationalComponentField) {
                foreach ($conditionalRelationalComponentField->getRelationalComponentFields() as $relationalComponentField) {
                    $conditionalDataField = $relationalComponentField->asFieldOutputQueryString();
                    if ($subcomponentTypeResolver = $this->getDataloadHelperService()->getTypeResolverFromSubcomponentDataField($relationalTypeResolver, $conditionalDataField)) {
                        foreach ($relationalComponentField->getNestedComponents() as $conditionalSubcomponent) {
                            $this->setProp($conditionalSubcomponent, $props, 'succeeding-typeResolver', $subcomponentTypeResolver);
                        }
                    }
                }
            }
        }

        /**
         * Allow to add more stuff
         */
        App::doAction(
            self::HOOK_INIT_MODEL_PROPS,
            array(&$props),
            $component,
            $this
        );
    }

    public function initRequestPropsComponentTree(array $component, array &$props, array $wildcard_props_to_propagate, array $targetted_props_to_propagate): void
    {
        $this->executeInitPropsComponentTree($this->initRequestProps(...), $this->getRequestPropsForDescendantComponents(...), $this->getRequestPropsForDescendantDatasetComponents(...), __FUNCTION__, $component, $props, $wildcard_props_to_propagate, $targetted_props_to_propagate);
    }

    public function getRequestPropsForDescendantComponents(array $component, array &$props): array
    {
        return [];
    }

    public function getRequestPropsForDescendantDatasetComponents(array $component, array &$props): array
    {
        return [];
    }

    public function initRequestProps(array $component, array &$props): void
    {
        /**
         * Allow to add more stuff
         */
        App::doAction(
            self::HOOK_INIT_REQUEST_PROPS,
            array(&$props),
            $component,
            $this
        );
    }

    //-------------------------------------------------
    // PRIVATE Functions: Atts
    //-------------------------------------------------

    private function getPathHeadComponent(array &$props): string
    {
        // From the root of the $props we obtain the current component
        reset($props);
        return (string)key($props);
    }

    private function isComponentPath(array $component_or_componentPath): bool
    {
        // $component_or_componentPath can be either a single component (the current one, or its descendant), or a targetted path of components
        // Because a component is itself represented as an array, to know which is the case, we must ask if it is:
        // - an array => single component
        // - an array of arrays (component path)
        return is_array($component_or_componentPath[0]);
    }

    private function isDescendantModule(array $component_or_componentPath, array &$props): bool
    {
        // If it is not an array of arrays, then this array is directly the component, or the descendant component on which to set the property
        if (!$this->isComponentPath($component_or_componentPath)) {
            // From the root of the $props we obtain the current component
            $componentFullName = $this->getPathHeadComponent($props);

            // If the component were we are adding the att, is this same component, then we are already at the path
            // If it is not, then go down one level to that component
            return ($componentFullName !== $this->getComponentHelpers()->getComponentFullName($component_or_componentPath));
        }

        return false;
    }

    protected function getComponentPath(array $component_or_componentPath, array &$props): array
    {
        // This function is used to get the path to the current component, or to a component path
        // It is not used for getting the path to a single component which is not the current one (since we do not know its path)
        if (!$props) {
            return [];
        }

        // From the root of the $props we obtain the current component
        $componentFullName = $this->getPathHeadComponent($props);

        // Calculate the path to iterate down. It always starts with the current component
        $ret = array($componentFullName);

        // If it is an array, then we're passing the path to find the component to which to add the att
        if ($this->isComponentPath($component_or_componentPath)) {
            $ret = array_merge(
                $ret,
                array_map(
                    $this->getComponentHelpers()->getComponentFullName(...),
                    $component_or_componentPath
                )
            );
        }

        return $ret;
    }

    protected function addPropGroupField(string $group, array $component_or_componentPath, array &$props, $field, $value, array $starting_from_componentPath = array(), array $options = array()): void
    {
        // Iterate down to the subcomponent, which must be an array of components
        if ($starting_from_componentPath) {
            // Convert it to string
            $startingFromComponentPathFullNames = array_map(
                $this->getComponentHelpers()->getComponentFullName(...),
                $starting_from_componentPath
            );

            // Attach the current component, which is not included on "starting_from", to step down this level too
            $componentFullName = $this->getPathHeadComponent($props);
            array_unshift($startingFromComponentPathFullNames, $componentFullName);

            // Descend into the path to find the component for which to add the att
            $component_props = &$props;
            foreach ($startingFromComponentPathFullNames as $pathlevelComponentFullName) {
                $last_component_props = &$component_props;
                $lastComponentFullName = $pathlevelComponentFullName;

                $component_props[$pathlevelComponentFullName][Props::SUBCOMPONENTS] = $component_props[$pathlevelComponentFullName][Props::SUBCOMPONENTS] ?? array();
                $component_props = &$component_props[$pathlevelComponentFullName][Props::SUBCOMPONENTS];
            }

            // This is the new $props, so it starts from here
            // Save the current $props, and restore later, to make sure this array has only one key, otherwise it will not work
            $current_props = $props;
            $props = array(
                $lastComponentFullName => &$last_component_props[$lastComponentFullName]
            );
        }

        // If the component is a string, there are 2 possibilities: either it is the current component or not
        // If it is not, then it is a descendant component, which will appear at some point down the path.
        // For that case, simply save it under some other entry, from where it will propagate the props later on in `initModelPropsComponentTree`
        if ($this->isDescendantModule($component_or_componentPath, $props)) {
            // It is a child component
            $att_component = $component_or_componentPath;
            $attComponentFullName = $this->getComponentHelpers()->getComponentFullName($att_component);

            // From the root of the $props we obtain the current component
            $componentFullName = $this->getPathHeadComponent($props);

            // Set the child attributes under a different entry
            $props[$componentFullName][Props::DESCENDANT_ATTRIBUTES] = $props[$componentFullName][Props::DESCENDANT_ATTRIBUTES] ?? array();
            $component_props = &$props[$componentFullName][Props::DESCENDANT_ATTRIBUTES];
        } else {
            // Calculate the path to iterate down
            $componentPath = $this->getComponentPath($component_or_componentPath, $props);

            // Extract the lastlevel, that's the component to with to add the att
            $attComponentFullName = array_pop($componentPath);

            // Descend into the path to find the component for which to add the att
            $component_props = &$props;
            foreach ($componentPath as $pathlevelFullName) {
                $component_props[$pathlevelFullName][Props::SUBCOMPONENTS] = $component_props[$pathlevelFullName][Props::SUBCOMPONENTS] ?? array();
                $component_props = &$component_props[$pathlevelFullName][Props::SUBCOMPONENTS];
            }
        }

        // Now can proceed to add the att
        $component_props[$attComponentFullName][$group] = $component_props[$attComponentFullName][$group] ?? array();

        if ($options['append'] ?? null) {
            $component_props[$attComponentFullName][$group][$field] = $component_props[$attComponentFullName][$group][$field] ?? '';
            $component_props[$attComponentFullName][$group][$field] .= ' ' . $value;
        } elseif ($options['array'] ?? null) {
            $component_props[$attComponentFullName][$group][$field] = $component_props[$attComponentFullName][$group][$field] ?? array();
            if ($options['merge'] ?? null) {
                $component_props[$attComponentFullName][$group][$field] = array_merge(
                    $component_props[$attComponentFullName][$group][$field],
                    $value
                );
            } elseif ($options['merge-iterate-key'] ?? null) {
                foreach ($value as $value_key => $value_value) {
                    if (!$component_props[$attComponentFullName][$group][$field][$value_key]) {
                        $component_props[$attComponentFullName][$group][$field][$value_key] = array();
                    }
                    // Doing array_unique, because in the NotificationPreviewLayout, different layouts might impose a JS down the road, many times, and these get duplicated
                    $component_props[$attComponentFullName][$group][$field][$value_key] = array_unique(
                        array_merge(
                            $component_props[$attComponentFullName][$group][$field][$value_key],
                            $value_value
                        )
                    );
                }
            } elseif ($options['push'] ?? null) {
                array_push($component_props[$attComponentFullName][$group][$field], $value);
            }
        } else {
            // If already set, then do nothing
            if (!isset($component_props[$attComponentFullName][$group][$field])) {
                $component_props[$attComponentFullName][$group][$field] = $value;
            }
        }

        // Restore original $props
        if ($starting_from_componentPath) {
            $props = $current_props;
        }
    }
    protected function getPropGroupField(string $group, array $component, array &$props, string $field, array $starting_from_componentPath = array()): mixed
    {
        $group = $this->getPropGroup($group, $component, $props, $starting_from_componentPath);
        return $group[$field] ?? null;
    }
    protected function getPropGroup(string $group, array $component, array &$props, array $starting_from_componentPath = array()): array
    {
        if (!$props) {
            return [];
        }

        $component_props = &$props;
        foreach ($starting_from_componentPath as $pathlevelModule) {
            $pathlevelComponentFullName = $this->getComponentHelpers()->getComponentFullName($pathlevelModule);
            $component_props = &$component_props[$pathlevelComponentFullName][Props::SUBCOMPONENTS];
        }

        $componentFullName = $this->getComponentHelpers()->getComponentFullName($component);
        return $component_props[$componentFullName][$group] ?? array();
    }
    protected function addGroupProp(string $group, array $component_or_componentPath, array &$props, string $field, $value, array $starting_from_componentPath = array()): void
    {
        $this->addPropGroupField($group, $component_or_componentPath, $props, $field, $value, $starting_from_componentPath);
    }
    public function setProp(array $component_or_componentPath, array &$props, string $field, $value, array $starting_from_componentPath = array()): void
    {
        $this->addGroupProp(Props::ATTRIBUTES, $component_or_componentPath, $props, $field, $value, $starting_from_componentPath);
    }
    public function appendGroupProp(string $group, array $component_or_componentPath, array &$props, string $field, $value, array $starting_from_componentPath = array()): void
    {
        $this->addPropGroupField($group, $component_or_componentPath, $props, $field, $value, $starting_from_componentPath, array('append' => true));
    }
    public function appendProp(array $component_or_componentPath, array &$props, string $field, $value, array $starting_from_componentPath = array()): void
    {
        $this->appendGroupProp(Props::ATTRIBUTES, $component_or_componentPath, $props, $field, $value, $starting_from_componentPath);
    }
    public function mergeGroupProp(string $group, array $component_or_componentPath, array &$props, string $field, $value, array $starting_from_componentPath = array()): void
    {
        $this->addPropGroupField($group, $component_or_componentPath, $props, $field, $value, $starting_from_componentPath, array('array' => true, 'merge' => true));
    }
    public function mergeProp(array $component_or_componentPath, array &$props, string $field, $value, array $starting_from_componentPath = array()): void
    {
        $this->mergeGroupProp(Props::ATTRIBUTES, $component_or_componentPath, $props, $field, $value, $starting_from_componentPath);
    }
    public function getGroupProp(string $group, array $component, array &$props, string $field, array $starting_from_componentPath = array()): mixed
    {
        return $this->getPropGroupField($group, $component, $props, $field, $starting_from_componentPath);
    }
    public function getProp(array $component, array &$props, string $field, array $starting_from_componentPath = array()): mixed
    {
        return $this->getGroupProp(Props::ATTRIBUTES, $component, $props, $field, $starting_from_componentPath);
    }
    public function mergeGroupIterateKeyProp(string $group, array $component_or_componentPath, array &$props, string $field, $value, array $starting_from_componentPath = array()): void
    {
        $this->addPropGroupField($group, $component_or_componentPath, $props, $field, $value, $starting_from_componentPath, array('array' => true, 'merge-iterate-key' => true));
    }
    public function mergeIterateKeyProp(array $component_or_componentPath, array &$props, string $field, $value, array $starting_from_componentPath = array()): void
    {
        $this->mergeGroupIterateKeyProp(Props::ATTRIBUTES, $component_or_componentPath, $props, $field, $value, $starting_from_componentPath);
    }
    public function pushProp(string $group, array $component_or_componentPath, array &$props, string $field, $value, array $starting_from_componentPath = array()): void
    {
        $this->addPropGroupField($group, $component_or_componentPath, $props, $field, $value, $starting_from_componentPath, array('array' => true, 'push' => true));
    }

    //-------------------------------------------------
    // New PUBLIC Functions: Model Static Settings
    //-------------------------------------------------

    public function getDatabaseKeys(array $component, array &$props): array
    {
        $ret = array();
        if ($relationalTypeResolver = $this->getRelationalTypeResolver($component)) {
            if ($dbkey = $relationalTypeResolver->getTypeOutputDBKey()) {
                // Place it under "id" because it is for fetching the current object from the DB, which is found through dbObject.id
                $ret['id'] = $dbkey;
            }
        }

        // This prop is set for both dataloading and non-dataloading components
        if ($relationalTypeResolver = $this->getProp($component, $props, 'succeeding-typeResolver')) {
            foreach ($this->getRelationalComponentFields($component) as $relationalComponentField) {
                // @todo Pass the ComponentField directly, do not convert to string first
                $subcomponent_data_field = $relationalComponentField->asFieldOutputQueryString();
                // If passing a subcomponent fieldname that doesn't exist to the API, then $subcomponent_typeResolver_class will be empty
                if ($subcomponent_typeResolver = $this->getDataloadHelperService()->getTypeResolverFromSubcomponentDataField($relationalTypeResolver, $subcomponent_data_field)) {
                    // If there is an alias, store the results under this. Otherwise, on the fieldName+fieldArgs
                    // @todo: Check if it should use `getUniqueFieldOutputKeyByTypeResolverClass`, or pass some $object to `getUniqueFieldOutputKey`, or what
                    // @see https://github.com/leoloso/PoP/issues/1050
                    $subcomponent_data_field_outputkey = $this->getFieldQueryInterpreter()->getFieldOutputKey($subcomponent_data_field);
                    $ret[$subcomponent_data_field_outputkey] = $this->getFieldQueryInterpreter()->getTargetObjectTypeUniqueFieldOutputKeys($relationalTypeResolver, $subcomponent_data_field);
                }
            }
            foreach ($this->getConditionalRelationalComponentFields($component) as $conditionalRelationalComponentField) {
                foreach ($conditionalRelationalComponentField->getRelationalComponentFields() as $relationalComponentField) {
                    $conditionalDataField = $relationalComponentField->asFieldOutputQueryString();
                    // If passing a subcomponent fieldname that doesn't exist to the API, then $subcomponentTypeResolverClass will be empty
                    if ($subcomponent_typeResolver = $this->getDataloadHelperService()->getTypeResolverFromSubcomponentDataField($relationalTypeResolver, $conditionalDataField)) {
                        // If there is an alias, store the results under this. Otherwise, on the fieldName+fieldArgs
                        // @todo: Check if it should use `getUniqueFieldOutputKeyByTypeResolverClass`, or pass some $object to `getUniqueFieldOutputKey`, or what
                        // @see https://github.com/leoloso/PoP/issues/1050
                        $subcomponent_data_field_outputkey = $this->getFieldQueryInterpreter()->getFieldOutputKey($conditionalDataField);
                        $ret[$subcomponent_data_field_outputkey] = $this->getFieldQueryInterpreter()->getTargetObjectTypeUniqueFieldOutputKeys($relationalTypeResolver, $conditionalDataField);
                    }
                }
            }
        }

        return $ret;
    }

    //-------------------------------------------------
    // New PUBLIC Functions: Model Static Settings
    //-------------------------------------------------

    public function getImmutableSettingsDatasetcomponentTree(array $component, array &$props): array
    {
        $options = array(
            'only-execute-on-dataloading-components' => true,
        );
        return $this->executeOnSelfAndPropagateToComponents('getImmutableDatasetsettings', __FUNCTION__, $component, $props, true, $options);
    }

    public function getImmutableDatasetsettings(array $component, array &$props): array
    {
        $ret = array();

        if ($database_keys = $this->getDatasetDatabaseKeys($component, $props)) {
            $ret['dbkeys'] = $database_keys;
        }

        return $ret;
    }

    public function addToDatasetDatabaseKeys(array $component, array &$props, array $path, array &$ret): void
    {
        // Add the current component's dbkeys
        if ($relationalTypeResolver = $this->getRelationalTypeResolver($component)) {
            $dbkeys = $this->getDatabaseKeys($component, $props);
            foreach ($dbkeys as $field => $dbkey) {
                // @todo: Check if it should use `getUniqueFieldOutputKeyByTypeResolverClass`, or pass some $object to `getUniqueFieldOutputKey`, or what
                // @see https://github.com/leoloso/PoP/issues/1050
                $field_outputkey = $this->getFieldQueryInterpreter()->getFieldOutputKey($field);
                $ret[implode('.', array_merge($path, [$field_outputkey]))] = $dbkey;
            }
        }

        // Propagate to all subcomponents which have no typeResolver
        $componentFullName = $this->getComponentHelpers()->getComponentFullName($component);

        if ($relationalTypeResolver = $this->getProp($component, $props, 'succeeding-typeResolver')) {
            $this->getComponentFilterManager()->prepareForPropagation($component, $props);
            foreach ($this->getRelationalComponentFields($component) as $relationalComponentField) {
                // @todo Pass the ComponentField directly, do not convert to string first
                $subcomponent_data_field = $relationalComponentField->asFieldOutputQueryString();
                // @todo: Check if it should use `getUniqueFieldOutputKeyByTypeResolverClass`, or pass some $object to `getUniqueFieldOutputKey`, or what
                // @see https://github.com/leoloso/PoP/issues/1050
                $subcomponent_data_field_outputkey = $this->getFieldQueryInterpreter()->getFieldOutputKey($subcomponent_data_field);
                // Only components which do not load data
                $subcomponent_components = array_filter(
                    $relationalComponentField->getNestedComponents(),
                    function ($subComponent) {
                        return !$this->getComponentProcessorManager()->getProcessor($subComponent)->startDataloadingSection($subComponent);
                    }
                );
                foreach ($subcomponent_components as $subcomponent_component) {
                    $this->getComponentProcessorManager()->getProcessor($subcomponent_component)->addToDatasetDatabaseKeys($subcomponent_component, $props[$componentFullName][Props::SUBCOMPONENTS], array_merge($path, [$subcomponent_data_field_outputkey]), $ret);
                }
            }
            foreach ($this->getConditionalRelationalComponentFields($component) as $conditionalRelationalComponentField) {
                foreach ($conditionalRelationalComponentField->getRelationalComponentFields() as $relationalComponentField) {
                    $conditionalDataField = $relationalComponentField->asFieldOutputQueryString();
                    // @todo: Check if it should use `getUniqueFieldOutputKeyByTypeResolverClass`, or pass some $object to `getUniqueFieldOutputKey`, or what
                    // @see https://github.com/leoloso/PoP/issues/1050
                    $subcomponent_data_field_outputkey = $this->getFieldQueryInterpreter()->getFieldOutputKey($conditionalDataField);
                    // Only components which do not load data
                    $subcomponent_components = array_filter(
                        $relationalComponentField->getNestedComponents(),
                        function ($subComponent) {
                            return !$this->getComponentProcessorManager()->getProcessor($subComponent)->startDataloadingSection($subComponent);
                        }
                    );
                    foreach ($subcomponent_components as $subcomponent_component) {
                        $this->getComponentProcessorManager()->getProcessor($subcomponent_component)->addToDatasetDatabaseKeys($subcomponent_component, $props[$componentFullName][Props::SUBCOMPONENTS], array_merge($path, [$subcomponent_data_field_outputkey]), $ret);
                    }
                }
            }

            // Only components which do not load data
            $subComponents = array_filter($this->getSubcomponents($component), function ($subComponent) {
                return !$this->getComponentProcessorManager()->getProcessor($subComponent)->startDataloadingSection($subComponent);
            });
            foreach ($subComponents as $subComponent) {
                $this->getComponentProcessorManager()->getProcessor($subComponent)->addToDatasetDatabaseKeys($subComponent, $props[$componentFullName][Props::SUBCOMPONENTS], $path, $ret);
            }
            $this->getComponentFilterManager()->restoreFromPropagation($component, $props);
        }
    }

    public function getDatasetDatabaseKeys(array $component, array &$props): array
    {
        $ret = array();
        $this->addToDatasetDatabaseKeys($component, $props, array(), $ret);
        return $ret;
    }

    //-------------------------------------------------
    // New PUBLIC Functions: Static + Stateful Data
    //-------------------------------------------------

    public function getDatasource(array $component, array &$props): string
    {
        // Each component can only return one piece of data, and it must be indicated if it static or mutableonrequest
        // Retrieving only 1 piece is needed so that its children do not get confused what data their getLeafComponentFields applies to
        return DataSources::MUTABLEONREQUEST;
    }

    public function getObjectIDOrIDs(array $component, array &$props, &$data_properties): string | int | array | null
    {
        return [];
    }

    public function getRelationalTypeResolver(array $component): ?RelationalTypeResolverInterface
    {
        return null;
    }

    public function doesComponentLoadData(array $component): bool
    {
        return $this->getRelationalTypeResolver($component) !== null;
    }

    public function startDataloadingSection(array $component): bool
    {
        return $this->doesComponentLoadData($component);
    }

    public function getComponentMutationResolverBridge(array $component): ?ComponentMutationResolverBridgeInterface
    {
        return null;
    }

    public function prepareDataPropertiesAfterMutationExecution(array $component, array &$props, array &$data_properties): void
    {
        // Do nothing
    }

    /**
     * @return LeafComponentField[]
     */
    public function getLeafComponentFields(array $component, array &$props): array
    {
        return [];
    }

    /**
     * @return RelationalComponentField[]
     */
    public function getRelationalComponentFields(array $component): array
    {
        return [];
    }

    /**
     * @return ConditionalLeafComponentField[]
     */
    public function getConditionalLeafComponentFields(array $component): array
    {
        return [];
    }

    /**
     * @return ConditionalRelationalComponentField[]
     */
    public function getConditionalRelationalComponentFields(array $component): array
    {
        return [];
    }

    //-------------------------------------------------
    // New PUBLIC Functions: Data Properties
    //-------------------------------------------------

    public function getImmutableDataPropertiesDatasetcomponentTree(array $component, array &$props): array
    {
        // The data-properties start on a dataloading component, and finish on the next dataloding component down the line
        // This way, we can collect all the data-fields that the component will need to load for its dbobjects
        return $this->executeOnSelfAndPropagateToComponents('getImmutableDataPropertiesDatasetcomponentTreeFullsection', __FUNCTION__, $component, $props, false);
    }

    public function getImmutableDataPropertiesDatasetcomponentTreeFullsection(array $component, array &$props): array
    {
        $ret = array();

        // Only if this component loads data => We are at the head nodule of the dataset section
        if ($this->doesComponentLoadData($component)) {
            // Load the data-fields from all components inside this section
            // And then, only for the top node, add its extra properties
            $properties = array_merge(
                $this->getDatasetcomponentTreeSectionFlattenedDataFields($component, $props),
                $this->getImmutableHeaddatasetcomponentDataProperties($component, $props)
            );

            if ($properties) {
                $ret[DataLoading::DATA_PROPERTIES] = $properties;
            }
        }

        return $ret;
    }

    public function getDatasetcomponentTreeSectionFlattenedDataFields(array $component, array &$props): array
    {
        $ret = array();

        /**
         * Calculate the data-fields from merging them with the
         * subcomponent components' keys, which are data-fields too.
         *
         * Doing `array_unique` works because the ComponentFields
         * implement __toString() as the fieldOutputQueryString,
         * so if two fields are the same, we need to process
         * one AST element only.
         */
        if (
            /** @var ComponentFieldInterface[] */
            $astComponentFields = array_unique(
                array_merge(
                    $this->getLeafComponentFields($component, $props),
                    $this->getRelationalComponentFields($component),
                    $this->getConditionalLeafComponentFields($component),
                    $this->getConditionalRelationalComponentFields($component),
                )
            )
        ) {
            /**
             * @todo Temporarily calling ->asQueryString, must work with AST directly!
             */
            $data_fields = [];
            foreach ($astComponentFields as $astComponentField) {
                $data_fields[] = $astComponentField->asFieldOutputQueryString();
            }
            $ret['data-fields'] = $data_fields;
        }

        // Propagate down to the components
        $this->flattenDatasetcomponentTreeDataProperties(__FUNCTION__, $ret, $component, $props);

        // Propagate down to the subcomponent components
        $this->flattenRelationalDBObjectDataProperties(__FUNCTION__, $ret, $component, $props);

        return $ret;
    }

    public function getDatasetcomponentTreeSectionFlattenedComponents(array $component): array
    {
        $ret = [];

        $this->addDatasetcomponentTreeSectionFlattenedComponents($ret, $component);

        return array_values(
            array_unique(
                $ret,
                SORT_REGULAR
            )
        );
    }

    public function addDatasetcomponentTreeSectionFlattenedComponents(&$ret, array $component): void
    {
        $ret[] = $component;

        // Propagate down to the components
        // $this->flattenDatasetcomponentTreeComponents(__FUNCTION__, $ret, $component);
        // Exclude the subcomponent components here
        if ($subComponents = $this->getComponentsToPropagateDataProperties($component)) {
            foreach ($subComponents as $subComponent) {
                $subcomponent_processor = $this->getComponentProcessorManager()->getProcessor($subComponent);

                // Propagate only if the subcomponent doesn't load data. If it does, this is the end of the data line, and the subcomponent is the beginning of a new datasetcomponentTree
                if (!$subcomponent_processor->startDataloadingSection($subComponent)) {
                    $subcomponent_processor->addDatasetcomponentTreeSectionFlattenedComponents($ret, $subComponent);
                }
            }
        }
    }

    // protected function flattenDatasetcomponentTreeComponents($propagate_fn, &$ret, array $component)
    // {
    //     // Exclude the subcomponent components here
    //     if ($subComponents = $this->getComponentsToPropagateDataProperties($component)) {
    //         foreach ($subComponents as $subComponent) {
    //             $subcomponent_processor = $this->getComponentProcessorManager()->getProcessor($subComponent);

    //             // Propagate only if the subcomponent doesn't have a typeResolver. If it does, this is the end of the data line, and the subcomponent is the beginning of a new datasetcomponentTree
    //             if (!$subcomponent_processor->startDataloadingSection($subComponent)) {
    //                 if ($subcomponent_ret = $subcomponent_processor->$propagate_fn($subComponent)) {
    //                     $ret = array_merge(
    //                         $ret,
    //                         $subcomponent_ret
    //                     );
    //                 }
    //             }
    //         }
    //     }
    // }

    public function getImmutableHeaddatasetcomponentDataProperties(array $component, array &$props): array
    {
        // By default return nothing at the last level
        $ret = array();

        // From the State property we find out if it's Static of Stateful
        $datasource = $this->getDatasource($component, $props);
        $ret[DataloadingConstants::DATASOURCE] = $datasource;

        // Add the properties below either as static or mutableonrequest
        if ($datasource == DataSources::IMMUTABLE) {
            $this->addHeaddatasetcomponentDataProperties($ret, $component, $props);
        }

        return $ret;
    }

    protected function addHeaddatasetcomponentDataProperties(&$ret, array $component, array &$props): void
    {
        /**
         * Allow to add more stuff
         */
        App::doAction(
            self::HOOK_ADD_HEADDATASETCOMPONENT_DATAPROPERTIES,
            array(&$ret),
            $component,
            array(&$props),
            $this
        );
    }

    public function getMutableonmodelDataPropertiesDatasetcomponentTree(array $component, array &$props): array
    {
        return $this->executeOnSelfAndPropagateToComponents('getMutableonmodelDataPropertiesDatasetcomponentTreeFullsection', __FUNCTION__, $component, $props, false);
    }

    public function getMutableonmodelDataPropertiesDatasetcomponentTreeFullsection(array $component, array &$props): array
    {
        $ret = array();

        // Only if this component loads data
        if ($this->doesComponentLoadData($component)) {
            $properties = $this->getMutableonmodelHeaddatasetcomponentDataProperties($component, $props);
            if ($properties) {
                $ret[DataLoading::DATA_PROPERTIES] = $properties;
            }
        }

        return $ret;
    }

    public function getMutableonmodelHeaddatasetcomponentDataProperties(array $component, array &$props): array
    {
        $ret = array();

        // Add the properties below either as static or mutableonrequest
        $datasource = $this->getDatasource($component, $props);
        if ($datasource == DataSources::MUTABLEONMODEL) {
            $this->addHeaddatasetcomponentDataProperties($ret, $component, $props);
        }

        // Fetch params from request?
        /** @var RootModuleConfiguration */
        $rootModuleConfiguration = App::getModule(RootModule::class)->getConfiguration();
        if (!$rootModuleConfiguration->enablePassingStateViaRequest()) {
            $ignore_params_from_request = true;
        } else {
            $ignore_params_from_request = $this->getProp($component, $props, 'ignore-request-params');
        }
        if ($ignore_params_from_request !== null) {
            $ret[DataloadingConstants::IGNOREREQUESTPARAMS] = $ignore_params_from_request;
        }

        return $ret;
    }

    public function getMutableonrequestDataPropertiesDatasetcomponentTree(array $component, array &$props): array
    {
        return $this->executeOnSelfAndPropagateToComponents('getMutableonrequestDataPropertiesDatasetcomponentTreeFullsection', __FUNCTION__, $component, $props, false);
    }

    public function getMutableonrequestDataPropertiesDatasetcomponentTreeFullsection(array $component, array &$props): array
    {
        $ret = array();

        // Only if this component loads data
        if ($this->doesComponentLoadData($component)) {
            // // Load the data-fields from all modules inside this section
            // // And then, only for the top node, add its extra properties
            // $properties = array_merge(
            //     $this->get_mutableonrequest_data_properties_datasetcomponentTree_section($component, $props),
            //     $this->getMutableonrequestHeaddatasetcomponentDataProperties($component, $props)
            // );
            $properties = $this->getMutableonrequestHeaddatasetcomponentDataProperties($component, $props);

            if ($properties) {
                $ret[DataLoading::DATA_PROPERTIES] = $properties;
            }
        }

        return $ret;
    }

    public function getMutableonrequestHeaddatasetcomponentDataProperties(array $component, array &$props): array
    {
        $ret = array();

        // Add the properties below either as static or mutableonrequest
        $datasource = $this->getDatasource($component, $props);
        if ($datasource == DataSources::MUTABLEONREQUEST) {
            $this->addHeaddatasetcomponentDataProperties($ret, $component, $props);
        }

        // When loading data or execution an action, check if to validate checkpoints?
        // This is in MUTABLEONREQUEST instead of STATIC because the checkpoints can change depending on doingPost()
        // (such as done to set-up checkpoint configuration for POP_USERSTANCE_ROUTE_ADDOREDITSTANCE, or within POPUSERLOGIN_CHECKPOINTCONFIGURATION_REQUIREUSERSTATEONDOINGPOST)
        if ($checkpoints = $this->getDataAccessCheckpoints($component, $props)) {
            $ret[DataLoading::DATA_ACCESS_CHECKPOINTS] = $checkpoints;
        }

        // To trigger the actionexecuter, its own checkpoints must be successful
        if ($checkpoints = $this->getActionExecutionCheckpoints($component, $props)) {
            $ret[DataLoading::ACTION_EXECUTION_CHECKPOINTS] = $checkpoints;
        }

        return $ret;
    }

    //-------------------------------------------------
    // New PUBLIC Functions: Data Feedback
    //-------------------------------------------------

    public function getDataFeedbackDatasetcomponentTree(array $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array
    {
        return $this->executeOnSelfAndPropagateToDatasetComponents('getDataFeedbackComponentTree', __FUNCTION__, $component, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);
    }

    public function getDataFeedbackComponentTree(array $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array
    {
        $ret = array();

        if ($feedback = $this->getDataFeedback($component, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)) {
            $ret[DataLoading::FEEDBACK] = $feedback;
        }

        return $ret;
    }

    public function getDataFeedback(array $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array
    {
        return [];
    }

    public function getDataFeedbackInterreferencedComponentPath(array $component, array &$props): ?array
    {
        return null;
    }

    //-------------------------------------------------
    // Background URLs
    //-------------------------------------------------

    public function getBackgroundurlsMergeddatasetcomponentTree(array $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $objectIDs): array
    {
        return $this->executeOnSelfAndMergeWithDatasetComponents('getBackgroundurls', __FUNCTION__, $component, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDs);
    }

    public function getBackgroundurls(array $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $objectIDs): array
    {
        return [];
    }

    //-------------------------------------------------
    // Dataset Meta
    //-------------------------------------------------

    public function getDatasetmeta(array $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbObjectIDOrIDs): array
    {
        return [];
    }

    //-------------------------------------------------
    // Others
    //-------------------------------------------------

    public function getDataAccessCheckpoints(array $component, array &$props): array
    {
        return [];
    }

    public function getActionExecutionCheckpoints(array $component, array &$props): array
    {
        return [];
    }

    public function shouldExecuteMutation(array $component, array &$props): bool
    {
        // By default, execute only if the component is targeted for execution and doing POST
        return 'POST' === App::server('REQUEST_METHOD') && App::getState('actionpath') === $this->getComponentPathHelpers()->getStringifiedModulePropagationCurrentPath($component);
    }

    public function getComponentsToPropagateDataProperties(array $component): array
    {
        return $this->getSubcomponentsByGroup(
            $component,
            array(
                self::COMPONENTELEMENT_SUBCOMPONENTS,
                self::COMPONENTELEMENT_CONDITIONALONDATAFIELDSUBCOMPONENTS,
            )
        );
    }

    protected function flattenDatasetcomponentTreeDataProperties($propagate_fn, &$ret, array $component, array &$props): void
    {
        $componentFullName = $this->getComponentHelpers()->getComponentFullName($component);

        // Exclude the subcomponent components here
        $this->getComponentFilterManager()->prepareForPropagation($component, $props);
        if ($subComponents = $this->getComponentsToPropagateDataProperties($component)) {
            // Calculate in 2 steps:
            // First step: The conditional-on-data-field-subcomponents must have their data-fields added under entry "conditional-data-fields"
            $conditionalLeafComponentFields = $this->getConditionalLeafComponentFields($component);
            $conditionalRelationalComponentFields = $this->getConditionalRelationalComponentFields($component);
            if ($conditionalLeafComponentFields !== [] || $conditionalRelationalComponentFields !== []) {
                $directSubcomponents = $this->getSubcomponents($component);
                $conditionalComponentFields = [];
                // Instead of assigning to $ret, first assign it to a temporary variable, so we can then replace 'data-fields' with 'conditional-data-fields' before merging to $ret
                foreach ($conditionalLeafComponentFields as $conditionalLeafComponentField) {
                    $conditionDataField = $conditionalLeafComponentField->asFieldOutputQueryString();
                    $conditionalSubcomponents = $conditionalLeafComponentField->getConditionalNestedComponents();
                    $conditionalComponentFields[$conditionDataField] = $conditionalSubcomponents;
                }
                foreach ($conditionalRelationalComponentFields as $conditionalRelationalComponentField) {
                    $conditionDataField = $conditionalRelationalComponentField->asFieldOutputQueryString();
                    $conditionalComponentFields[$conditionDataField] = [];
                    foreach ($conditionalRelationalComponentField->getRelationalComponentFields() as $subConditionalRelationalComponentField) {
                        $conditionalSubcomponents = $subConditionalRelationalComponentField->getNestedComponents();
                        $conditionalComponentFields[$conditionDataField] = array_merge(
                            $conditionalComponentFields[$conditionDataField],
                            $conditionalSubcomponents
                        );
                    }
                }
                foreach ($conditionalComponentFields as $conditionDataField => $conditionalSubcomponents) {
                    // Calculate those fields which are certainly to be propagated, and not part of the direct subcomponents
                    // Using this really ugly way because, for comparing components, using `array_diff` and `intersect` fail
                    for ($i = count($conditionalSubcomponents) - 1; $i >= 0; $i--) {
                        // If this subcomponent is also in the direct ones, then it's not conditional anymore
                        if (in_array($conditionalSubcomponents[$i], $directSubcomponents)) {
                            array_splice($conditionalSubcomponents, $i, 1);
                        }
                    }
                    foreach ($conditionalSubcomponents as $subComponent) {
                        $subcomponent_processor = $this->getComponentProcessorManager()->getProcessor($subComponent);

                        // Propagate only if the subcomponent doesn't load data. If it does, this is the end of the data line, and the subcomponent is the beginning of a new datasetcomponentTree
                        if (!$subcomponent_processor->startDataloadingSection($subComponent)) {
                            if ($subcomponent_ret = $subcomponent_processor->$propagate_fn($subComponent, $props[$componentFullName][Props::SUBCOMPONENTS])) {
                                // Chain the "data-fields" from the sublevels under the current "conditional-data-fields"
                                // Move from "data-fields" to "conditional-data-fields"
                                if ($subcomponent_ret['data-fields'] ?? null) {
                                    foreach ($subcomponent_ret['data-fields'] as $subcomponent_data_field) {
                                        $ret['conditional-data-fields'][$conditionDataField][$subcomponent_data_field] = [];
                                    }
                                    unset($subcomponent_ret['data-fields']);
                                }
                                // Chain the conditional-data-fields at the end of the one from this component
                                if ($subcomponent_ret['conditional-data-fields'] ?? null) {
                                    foreach ($subcomponent_ret['conditional-data-fields'] as $subcomponent_condition_data_field => $subcomponent_conditional_data_fields) {
                                        $ret['conditional-data-fields'][$conditionDataField][$subcomponent_condition_data_field] = array_merge(
                                            $ret['conditional-data-fields'][$conditionDataField][$subcomponent_condition_data_field] ?? [],
                                            $subcomponent_conditional_data_fields
                                        );
                                    }
                                    unset($subcomponent_ret['conditional-data-fields']);
                                }

                                // array_merge_recursive => data-fields from different sidebar-components can be integrated all together
                                $ret = array_merge_recursive(
                                    $ret,
                                    $subcomponent_ret
                                );
                            }
                        }
                    }

                    // Extract the conditional subcomponents from the rest of the subcomponents, which will be processed below
                    foreach ($conditionalSubcomponents as $conditionalSubcomponent) {
                        $pos = array_search($conditionalSubcomponent, $subComponents);
                        if ($pos !== false) {
                            array_splice($subComponents, $pos, 1);
                        }
                    }
                }
            }

            // Second step: all the other subcomponents can be calculated directly
            foreach ($subComponents as $subComponent) {
                $subcomponent_processor = $this->getComponentProcessorManager()->getProcessor($subComponent);

                // Propagate only if the subcomponent doesn't load data. If it does, this is the end of the data line, and the subcomponent is the beginning of a new datasetcomponentTree
                if (!$subcomponent_processor->startDataloadingSection($subComponent)) {
                    if ($subcomponent_ret = $subcomponent_processor->$propagate_fn($subComponent, $props[$componentFullName][Props::SUBCOMPONENTS])) {
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
        $this->getComponentFilterManager()->restoreFromPropagation($component, $props);
    }

    protected function flattenRelationalDBObjectDataProperties($propagate_fn, &$ret, array $component, array &$props): void
    {
        $componentFullName = $this->getComponentHelpers()->getComponentFullName($component);

        // Combine the direct and conditionalOnDataField components all together to iterate below
        $relationalSubcomponents = [];
        foreach ($this->getRelationalComponentFields($component) as $relationalComponentField) {
            // @todo Pass the ComponentField directly, do not convert to string first
            $subcomponent_data_field = $relationalComponentField->asFieldOutputQueryString();
            $relationalSubcomponents[$subcomponent_data_field] = $relationalComponentField->getNestedComponents();
        }
        foreach ($this->getConditionalRelationalComponentFields($component) as $conditionalRelationalComponentField) {
            foreach ($conditionalRelationalComponentField->getRelationalComponentFields() as $relationalComponentField) {
                $conditionalDataField = $relationalComponentField->asFieldOutputQueryString();
                $relationalSubcomponents[$conditionalDataField] = array_values(array_unique(array_merge(
                    $relationalSubcomponents[$conditionalDataField] ?? [],
                    $relationalComponentField->getNestedComponents()
                )));
            }
        }

        // If it has subcomponent components, integrate them under 'subcomponents'
        $this->getComponentFilterManager()->prepareForPropagation($component, $props);
        foreach ($relationalSubcomponents as $subcomponent_data_field => $subcomponent_components) {
            $subcomponent_components_data_properties = array(
                'data-fields' => array(),
                'conditional-data-fields' => array(),
                'subcomponents' => array()
            );
            foreach ($subcomponent_components as $subcomponent_component) {
                $subcomponent_processor = $this->getComponentProcessorManager()->getProcessor($subcomponent_component);
                if ($subcomponent_component_data_properties = $subcomponent_processor->$propagate_fn($subcomponent_component, $props[$componentFullName][Props::SUBCOMPONENTS])) {
                    $subcomponent_components_data_properties = array_merge_recursive(
                        $subcomponent_components_data_properties,
                        $subcomponent_component_data_properties
                    );
                }
            }

            $ret['subcomponents'][$subcomponent_data_field] = $ret['subcomponents'][$subcomponent_data_field] ?? array();
            if ($subcomponent_components_data_properties['data-fields'] ?? null) {
                $subcomponent_components_data_properties['data-fields'] = array_unique($subcomponent_components_data_properties['data-fields']);
                $ret['subcomponents'][$subcomponent_data_field]['data-fields'] = array_values(array_unique(array_merge(
                    $ret['subcomponents'][$subcomponent_data_field]['data-fields'] ?? [],
                    $subcomponent_components_data_properties['data-fields']
                )));
            }
            if ($subcomponent_components_data_properties['conditional-data-fields'] ?? null) {
                $ret['subcomponents'][$subcomponent_data_field]['conditional-data-fields'] = $ret['subcomponents'][$subcomponent_data_field]['conditional-data-fields'] ?? [];
                foreach ($subcomponent_components_data_properties['conditional-data-fields'] as $conditionDataField => $conditionalDataFields) {
                    $ret['subcomponents'][$subcomponent_data_field]['conditional-data-fields'][$conditionDataField] = array_merge_recursive(
                        $ret['subcomponents'][$subcomponent_data_field]['conditional-data-fields'][$conditionDataField] ?? [],
                        $conditionalDataFields
                    );
                }
            }

            if ($subcomponent_components_data_properties['subcomponents'] ?? null) {
                $ret['subcomponents'][$subcomponent_data_field]['subcomponents'] = $ret['subcomponents'][$subcomponent_data_field]['subcomponents'] ?? array();
                $ret['subcomponents'][$subcomponent_data_field]['subcomponents'] = array_merge_recursive(
                    $ret['subcomponents'][$subcomponent_data_field]['subcomponents'],
                    $subcomponent_components_data_properties['subcomponents']
                );
            }
        }
        $this->getComponentFilterManager()->restoreFromPropagation($component, $props);
    }


    //-------------------------------------------------
    // New PUBLIC Functions: Static Data
    //-------------------------------------------------

    public function getModelSupplementaryDBObjectDataComponentTree(array $component, array &$props): array
    {
        return $this->executeOnSelfAndMergeWithComponents('getModelSupplementaryDBObjectData', __FUNCTION__, $component, $props);
    }

    public function getModelSupplementaryDBObjectData(array $component, array &$props): array
    {
        return [];
    }

    //-------------------------------------------------
    // New PUBLIC Functions: Stateful Data
    //-------------------------------------------------

    public function getMutableonrequestSupplementaryDBObjectDataComponentTree(array $component, array &$props): array
    {
        return $this->executeOnSelfAndMergeWithComponents('getMutableonrequestSupplementaryDbobjectdata', __FUNCTION__, $component, $props);
    }

    public function getMutableonrequestSupplementaryDbobjectdata(array $component, array &$props): array
    {
        return [];
    }

    private function getSubcomponentsByGroup(array $component, array $elements = array()): array
    {
        if (empty($elements)) {
            $elements = array(
                self::COMPONENTELEMENT_SUBCOMPONENTS,
                self::COMPONENTELEMENT_RELATIONALSUBCOMPONENTS,
                self::COMPONENTELEMENT_CONDITIONALONDATAFIELDSUBCOMPONENTS,
                self::COMPONENTELEMENT_CONDITIONALONDATAFIELDRELATIONALSUBCOMPONENTS,
            );
        }

        $ret = array();

        if (in_array(self::COMPONENTELEMENT_SUBCOMPONENTS, $elements)) {
            // Modules are arrays, comparing them through the default SORT_STRING fails
            $ret = array_unique(
                $this->getSubcomponents($component),
                SORT_REGULAR
            );
        }

        if (in_array(self::COMPONENTELEMENT_RELATIONALSUBCOMPONENTS, $elements)) {
            foreach ($this->getRelationalComponentFields($component) as $relationalComponentField) {
                $ret = array_values(array_unique(
                    array_merge(
                        $relationalComponentField->getNestedComponents(),
                        $ret
                    ),
                    SORT_REGULAR
                ));
            }
        }

        if (in_array(self::COMPONENTELEMENT_CONDITIONALONDATAFIELDSUBCOMPONENTS, $elements)) {
            // Modules are arrays, comparing them through the default SORT_STRING fails
            foreach ($this->getConditionalLeafComponentFields($component) as $conditionalLeafComponentField) {
                $ret = array_unique(
                    array_merge(
                        $ret,
                        $conditionalLeafComponentField->getConditionalNestedComponents()
                    ),
                    SORT_REGULAR
                );
            }
        }

        if (in_array(self::COMPONENTELEMENT_CONDITIONALONDATAFIELDRELATIONALSUBCOMPONENTS, $elements)) {
            foreach ($this->getConditionalRelationalComponentFields($component) as $conditionalRelationalComponentField) {
                foreach ($conditionalRelationalComponentField->getRelationalComponentFields() as $relationalComponentField) {
                    $ret = array_values(
                        array_unique(
                            array_merge(
                                $relationalComponentField->getNestedComponents(),
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

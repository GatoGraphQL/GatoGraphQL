<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ConditionalLeafModuleField;

abstract class PoP_Module_Processor_ConditionWrapperBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_CONDITIONWRAPPER];
    }

    /**
     * @return ConditionalLeafModuleField[]
     */
    public function getConditionalOnDataFieldSubmodules(array $module): array
    {
        $ret = parent::getConditionalOnDataFieldSubmodules($module);

        if ($conditionDataField = $this->getConditionField($module)) {
            if ($layouts = $this->getConditionSucceededSubmodules($module)) {
                $ret[] = new ConditionalLeafModuleField(
                    $conditionDataField,
                    $layouts
                );
            }

            if ($conditionfailed_layouts = $this->getConditionFailedSubmodules($module)) {
                // Calculate the "not" data field for the conditionDataField
                $notConditionDataField = $this->getNotConditionField($module);
                $ret[] = new ConditionalLeafModuleField(
                    $notConditionDataField,
                    $conditionfailed_layouts
                );
            }
        }

        return $ret;
    }

    public function showDiv(array $module, array &$props)
    {
        return true;
    }

    public function getConditionSucceededSubmodules(array $module)
    {
        return array();
    }

    public function getConditionFailedSubmodules(array $module)
    {
        return array();
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        $ret = [];
        if ($conditionDataField = $this->getConditionField($module)) {
            $ret[] = $conditionDataField;
        }
        if (!empty($this->getConditionFailedSubmodules($module))) {
            $ret[] = $this->getNotConditionField($module);
        }

        return $ret;
    }

    abstract public function getConditionField(array $module): ?string;

    public function getNotConditionField(array $module)
    {
        // Calculate the "not" data field for the conditionDataField
        if ($conditionDataField = $this->getConditionField($module)) {
            $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
            list(
                $fieldName,
                $fieldArgs,
                $fieldAlias,
                $skipIfOutputNull,
                $fieldDirectives,
            ) = $fieldQueryInterpreter->listField($conditionDataField);
            if (!is_null($fieldAlias)) {
                $notFieldAlias = 'not-'.$fieldAlias;
            }
            // Make sure the conditionField has "()" as to be executed as a field
            $conditionField = $fieldArgs ?
                $fieldQueryInterpreter->composeField($fieldName, $fieldArgs) :
                $fieldQueryInterpreter->createFieldArgValueAsFieldFromFieldName($fieldName);
            return $fieldQueryInterpreter->getField(
                'not',
                [
                    'value' => $conditionField,
                ],
                $notFieldAlias,
                $skipIfOutputNull,
                $fieldDirectives
            );
        }
        return null;
    }

    public function getConditionMethod(array $module)
    {
        // Allow to execute a JS function on the object field value
        return null;
    }

    public function getConditionsucceededClass(array $module, array &$props)
    {
        return '';
    }

    public function getConditionfailedClass(array $module, array &$props)
    {
        return '';
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        if ($this->showDiv($module, $props)) {
            $ret['show-div'] = true;
        }

        if ($condition_field = $this->getConditionField($module)) {
            $ret['condition-field'] = FieldQueryInterpreterFacade::getInstance()->getFieldAlias($condition_field);
        }
        if ($not_condition_field = $this->getNotConditionField($module)) {
            $ret['not-condition-field'] = FieldQueryInterpreterFacade::getInstance()->getFieldAlias($not_condition_field);
        }
        if ($condition_method = $this->getConditionMethod($module)) {
            $ret['condition-method'] = $condition_method;
        }
        if ($classs = $this->getConditionsucceededClass($module, $props)) {
            $ret[GD_JS_CLASSES]['succeeded'] = $classs;
        }
        if ($classs = $this->getConditionfailedClass($module, $props)) {
            $ret[GD_JS_CLASSES]['failed'] = $classs;
        }

        if ($layouts = $this->getConditionSucceededSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layouts'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $layouts
            );
        }

        if ($conditionfailed_layouts = $this->getConditionFailedSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['conditionfailed-layouts'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $conditionfailed_layouts
            );
        }

        return $ret;
    }
}

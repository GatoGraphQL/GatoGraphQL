<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ConditionalLeafModuleField;

abstract class PoP_Module_Processor_ConditionWrapperBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_CONDITIONWRAPPER];
    }

    /**
     * @return ConditionalLeafModuleField[]
     */
    public function getConditionalOnDataFieldSubmodules(array $component): array
    {
        $ret = parent::getConditionalOnDataFieldSubmodules($component);

        if ($conditionDataField = $this->getConditionField($component)) {
            if ($layouts = $this->getConditionSucceededSubmodules($component)) {
                $ret[] = new ConditionalLeafModuleField(
                    $conditionDataField,
                    $layouts
                );
            }

            if ($conditionfailed_layouts = $this->getConditionFailedSubmodules($component)) {
                // Calculate the "not" data field for the conditionDataField
                $notConditionDataField = $this->getNotConditionField($component);
                $ret[] = new ConditionalLeafModuleField(
                    $notConditionDataField,
                    $conditionfailed_layouts
                );
            }
        }

        return $ret;
    }

    public function showDiv(array $component, array &$props)
    {
        return true;
    }

    public function getConditionSucceededSubmodules(array $component)
    {
        return array();
    }

    public function getConditionFailedSubmodules(array $component)
    {
        return array();
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        $ret = [];
        if ($conditionDataField = $this->getConditionField($component)) {
            $ret[] = $conditionDataField;
        }
        if (!empty($this->getConditionFailedSubmodules($component))) {
            $ret[] = $this->getNotConditionField($component);
        }

        return $ret;
    }

    abstract public function getConditionField(array $component): ?string;

    public function getNotConditionField(array $component)
    {
        // Calculate the "not" data field for the conditionDataField
        if ($conditionDataField = $this->getConditionField($component)) {
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

    public function getConditionMethod(array $component)
    {
        // Allow to execute a JS function on the object field value
        return null;
    }

    public function getConditionsucceededClass(array $component, array &$props)
    {
        return '';
    }

    public function getConditionfailedClass(array $component, array &$props)
    {
        return '';
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($this->showDiv($component, $props)) {
            $ret['show-div'] = true;
        }

        if ($condition_field = $this->getConditionField($component)) {
            $ret['condition-field'] = FieldQueryInterpreterFacade::getInstance()->getFieldAlias($condition_field);
        }
        if ($not_condition_field = $this->getNotConditionField($component)) {
            $ret['not-condition-field'] = FieldQueryInterpreterFacade::getInstance()->getFieldAlias($not_condition_field);
        }
        if ($condition_method = $this->getConditionMethod($component)) {
            $ret['condition-method'] = $condition_method;
        }
        if ($classs = $this->getConditionsucceededClass($component, $props)) {
            $ret[GD_JS_CLASSES]['succeeded'] = $classs;
        }
        if ($classs = $this->getConditionfailedClass($component, $props)) {
            $ret[GD_JS_CLASSES]['failed'] = $classs;
        }

        if ($layouts = $this->getConditionSucceededSubmodules($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['layouts'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $layouts
            );
        }

        if ($conditionfailed_layouts = $this->getConditionFailedSubmodules($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['conditionfailed-layouts'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $conditionfailed_layouts
            );
        }

        return $ret;
    }
}

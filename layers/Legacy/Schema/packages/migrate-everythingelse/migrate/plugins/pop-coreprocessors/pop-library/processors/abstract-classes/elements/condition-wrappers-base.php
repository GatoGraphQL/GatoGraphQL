<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ConditionalLeafModuleField;

abstract class PoP_Module_Processor_ConditionWrapperBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_CONDITIONWRAPPER];
    }

    /**
     * @return ConditionalLeafModuleField[]
     */
    public function getConditionalOnDataFieldSubmodules(array $componentVariation): array
    {
        $ret = parent::getConditionalOnDataFieldSubmodules($componentVariation);

        if ($conditionDataField = $this->getConditionField($componentVariation)) {
            if ($layouts = $this->getConditionSucceededSubmodules($componentVariation)) {
                $ret[] = new ConditionalLeafModuleField(
                    $conditionDataField,
                    $layouts
                );
            }

            if ($conditionfailed_layouts = $this->getConditionFailedSubmodules($componentVariation)) {
                // Calculate the "not" data field for the conditionDataField
                $notConditionDataField = $this->getNotConditionField($componentVariation);
                $ret[] = new ConditionalLeafModuleField(
                    $notConditionDataField,
                    $conditionfailed_layouts
                );
            }
        }

        return $ret;
    }

    public function showDiv(array $componentVariation, array &$props)
    {
        return true;
    }

    public function getConditionSucceededSubmodules(array $componentVariation)
    {
        return array();
    }

    public function getConditionFailedSubmodules(array $componentVariation)
    {
        return array();
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        $ret = [];
        if ($conditionDataField = $this->getConditionField($componentVariation)) {
            $ret[] = $conditionDataField;
        }
        if (!empty($this->getConditionFailedSubmodules($componentVariation))) {
            $ret[] = $this->getNotConditionField($componentVariation);
        }

        return $ret;
    }

    abstract public function getConditionField(array $componentVariation): ?string;

    public function getNotConditionField(array $componentVariation)
    {
        // Calculate the "not" data field for the conditionDataField
        if ($conditionDataField = $this->getConditionField($componentVariation)) {
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

    public function getConditionMethod(array $componentVariation)
    {
        // Allow to execute a JS function on the object field value
        return null;
    }

    public function getConditionsucceededClass(array $componentVariation, array &$props)
    {
        return '';
    }

    public function getConditionfailedClass(array $componentVariation, array &$props)
    {
        return '';
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        if ($this->showDiv($componentVariation, $props)) {
            $ret['show-div'] = true;
        }

        if ($condition_field = $this->getConditionField($componentVariation)) {
            $ret['condition-field'] = FieldQueryInterpreterFacade::getInstance()->getFieldAlias($condition_field);
        }
        if ($not_condition_field = $this->getNotConditionField($componentVariation)) {
            $ret['not-condition-field'] = FieldQueryInterpreterFacade::getInstance()->getFieldAlias($not_condition_field);
        }
        if ($condition_method = $this->getConditionMethod($componentVariation)) {
            $ret['condition-method'] = $condition_method;
        }
        if ($classs = $this->getConditionsucceededClass($componentVariation, $props)) {
            $ret[GD_JS_CLASSES]['succeeded'] = $classs;
        }
        if ($classs = $this->getConditionfailedClass($componentVariation, $props)) {
            $ret[GD_JS_CLASSES]['failed'] = $classs;
        }

        if ($layouts = $this->getConditionSucceededSubmodules($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layouts'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $layouts
            );
        }

        if ($conditionfailed_layouts = $this->getConditionFailedSubmodules($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['conditionfailed-layouts'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $conditionfailed_layouts
            );
        }

        return $ret;
    }
}

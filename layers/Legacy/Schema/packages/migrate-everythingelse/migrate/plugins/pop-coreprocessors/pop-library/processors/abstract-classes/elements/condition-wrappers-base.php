<?php
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ConditionalLeafComponentFieldNode;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;

abstract class PoP_Module_Processor_ConditionWrapperBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_CONDITIONWRAPPER];
    }

    /**
     * @return ConditionalLeafComponentFieldNode[]
     */
    public function getConditionalLeafComponentFieldNodes(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getConditionalLeafComponentFieldNodes($component);

        if ($conditionField = $this->getConditionField($component)) {
            if ($layouts = $this->getConditionSucceededSubcomponents($component)) {
                $ret[] = new ConditionalLeafComponentFieldNode(
                    new LeafField(
                        $conditionField,
                        null,
                        [],
                        [],
                        ASTNodesFactory::getNonSpecificLocation()
                    ),
                    $layouts
                );
            }

            if ($conditionfailed_layouts = $this->getConditionFailedSubcomponents($component)) {
                // Calculate the "not" data field for the conditionField
                $notConditionDataField = $this->getNotConditionField($component);
                $ret[] = new ConditionalLeafComponentFieldNode(
                    new LeafField(
                        $notConditionDataField,
                        null,
                        [],
                        [],
                        ASTNodesFactory::getNonSpecificLocation()
                    ),
                    $conditionfailed_layouts
                );
            }
        }

        return $ret;
    }

    public function showDiv(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return true;
    }

    public function getConditionSucceededSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        return array();
    }

    public function getConditionFailedSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        return array();
    }

    /**
     * @todo Migrate from string to LeafComponentFieldNode
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentFieldNode[]
     */
    public function getLeafComponentFieldNodes(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = [];
        if ($conditionField = $this->getConditionField($component)) {
            $ret[] = $conditionField;
        }
        if (!empty($this->getConditionFailedSubcomponents($component))) {
            $ret[] = $this->getNotConditionField($component);
        }

        return $ret;
    }

    abstract public function getConditionField(\PoP\ComponentModel\Component\Component $component): ?string;

    /**
     * Calculate the "not" data field for the conditionField
     *
     * @todo Re-do this method with AST
     */
    public function getNotConditionField(\PoP\ComponentModel\Component\Component $component)
    {
        // Calculate the "not" data field for the conditionField
        if ($conditionField = $this->getConditionField($component)) {
            // @todo Create the "not" Field using FieldVariableReferences
            return '';
        }
        return null;
    }

    public function getConditionMethod(\PoP\ComponentModel\Component\Component $component)
    {
        // Allow to execute a JS function on the object field value
        return null;
    }

    public function getConditionsucceededClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return '';
    }

    public function getConditionfailedClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return '';
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($this->showDiv($component, $props)) {
            $ret['show-div'] = true;
        }

        if ($condition_field = $this->getConditionField($component)) {
            $ret['condition-field'] = /* @todo This must be a Field */ $condition_field->getAlias();
        }
        if ($not_condition_field = $this->getNotConditionField($component)) {
            $ret['not-condition-field'] = /* @todo This must be a Field */ $not_condition_field->getAlias();
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

        if ($layouts = $this->getConditionSucceededSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['layouts'] = array_map(
                \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName(...),
                $layouts
            );
        }

        if ($conditionfailed_layouts = $this->getConditionFailedSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['conditionfailed-layouts'] = array_map(
                \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName(...),
                $conditionfailed_layouts
            );
        }

        return $ret;
    }
}

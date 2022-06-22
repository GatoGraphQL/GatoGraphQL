<?php

use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ConditionalLeafComponentFieldNode;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;

abstract class PoP_Module_Processor_MultipleLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_MULTIPLE];
    }

    public function getDefaultLayoutSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }

    public function getMultipleLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        return array();
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($default = $this->getDefaultLayoutSubcomponent($component)) {
            $ret[] = $default;
        }

        return $ret;
    }

    /**
     * @return ConditionalLeafComponentFieldNode[]
     */
    public function getConditionalLeafComponentFieldNodes(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getConditionalLeafComponentFieldNodes($component);

        // The function below returns an array with value => $subcomponent.
        // It must be converted to value => [$subcomponent]
        foreach ($this->getMultipleLayoutSubcomponents($component) as $conditionField => $conditionalSubcomponent) {
            $ret[] = new ConditionalLeafComponentFieldNode(
                new LeafField(
                    $conditionField,
                    null,
                    [],
                    [],
                    LocationHelper::getNonSpecificLocation()
                ),
                [
                    $conditionalSubcomponent,
                ]
            );
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $this->appendProp($component, $props, 'class', 'pop-multilayout');
        parent::initModelProps($component, $props);
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($defaultLayout = $this->getDefaultLayoutSubcomponent($component)) {
            $ret['default-component'] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($defaultLayout);
        }
        $ret['condition-on-data-field-components'] = array_map(
            \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName(...),
            $this->getMultipleLayoutSubcomponents($component)
        );

        return $ret;
    }
}

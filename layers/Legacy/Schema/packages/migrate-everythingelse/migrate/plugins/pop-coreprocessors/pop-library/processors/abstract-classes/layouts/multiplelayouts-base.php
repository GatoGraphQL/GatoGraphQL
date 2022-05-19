<?php

use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ConditionalLeafModuleField;

abstract class PoP_Module_Processor_MultipleLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_MULTIPLE];
    }

    public function getDefaultLayoutSubcomponent(array $component)
    {
        return null;
    }

    public function getMultipleLayoutSubcomponents(array $component)
    {
        return array();
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($default = $this->getDefaultLayoutSubcomponent($component)) {
            $ret[] = $default;
        }

        return $ret;
    }

    /**
     * @return ConditionalLeafModuleField[]
     */
    public function getConditionalOnDataFieldSubcomponents(array $component): array
    {
        $ret = parent::getConditionalOnDataFieldSubcomponents($component);

        // The function below returns an array with value => $subComponent.
        // It must be converted to value => [$subComponent]
        foreach ($this->getMultipleLayoutSubcomponents($component) as $conditionDataField => $conditionalSubcomponent) {
            $ret[] = new ConditionalLeafModuleField(
                $conditionDataField,
                [
                    $conditionalSubcomponent,
                ]
            );
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        $this->appendProp($component, $props, 'class', 'pop-multilayout');
        parent::initModelProps($component, $props);
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($defaultLayout = $this->getDefaultLayoutSubcomponent($component)) {
            $ret['default-component'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($defaultLayout);
        }
        $ret['condition-on-data-field-components'] = array_map(
            [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
            $this->getMultipleLayoutSubcomponents($component)
        );

        return $ret;
    }
}

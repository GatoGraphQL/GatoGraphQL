<?php

use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ConditionalLeafModuleField;

abstract class PoP_Module_Processor_MultipleLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_MULTIPLE];
    }

    public function getDefaultLayoutSubmodule(array $component)
    {
        return null;
    }

    public function getMultipleLayoutSubmodules(array $component)
    {
        return array();
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        if ($default = $this->getDefaultLayoutSubmodule($component)) {
            $ret[] = $default;
        }

        return $ret;
    }

    /**
     * @return ConditionalLeafModuleField[]
     */
    public function getConditionalOnDataFieldSubmodules(array $component): array
    {
        $ret = parent::getConditionalOnDataFieldSubmodules($component);

        // The function below returns an array with value => $subComponent.
        // It must be converted to value => [$subComponent]
        foreach ($this->getMultipleLayoutSubmodules($component) as $conditionDataField => $conditionalSubmodule) {
            $ret[] = new ConditionalLeafModuleField(
                $conditionDataField,
                [
                    $conditionalSubmodule,
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

        if ($defaultLayout = $this->getDefaultLayoutSubmodule($component)) {
            $ret['default-module'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($defaultLayout);
        }
        $ret['condition-on-data-field-modules'] = array_map(
            [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
            $this->getMultipleLayoutSubmodules($component)
        );

        return $ret;
    }
}

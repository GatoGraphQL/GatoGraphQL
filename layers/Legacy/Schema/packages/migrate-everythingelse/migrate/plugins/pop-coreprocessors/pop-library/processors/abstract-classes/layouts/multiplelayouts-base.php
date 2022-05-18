<?php

use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ConditionalLeafModuleField;

abstract class PoP_Module_Processor_MultipleLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_MULTIPLE];
    }

    public function getDefaultLayoutSubmodule(array $componentVariation)
    {
        return null;
    }

    public function getMultipleLayoutSubmodules(array $componentVariation)
    {
        return array();
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        if ($default = $this->getDefaultLayoutSubmodule($componentVariation)) {
            $ret[] = $default;
        }

        return $ret;
    }

    /**
     * @return ConditionalLeafModuleField[]
     */
    public function getConditionalOnDataFieldSubmodules(array $componentVariation): array
    {
        $ret = parent::getConditionalOnDataFieldSubmodules($componentVariation);

        // The function below returns an array with value => $subComponentVariation.
        // It must be converted to value => [$subComponentVariation]
        foreach ($this->getMultipleLayoutSubmodules($componentVariation) as $conditionDataField => $conditionalSubmodule) {
            $ret[] = new ConditionalLeafModuleField(
                $conditionDataField,
                [
                    $conditionalSubmodule,
                ]
            );
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $this->appendProp($componentVariation, $props, 'class', 'pop-multilayout');
        parent::initModelProps($componentVariation, $props);
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        if ($defaultLayout = $this->getDefaultLayoutSubmodule($componentVariation)) {
            $ret['default-module'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($defaultLayout);
        }
        $ret['condition-on-data-field-modules'] = array_map(
            [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
            $this->getMultipleLayoutSubmodules($componentVariation)
        );

        return $ret;
    }
}

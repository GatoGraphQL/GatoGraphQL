<?php

use PoP\ComponentModel\GraphQLModel\ComponentModelSpec\Ast\ConditionalLeafModuleField;

abstract class PoP_Module_Processor_MultipleLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_MULTIPLE];
    }

    public function getDefaultLayoutSubmodule(array $module)
    {
        return null;
    }

    public function getMultipleLayoutSubmodules(array $module)
    {
        return array();
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        if ($default = $this->getDefaultLayoutSubmodule($module)) {
            $ret[] = $default;
        }

        return $ret;
    }

    /**
     * @return ConditionalLeafModuleField[]
     */
    public function getConditionalOnDataFieldSubmodules(array $module): array
    {
        $ret = parent::getConditionalOnDataFieldSubmodules($module);

        // The function below returns an array with value => $submodule.
        // It must be converted to value => [$submodule]
        foreach ($this->getMultipleLayoutSubmodules($module) as $conditionDataField => $conditionalSubmodule) {
            $ret[] = new ConditionalLeafModuleField(
                $conditionDataField,
                [
                    $conditionalSubmodule,
                ]
            );
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        $this->appendProp($module, $props, 'class', 'pop-multilayout');
        parent::initModelProps($module, $props);
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        if ($defaultLayout = $this->getDefaultLayoutSubmodule($module)) {
            $ret['default-module'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($defaultLayout);
        }
        $ret['condition-on-data-field-modules'] = array_map(
            [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
            $this->getMultipleLayoutSubmodules($module)
        );

        return $ret;
    }
}

<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

abstract class PoP_Module_Processor_SelectableTypeaheadFormComponentsBase extends PoP_Module_Processor_TypeaheadFormComponentsBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORMCOMPONENT_SELECTABLETYPEAHEAD];
    }

    protected function upToOneSelection(array $module, array &$props)
    {
        return $this->getProp($module, $props, 'max-selected') === 1;
    }

    public function isMultiple(array $module): bool
    {
        return true;
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        // If it is the unique-preselected, then the typeahead will not be used, then no need to initialize it
        if (!$this->getProp($module, $props, 'unique-preselected')) {
            $this->addJsmethod($ret, 'selectableTypeahead');
        }

        return $ret;
    }
    public function getTypeaheadJsmethod(array $module, array &$props)
    {
        return 'selectableTypeahead';
    }

    public function getImmutableJsconfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($module, $props);

        if (!$this->getProp($module, $props, 'unique-preselected')) {
            $ret['selectableTypeahead']['trigger-layout'] = $this->getTriggerLayoutSubmodule($module);
        }

        return $ret;
    }

    public function getFormcomponentModule(array $module)
    {
        return $this->getTriggerLayoutSubmodule($module);
    }

    public function getTriggerLayoutSubmodule(array $module)
    {
        return null;
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        $ret[] = $this->getTriggerLayoutSubmodule($module);

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        $trigger_layout = $this->getTriggerLayoutSubmodule($module);
        $this->appendProp($trigger_layout, $props, 'class', GD_CLASS_TRIGGERLAYOUT);

        // If the input is not multiple, then it cannot have more than 1 option selected for sure
        if (!$this->isMultiple($module)) {
            $this->setProp($module, $props, 'max-selected', 1);
        }

        $input = $this->getInputSubmodule($module);
        $this->appendProp($input, $props, 'class', 'max-selected-disable');

        // Sortable only if maxSelected is not only 1
        if (!$this->upToOneSelection($module, $props)) {
            // Add class 'sortable' when the group is, well, sortable. This will show the appropriate cursor
            $this->appendProp($trigger_layout, $props, 'class', 'sortable');
        }

        parent::initModelProps($module, $props);
    }

    public function initWebPlatformModelProps(array $module, array &$props)
    {
        // Sortable only if maxSelected is not only 1
        if (!$this->upToOneSelection($module, $props)) {
            $trigger_layout = $this->getTriggerLayoutSubmodule($module);
            $this->mergeJsmethodsProp($trigger_layout, $props, array('sortable'));
        }

        parent::initWebPlatformModelProps($module, $props);
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $trigger_layout = $this->getTriggerLayoutSubmodule($module);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['trigger-layout'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($trigger_layout);

        if ($maxSelected = $this->getProp($module, $props, 'max-selected')) {
            $ret['max-selected'] = $maxSelected;
        }

        return $ret;
    }

    public function getModulesToPropagateDataProperties(array $module): array
    {
        $ret = parent::getModulesToPropagateDataProperties($module);

        if ($trigger_layout = $this->getTriggerLayoutSubmodule($module)) {
            // Important: the trigger layout must not be included, since it doesn't apply to the same entity being iterated
            return array_values(
                array_diff(
                    $ret,
                    [
                        $trigger_layout,
                    ]
                )
            );
        }

        return $ret;
    }
}

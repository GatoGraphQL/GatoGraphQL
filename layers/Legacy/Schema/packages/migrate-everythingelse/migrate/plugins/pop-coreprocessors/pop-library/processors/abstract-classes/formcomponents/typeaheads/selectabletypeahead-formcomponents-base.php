<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_SelectableTypeaheadFormComponentsBase extends PoP_Module_Processor_TypeaheadFormComponentsBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORMCOMPONENT_SELECTABLETYPEAHEAD];
    }

    protected function upToOneSelection(array $componentVariation, array &$props)
    {
        return $this->getProp($componentVariation, $props, 'max-selected') === 1;
    }

    public function isMultiple(array $componentVariation): bool
    {
        return true;
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        // If it is the unique-preselected, then the typeahead will not be used, then no need to initialize it
        if (!$this->getProp($componentVariation, $props, 'unique-preselected')) {
            $this->addJsmethod($ret, 'selectableTypeahead');
        }

        return $ret;
    }
    public function getTypeaheadJsmethod(array $componentVariation, array &$props)
    {
        return 'selectableTypeahead';
    }

    public function getImmutableJsconfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($componentVariation, $props);

        if (!$this->getProp($componentVariation, $props, 'unique-preselected')) {
            $ret['selectableTypeahead']['trigger-layout'] = $this->getTriggerLayoutSubmodule($componentVariation);
        }

        return $ret;
    }

    public function getFormcomponentModule(array $componentVariation)
    {
        return $this->getTriggerLayoutSubmodule($componentVariation);
    }

    public function getTriggerLayoutSubmodule(array $componentVariation)
    {
        return null;
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        $ret[] = $this->getTriggerLayoutSubmodule($componentVariation);

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $trigger_layout = $this->getTriggerLayoutSubmodule($componentVariation);
        $this->appendProp($trigger_layout, $props, 'class', GD_CLASS_TRIGGERLAYOUT);

        // If the input is not multiple, then it cannot have more than 1 option selected for sure
        if (!$this->isMultiple($componentVariation)) {
            $this->setProp($componentVariation, $props, 'max-selected', 1);
        }

        $input = $this->getInputSubmodule($componentVariation);
        $this->appendProp($input, $props, 'class', 'max-selected-disable');

        // Sortable only if maxSelected is not only 1
        if (!$this->upToOneSelection($componentVariation, $props)) {
            // Add class 'sortable' when the group is, well, sortable. This will show the appropriate cursor
            $this->appendProp($trigger_layout, $props, 'class', 'sortable');
        }

        parent::initModelProps($componentVariation, $props);
    }

    public function initWebPlatformModelProps(array $componentVariation, array &$props)
    {
        // Sortable only if maxSelected is not only 1
        if (!$this->upToOneSelection($componentVariation, $props)) {
            $trigger_layout = $this->getTriggerLayoutSubmodule($componentVariation);
            $this->mergeJsmethodsProp($trigger_layout, $props, array('sortable'));
        }

        parent::initWebPlatformModelProps($componentVariation, $props);
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $trigger_layout = $this->getTriggerLayoutSubmodule($componentVariation);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['trigger-layout'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($trigger_layout);

        if ($maxSelected = $this->getProp($componentVariation, $props, 'max-selected')) {
            $ret['max-selected'] = $maxSelected;
        }

        return $ret;
    }

    public function getModulesToPropagateDataProperties(array $componentVariation): array
    {
        $ret = parent::getModulesToPropagateDataProperties($componentVariation);

        if ($trigger_layout = $this->getTriggerLayoutSubmodule($componentVariation)) {
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

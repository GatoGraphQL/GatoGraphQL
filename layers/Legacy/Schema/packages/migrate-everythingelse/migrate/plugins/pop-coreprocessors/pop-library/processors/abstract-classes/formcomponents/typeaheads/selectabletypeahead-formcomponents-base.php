<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_SelectableTypeaheadFormComponentsBase extends PoP_Module_Processor_TypeaheadFormComponentsBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORMCOMPONENT_SELECTABLETYPEAHEAD];
    }

    protected function upToOneSelection(array $component, array &$props)
    {
        return $this->getProp($component, $props, 'max-selected') === 1;
    }

    public function isMultiple(array $component): bool
    {
        return true;
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        // If it is the unique-preselected, then the typeahead will not be used, then no need to initialize it
        if (!$this->getProp($component, $props, 'unique-preselected')) {
            $this->addJsmethod($ret, 'selectableTypeahead');
        }

        return $ret;
    }
    public function getTypeaheadJsmethod(array $component, array &$props)
    {
        return 'selectableTypeahead';
    }

    public function getImmutableJsconfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($component, $props);

        if (!$this->getProp($component, $props, 'unique-preselected')) {
            $ret['selectableTypeahead']['trigger-layout'] = $this->getTriggerLayoutSubcomponent($component);
        }

        return $ret;
    }

    public function getFormcomponentModule(array $component)
    {
        return $this->getTriggerLayoutSubcomponent($component);
    }

    public function getTriggerLayoutSubcomponent(array $component)
    {
        return null;
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        $ret[] = $this->getTriggerLayoutSubcomponent($component);

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        $trigger_layout = $this->getTriggerLayoutSubcomponent($component);
        $this->appendProp($trigger_layout, $props, 'class', GD_CLASS_TRIGGERLAYOUT);

        // If the input is not multiple, then it cannot have more than 1 option selected for sure
        if (!$this->isMultiple($component)) {
            $this->setProp($component, $props, 'max-selected', 1);
        }

        $input = $this->getInputSubcomponent($component);
        $this->appendProp($input, $props, 'class', 'max-selected-disable');

        // Sortable only if maxSelected is not only 1
        if (!$this->upToOneSelection($component, $props)) {
            // Add class 'sortable' when the group is, well, sortable. This will show the appropriate cursor
            $this->appendProp($trigger_layout, $props, 'class', 'sortable');
        }

        parent::initModelProps($component, $props);
    }

    public function initWebPlatformModelProps(array $component, array &$props)
    {
        // Sortable only if maxSelected is not only 1
        if (!$this->upToOneSelection($component, $props)) {
            $trigger_layout = $this->getTriggerLayoutSubcomponent($component);
            $this->mergeJsmethodsProp($trigger_layout, $props, array('sortable'));
        }

        parent::initWebPlatformModelProps($component, $props);
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $trigger_layout = $this->getTriggerLayoutSubcomponent($component);
        $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['trigger-layout'] = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getModuleOutputName($trigger_layout);

        if ($maxSelected = $this->getProp($component, $props, 'max-selected')) {
            $ret['max-selected'] = $maxSelected;
        }

        return $ret;
    }

    public function getModulesToPropagateDataProperties(array $component): array
    {
        $ret = parent::getModulesToPropagateDataProperties($component);

        if ($trigger_layout = $this->getTriggerLayoutSubcomponent($component)) {
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

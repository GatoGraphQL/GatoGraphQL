<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\ComponentProcessors\FormComponentComponentProcessorInterface;

abstract class PoP_Module_Processor_TypeaheadFormComponentsBase extends PoPEngine_QueryDataComponentProcessorBase implements FormComponentComponentProcessorInterface
{
    use FormComponentModuleDelegatorTrait;

    public function getFormcomponentModule(array $component)
    {
        return $this->getInputSubcomponent($component);
    }

    public function getComponentSubcomponents(array $component)
    {
        return array();
    }
    public function getInputSubcomponent(array $component)
    {
        return [PoP_Module_Processor_TypeaheadTextFormInputs::class, PoP_Module_Processor_TypeaheadTextFormInputs::COMPONENT_FORMINPUT_TEXT_TYPEAHEAD];
    }

    public function getLabel(array $component, array &$props)
    {
        $componentFullName = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentFullName($component);
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $input = $this->getInputSubcomponent($component);

        // Because getLabel is used on initModelProps, the structure in $props for the subcomponent may not be created yet, throwing an error since then it's null
        // Just for this case, pass another array, not $props
        $subcomponent_props = [];
        if ($props[$componentFullName][\PoP\ComponentModel\Constants\Props::SUBCOMPONENTS]) {
            $subcomponent_props = &$props[$componentFullName][\PoP\ComponentModel\Constants\Props::SUBCOMPONENTS];
        }
        return $componentprocessor_manager->getProcessor($input)->getLabel($input, $subcomponent_props);
    }

    public function initRequestProps(array $component, array &$props): void
    {
        $this->metaFormcomponentInitModuleRequestProps($component, $props);
        parent::initRequestProps($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        $typeahead_class = $this->getTypeaheadClass($component, $props);
        $this->appendProp($component, $props, 'class', $typeahead_class);

        // Make the input hidden? This is needed when using the typeahead to pre-select a value,
        // but we don't want the user to keep selecting more. Eg: Add Highlight
        $input = $this->getInputSubcomponent($component);
        $this->appendProp($input, $props, 'class', 'typeahead');

        // Transfer properties down the line
        $label = $this->getProp($component, $props, 'label');
        if (!is_null($label)) {
            $this->setProp($input, $props, 'label', $label);
        }
        $placeholder = $this->getProp($component, $props, 'placeholder');
        if (!is_null($placeholder)) {
            $this->setProp($input, $props, 'placeholder', $placeholder);
        }

        parent::initModelProps($component, $props);
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $input = $this->getInputSubcomponent($component);
        $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['input'] = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName($input);

        return $ret;
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        return array_merge(
            $ret,
            $this->getComponentSubcomponents($component),
            array(
                $this->getInputSubcomponent($component)
            )
        );
    }
    public function getTypeaheadClass(array $component, array &$props)
    {
        return 'pop-typeahead';
    }
    public function getTypeaheadJsmethod(array $component, array &$props)
    {
        return null;
    }

    public function getImmutableJsconfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($component, $props);

        if ($typeahead_jsmethod = $this->getTypeaheadJsmethod($component, $props)) {
            $ret[$typeahead_jsmethod]['dataset-components'] = $this->getComponentSubcomponents($component);
        }

        return $ret;
    }

    public function getComponentsToPropagateDataProperties(array $component): array
    {

        // Important: the TYPEAHEAD_COMPONENT (eg: PoP_Module_Processor_UserTypeaheadComponentLayouts::COMPONENT_LAYOUTUSER_TYPEAHEAD_COMPONENT) should not have data-fields, because it doesn't apply to {{blockSettings.dataset}}
        // but it applies to Twitter Typeahead, which doesn't need these parameters, however these, here, upset the whole getDatasetcomponentTreeSectionFlattenedDataFields
        // To fix this, in self::COMPONENT_FORMINPUT_TYPEAHEAD data_properties we stop spreading down, so it never reaches below there to get further data-fields
        return array_values(
            array_diff(
                parent::getComponentsToPropagateDataProperties($component),
                $this->getComponentSubcomponents($component)
            )
        );
    }
}

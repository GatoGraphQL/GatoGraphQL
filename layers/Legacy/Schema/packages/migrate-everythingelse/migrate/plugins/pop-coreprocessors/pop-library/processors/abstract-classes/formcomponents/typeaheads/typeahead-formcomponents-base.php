<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\ComponentProcessors\FormComponentComponentProcessorInterface;

abstract class PoP_Module_Processor_TypeaheadFormComponentsBase extends PoPEngine_QueryDataComponentProcessorBase implements FormComponentComponentProcessorInterface
{
    use FormComponentModuleDelegatorTrait;

    public function getFormcomponentModule(array $componentVariation)
    {
        return $this->getInputSubmodule($componentVariation);
    }

    public function getComponentSubmodules(array $componentVariation)
    {
        return array();
    }
    public function getInputSubmodule(array $componentVariation)
    {
        return [PoP_Module_Processor_TypeaheadTextFormInputs::class, PoP_Module_Processor_TypeaheadTextFormInputs::MODULE_FORMINPUT_TEXT_TYPEAHEAD];
    }

    public function getLabel(array $componentVariation, array &$props)
    {
        $moduleFullName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($componentVariation);
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $input = $this->getInputSubmodule($componentVariation);

        // Because getLabel is used on initModelProps, the structure in $props for the submodule may not be created yet, throwing an error since then it's null
        // Just for this case, pass another array, not $props
        $submodule_props = [];
        if ($props[$moduleFullName][\PoP\ComponentModel\Constants\Props::SUBMODULES]) {
            $submodule_props = &$props[$moduleFullName][\PoP\ComponentModel\Constants\Props::SUBMODULES];
        }
        return $componentprocessor_manager->getProcessor($input)->getLabel($input, $submodule_props);
    }

    public function initRequestProps(array $componentVariation, array &$props): void
    {
        $this->metaFormcomponentInitModuleRequestProps($componentVariation, $props);
        parent::initRequestProps($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $typeahead_class = $this->getTypeaheadClass($componentVariation, $props);
        $this->appendProp($componentVariation, $props, 'class', $typeahead_class);

        // Make the input hidden? This is needed when using the typeahead to pre-select a value,
        // but we don't want the user to keep selecting more. Eg: Add Highlight
        $input = $this->getInputSubmodule($componentVariation);
        $this->appendProp($input, $props, 'class', 'typeahead');

        // Transfer properties down the line
        $label = $this->getProp($componentVariation, $props, 'label');
        if (!is_null($label)) {
            $this->setProp($input, $props, 'label', $label);
        }
        $placeholder = $this->getProp($componentVariation, $props, 'placeholder');
        if (!is_null($placeholder)) {
            $this->setProp($input, $props, 'placeholder', $placeholder);
        }

        parent::initModelProps($componentVariation, $props);
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $input = $this->getInputSubmodule($componentVariation);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['input'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($input);

        return $ret;
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        return array_merge(
            $ret,
            $this->getComponentSubmodules($componentVariation),
            array(
                $this->getInputSubmodule($componentVariation)
            )
        );
    }
    public function getTypeaheadClass(array $componentVariation, array &$props)
    {
        return 'pop-typeahead';
    }
    public function getTypeaheadJsmethod(array $componentVariation, array &$props)
    {
        return null;
    }

    public function getImmutableJsconfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($componentVariation, $props);

        if ($typeahead_jsmethod = $this->getTypeaheadJsmethod($componentVariation, $props)) {
            $ret[$typeahead_jsmethod]['dataset-components'] = $this->getComponentSubmodules($componentVariation);
        }

        return $ret;
    }

    public function getModulesToPropagateDataProperties(array $componentVariation): array
    {

        // Important: the TYPEAHEAD_COMPONENT (eg: PoP_Module_Processor_UserTypeaheadComponentLayouts::MODULE_LAYOUTUSER_TYPEAHEAD_COMPONENT) should not have data-fields, because it doesn't apply to {{blockSettings.dataset}}
        // but it applies to Twitter Typeahead, which doesn't need these parameters, however these, here, upset the whole getDatasetmoduletreeSectionFlattenedDataFields
        // To fix this, in self::MODULE_FORMINPUT_TYPEAHEAD data_properties we stop spreading down, so it never reaches below there to get further data-fields
        return array_values(
            array_diff(
                parent::getModulesToPropagateDataProperties($componentVariation),
                $this->getComponentSubmodules($componentVariation)
            )
        );
    }
}

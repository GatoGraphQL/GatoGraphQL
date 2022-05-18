<?php

abstract class PoP_Module_Processor_TextareaFormInputsBase extends PoP_Module_Processor_FormInputsBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORMINPUT_TEXTAREA];
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $ret['rows'] = $this->getRows($component, $props);
        if ($placeholder = $this->getProp($component, $props, 'placeholder')) {
            $ret['placeholder'] = $placeholder;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {

        // Use the label as placeholder
        $this->setProp($component, $props, 'placeholder', $this->getLabel($component, $props));
        parent::initModelProps($component, $props);
    }

    public function getRows(array $component, array &$props)
    {
        return 5;
    }
}

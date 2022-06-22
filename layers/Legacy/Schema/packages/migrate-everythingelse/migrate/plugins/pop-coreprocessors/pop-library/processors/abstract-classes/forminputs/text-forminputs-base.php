<?php

abstract class PoP_Module_Processor_TextFormInputsBase extends PoP_Module_Processor_FormInputsBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORMINPUT_TEXT];
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $ret['type'] = $this->getType($component, $props);
        if ($placeholder = $this->getProp($component, $props, 'placeholder')) {
            $ret['placeholder'] = $placeholder;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {

        // Use the label as placeholder
        $this->setProp($component, $props, 'placeholder', $this->getLabel($component, $props));
        parent::initModelProps($component, $props);
    }

    public function getType(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        if ($this->isHidden($component, $props)) {
            return 'hidden';
        }

        return 'text';
    }
}

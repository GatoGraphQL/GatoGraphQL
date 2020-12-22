<?php

abstract class PoP_Module_Processor_TextFormInputsBase extends PoP_Module_Processor_FormInputsBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORMINPUT_TEXT];
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $ret['type'] = $this->getType($module, $props);
        if ($placeholder = $this->getProp($module, $props, 'placeholder')) {
            $ret['placeholder'] = $placeholder;
        }
                
        return $ret;
    }

    public function initModelProps(array $module, array &$props)
    {

        // Use the label as placeholder
        $this->setProp($module, $props, 'placeholder', $this->getLabel($module, $props));
        parent::initModelProps($module, $props);
    }

    public function getType(array $module, array &$props)
    {
        if ($this->isHidden($module, $props)) {
            return 'hidden';
        }

        return 'text';
    }
}

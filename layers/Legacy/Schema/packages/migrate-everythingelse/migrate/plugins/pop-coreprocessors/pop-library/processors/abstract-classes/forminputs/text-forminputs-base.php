<?php

abstract class PoP_Module_Processor_TextFormInputsBase extends PoP_Module_Processor_FormInputsBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORMINPUT_TEXT];
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $ret['type'] = $this->getType($componentVariation, $props);
        if ($placeholder = $this->getProp($componentVariation, $props, 'placeholder')) {
            $ret['placeholder'] = $placeholder;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {

        // Use the label as placeholder
        $this->setProp($componentVariation, $props, 'placeholder', $this->getLabel($componentVariation, $props));
        parent::initModelProps($componentVariation, $props);
    }

    public function getType(array $componentVariation, array &$props)
    {
        if ($this->isHidden($componentVariation, $props)) {
            return 'hidden';
        }

        return 'text';
    }
}

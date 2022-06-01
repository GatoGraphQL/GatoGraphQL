<?php

abstract class PoP_Module_Processor_ControlsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getLabel(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return null;
    }
    public function getText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return $this->getLabel($component, $props);
    }
    public function getMutableonrequestText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return null;
    }
    public function getIcon(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }
    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return null;
    }
    public function showTooltip(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return false;
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        if ($this->getLabel($component, $props) && $this->showTooltip($component, $props)) {
            $this->addJsmethod($ret, 'tooltip');
        }

        return $ret;
    }

    public function getMutableonrequestConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($component, $props);

        // If there is a runtime configuration for the text, it overrides the configuration
        if ($text = $this->getMutableonrequestText($component, $props)) {
            $ret['text'] = $text;
        }

        return $ret;
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($label = $this->getLabel($component, $props)) {
            $ret['label'] = $label;
        }
        if ($text = $this->getText($component, $props)) {
            $ret['text'] = $text;
        }
        if ($icon = $this->getIcon($component)) {
            $ret['icon'] = $icon;
        }
        if ($fontawesome = $this->getFontawesome($component, $props)) {
            $ret[GD_JS_FONTAWESOME] = $fontawesome;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        if ($blocktarget = $this->getProp($component, $props, 'control-target')) {
            foreach ($this->getSubcomponents($component) as $subcomponent) {
                $this->setProp([$subcomponent], $props, 'control-target', $blocktarget);
            }
        }

        parent::initModelProps($component, $props);
    }
}

<?php

abstract class PoP_Module_Processor_ControlsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getLabel(array $component, array &$props)
    {
        return null;
    }
    public function getText(array $component, array &$props)
    {
        return $this->getLabel($component, $props);
    }
    public function getMutableonrequestText(array $component, array &$props)
    {
        return null;
    }
    public function getIcon(array $component)
    {
        return null;
    }
    public function getFontawesome(array $component, array &$props)
    {
        return null;
    }
    public function showTooltip(array $component, array &$props)
    {
        return false;
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        if ($this->getLabel($component, $props) && $this->showTooltip($component, $props)) {
            $this->addJsmethod($ret, 'tooltip');
        }

        return $ret;
    }

    public function getMutableonrequestConfiguration(array $component, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($component, $props);

        // If there is a runtime configuration for the text, it overrides the configuration
        if ($text = $this->getMutableonrequestText($component, $props)) {
            $ret['text'] = $text;
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
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

    public function initModelProps(array $component, array &$props): void
    {
        if ($blocktarget = $this->getProp($component, $props, 'control-target')) {
            foreach ($this->getSubcomponents($component) as $subComponent) {
                $this->setProp([$subComponent], $props, 'control-target', $blocktarget);
            }
        }

        parent::initModelProps($component, $props);
    }
}

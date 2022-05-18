<?php

abstract class PoP_Module_Processor_ControlsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getLabel(array $module, array &$props)
    {
        return null;
    }
    public function getText(array $module, array &$props)
    {
        return $this->getLabel($module, $props);
    }
    public function getMutableonrequestText(array $module, array &$props)
    {
        return null;
    }
    public function getIcon(array $module)
    {
        return null;
    }
    public function getFontawesome(array $module, array &$props)
    {
        return null;
    }
    public function showTooltip(array $module, array &$props)
    {
        return false;
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        if ($this->getLabel($module, $props) && $this->showTooltip($module, $props)) {
            $this->addJsmethod($ret, 'tooltip');
        }

        return $ret;
    }

    public function getMutableonrequestConfiguration(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($module, $props);

        // If there is a runtime configuration for the text, it overrides the configuration
        if ($text = $this->getMutableonrequestText($module, $props)) {
            $ret['text'] = $text;
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        if ($label = $this->getLabel($module, $props)) {
            $ret['label'] = $label;
        }
        if ($text = $this->getText($module, $props)) {
            $ret['text'] = $text;
        }
        if ($icon = $this->getIcon($module)) {
            $ret['icon'] = $icon;
        }
        if ($fontawesome = $this->getFontawesome($module, $props)) {
            $ret[GD_JS_FONTAWESOME] = $fontawesome;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        if ($blocktarget = $this->getProp($module, $props, 'control-target')) {
            foreach ($this->getSubmodules($module) as $submodule) {
                $this->setProp([$submodule], $props, 'control-target', $blocktarget);
            }
        }

        parent::initModelProps($module, $props);
    }
}

<?php

abstract class PoP_Module_Processor_ControlsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getLabel(array $componentVariation, array &$props)
    {
        return null;
    }
    public function getText(array $componentVariation, array &$props)
    {
        return $this->getLabel($componentVariation, $props);
    }
    public function getMutableonrequestText(array $componentVariation, array &$props)
    {
        return null;
    }
    public function getIcon(array $componentVariation)
    {
        return null;
    }
    public function getFontawesome(array $componentVariation, array &$props)
    {
        return null;
    }
    public function showTooltip(array $componentVariation, array &$props)
    {
        return false;
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        if ($this->getLabel($componentVariation, $props) && $this->showTooltip($componentVariation, $props)) {
            $this->addJsmethod($ret, 'tooltip');
        }

        return $ret;
    }

    public function getMutableonrequestConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($componentVariation, $props);

        // If there is a runtime configuration for the text, it overrides the configuration
        if ($text = $this->getMutableonrequestText($componentVariation, $props)) {
            $ret['text'] = $text;
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        if ($label = $this->getLabel($componentVariation, $props)) {
            $ret['label'] = $label;
        }
        if ($text = $this->getText($componentVariation, $props)) {
            $ret['text'] = $text;
        }
        if ($icon = $this->getIcon($componentVariation)) {
            $ret['icon'] = $icon;
        }
        if ($fontawesome = $this->getFontawesome($componentVariation, $props)) {
            $ret[GD_JS_FONTAWESOME] = $fontawesome;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        if ($blocktarget = $this->getProp($componentVariation, $props, 'control-target')) {
            foreach ($this->getSubComponentVariations($componentVariation) as $subComponentVariation) {
                $this->setProp([$subComponentVariation], $props, 'control-target', $blocktarget);
            }
        }

        parent::initModelProps($componentVariation, $props);
    }
}

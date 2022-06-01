<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\Root\App;

trait DataloadComponentProcessorTrait
{
    use FormattableModuleTrait;

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($filter_component = $this->getFilterSubcomponent($component)) {
            $ret[] = $filter_component;
        }

        if ($inners = $this->getInnerSubcomponents($component)) {
            $ret = array_merge(
                $ret,
                $inners
            );
        }

        return $ret;
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        return array();
    }

    public function getFilterSubcomponent(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\Component\Component
    {
        return null;
    }

    public function metaInitProps(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        /**
         * Allow to add more stuff
         */
        App::doAction(
            Constants::HOOK_DATALOAD_INIT_MODEL_PROPS,
            array(&$props),
            $component,
            $this
        );
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $this->metaInitProps($component, $props);
        parent::initModelProps($component, $props);
    }

    public function startDataloadingSection(\PoP\ComponentModel\Component\Component $component): bool
    {
        return true;
    }
}

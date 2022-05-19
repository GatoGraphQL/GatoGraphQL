<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\Root\App;

trait DataloadComponentProcessorTrait
{
    use FormattableModuleTrait;

    public function getSubcomponents(array $component): array
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

    protected function getInnerSubcomponents(array $component): array
    {
        return array();
    }

    public function getFilterSubcomponent(array $component): ?array
    {
        return null;
    }

    public function metaInitProps(array $component, array &$props)
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

    public function initModelProps(array $component, array &$props): void
    {
        $this->metaInitProps($component, $props);
        parent::initModelProps($component, $props);
    }

    public function startDataloadingSection(array $component): bool
    {
        return true;
    }
}

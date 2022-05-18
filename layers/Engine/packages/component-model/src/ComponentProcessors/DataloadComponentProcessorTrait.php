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

        if ($filter_component = $this->getFilterSubmodule($component)) {
            $ret[] = $filter_component;
        }

        if ($inners = $this->getInnerSubmodules($component)) {
            $ret = array_merge(
                $ret,
                $inners
            );
        }

        return $ret;
    }

    protected function getInnerSubmodules(array $component): array
    {
        return array();
    }

    public function getFilterSubmodule(array $component): ?array
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

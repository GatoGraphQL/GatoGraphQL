<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\Constants\HookNames;
use PoP\Root\App;

trait DataloadComponentProcessorTrait
{
    use FormattableModuleTrait;

    /**
     * @return Component[]
     */
    public function getSubcomponents(Component $component): array
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

    /**
     * @return Component[]
     */
    protected function getInnerSubcomponents(Component $component): array
    {
        return array();
    }

    public function getFilterSubcomponent(Component $component): ?Component
    {
        return null;
    }

    /**
     * @param array<string,mixed> $props
     */
    public function metaInitProps(Component $component, array &$props): void
    {
        /**
         * Allow to add more stuff
         */
        App::doAction(
            HookNames::DATALOAD_INIT_MODEL_PROPS,
            array(&$props),
            $component,
            $this
        );
    }

    /**
     * @param array<string,mixed> $props
     */
    public function initModelProps(Component $component, array &$props): void
    {
        $this->metaInitProps($component, $props);
        parent::initModelProps($component, $props);
    }

    public function startDataloadingSection(Component $component): bool
    {
        return true;
    }
}

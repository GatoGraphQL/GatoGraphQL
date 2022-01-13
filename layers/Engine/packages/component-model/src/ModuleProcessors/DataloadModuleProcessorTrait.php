<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

use PoP\Root\Hooks\HooksAPIInterface;

trait DataloadModuleProcessorTrait
{
    use FormattableModuleTrait;

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        if ($filter_module = $this->getFilterSubmodule($module)) {
            $ret[] = $filter_module;
        }

        if ($inners = $this->getInnerSubmodules($module)) {
            $ret = array_merge(
                $ret,
                $inners
            );
        }

        return $ret;
    }

    protected function getInnerSubmodules(array $module): array
    {
        return array();
    }

    public function getFilterSubmodule(array $module): ?array
    {
        return null;
    }

    public function metaInitProps(array $module, array &$props)
    {
        /**
         * Allow to add more stuff
         */
        \PoP\Root\App::getHookManager()->doAction(
            Constants::HOOK_DATALOAD_INIT_MODEL_PROPS,
            array(&$props),
            $module,
            $this
        );
    }

    public function initModelProps(array $module, array &$props): void
    {
        $this->metaInitProps($module, $props);
        parent::initModelProps($module, $props);
    }

    public function startDataloadingSection(array $module): bool
    {
        return true;
    }
}

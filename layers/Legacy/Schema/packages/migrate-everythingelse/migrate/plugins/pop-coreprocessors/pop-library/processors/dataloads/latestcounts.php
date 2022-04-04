<?php
use PoP\Application\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;

class GD_Core_Module_Processor_Dataloads extends PoP_Module_Processor_DataloadsBase
{
    public final const MODULE_DATALOAD_LATESTCOUNTS = 'dataload-latestcounts';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_LATESTCOUNTS],
        );
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $inner_modules = array(
            self::MODULE_DATALOAD_LATESTCOUNTS => [PoPCore_Module_Processor_Contents::class, PoPCore_Module_Processor_Contents::MODULE_CONTENT_LATESTCOUNTS],
        );

        if ($inner = $inner_modules[$module[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getImmutableDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($module, $props);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_LATESTCOUNTS:
                PoP_Application_SectionUtils::addDataloadqueryargsLatestcounts($ret);
                break;
        }

        return $ret;
    }

    public function getFormat(array $module): ?string
    {

        // Set the display configuration
        $latestcounts = array(
            [self::class, self::MODULE_DATALOAD_LATESTCOUNTS],
        );

        if (in_array($module, $latestcounts)) {
            $format = POP_FORMAT_LATESTCOUNT;
        }

        return $format ?? parent::getFormat($module);
    }

    public function getQueryInputOutputHandler(array $module): ?QueryInputOutputHandlerInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_LATESTCOUNTS:
                return $this->instanceManager->getInstance(ListQueryInputOutputHandler::class);
        }

        return parent::getQueryInputOutputHandler($module);
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_LATESTCOUNTS:
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_LATESTCOUNTS:
                // It can be invisible, nothing to show
                $this->appendProp($module, $props, 'class', 'hidden');

                // Do not load initially. Load only needed when executing the setTimeout with loadLatest
                if (\PoP\Root\App::getState('fetching-site')) {
                    $this->setProp($module, $props, 'skip-data-load', true);
                }
                break;
        }

        parent::initModelProps($module, $props);
    }
}




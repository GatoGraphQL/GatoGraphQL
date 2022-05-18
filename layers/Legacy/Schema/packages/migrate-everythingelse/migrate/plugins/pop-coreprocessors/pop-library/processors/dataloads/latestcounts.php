<?php
use PoP\Application\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;

class GD_Core_Module_Processor_Dataloads extends PoP_Module_Processor_DataloadsBase
{
    public final const MODULE_DATALOAD_LATESTCOUNTS = 'dataload-latestcounts';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_LATESTCOUNTS],
        );
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        $inner_modules = array(
            self::MODULE_DATALOAD_LATESTCOUNTS => [PoPCore_Module_Processor_Contents::class, PoPCore_Module_Processor_Contents::MODULE_CONTENT_LATESTCOUNTS],
        );

        if ($inner = $inner_modules[$componentVariation[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getImmutableDataloadQueryArgs(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_LATESTCOUNTS:
                PoP_Application_SectionUtils::addDataloadqueryargsLatestcounts($ret);
                break;
        }

        return $ret;
    }

    public function getFormat(array $componentVariation): ?string
    {

        // Set the display configuration
        $latestcounts = array(
            [self::class, self::MODULE_DATALOAD_LATESTCOUNTS],
        );

        if (in_array($componentVariation, $latestcounts)) {
            $format = POP_FORMAT_LATESTCOUNT;
        }

        return $format ?? parent::getFormat($componentVariation);
    }

    public function getQueryInputOutputHandler(array $componentVariation): ?QueryInputOutputHandlerInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_LATESTCOUNTS:
                return $this->instanceManager->getInstance(ListQueryInputOutputHandler::class);
        }

        return parent::getQueryInputOutputHandler($componentVariation);
    }

    public function getRelationalTypeResolver(array $componentVariation): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_LATESTCOUNTS:
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_LATESTCOUNTS:
                // It can be invisible, nothing to show
                $this->appendProp($componentVariation, $props, 'class', 'hidden');

                // Do not load initially. Load only needed when executing the setTimeout with loadLatest
                if (\PoP\Root\App::getState('fetching-site')) {
                    $this->setProp($componentVariation, $props, 'skip-data-load', true);
                }
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}




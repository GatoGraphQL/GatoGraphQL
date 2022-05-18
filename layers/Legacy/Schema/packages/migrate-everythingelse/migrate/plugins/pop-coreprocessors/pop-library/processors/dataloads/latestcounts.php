<?php
use PoP\Application\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;

class GD_Core_Module_Processor_Dataloads extends PoP_Module_Processor_DataloadsBase
{
    public final const MODULE_DATALOAD_LATESTCOUNTS = 'dataload-latestcounts';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_LATESTCOUNTS],
        );
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $inner_components = array(
            self::COMPONENT_DATALOAD_LATESTCOUNTS => [PoPCore_Module_Processor_Contents::class, PoPCore_Module_Processor_Contents::COMPONENT_CONTENT_LATESTCOUNTS],
        );

        if ($inner = $inner_components[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getImmutableDataloadQueryArgs(array $component, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_LATESTCOUNTS:
                PoP_Application_SectionUtils::addDataloadqueryargsLatestcounts($ret);
                break;
        }

        return $ret;
    }

    public function getFormat(array $component): ?string
    {

        // Set the display configuration
        $latestcounts = array(
            [self::class, self::COMPONENT_DATALOAD_LATESTCOUNTS],
        );

        if (in_array($component, $latestcounts)) {
            $format = POP_FORMAT_LATESTCOUNT;
        }

        return $format ?? parent::getFormat($component);
    }

    public function getQueryInputOutputHandler(array $component): ?QueryInputOutputHandlerInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_LATESTCOUNTS:
                return $this->instanceManager->getInstance(ListQueryInputOutputHandler::class);
        }

        return parent::getQueryInputOutputHandler($component);
    }

    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_LATESTCOUNTS:
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_LATESTCOUNTS:
                // It can be invisible, nothing to show
                $this->appendProp($component, $props, 'class', 'hidden');

                // Do not load initially. Load only needed when executing the setTimeout with loadLatest
                if (\PoP\Root\App::getState('fetching-site')) {
                    $this->setProp($component, $props, 'skip-data-load', true);
                }
                break;
        }

        parent::initModelProps($component, $props);
    }
}




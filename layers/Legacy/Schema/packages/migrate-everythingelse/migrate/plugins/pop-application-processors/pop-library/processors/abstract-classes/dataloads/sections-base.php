<?php
use PoP\Application\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\Users\Routing\RouteNatures as UserRouteNatures;

abstract class PoP_Module_Processor_SectionDataloadsBase extends PoP_Module_Processor_DataloadsBase
{
    public function getDataloadSource(array $module, array &$props): string
    {
        $ret = parent::getDataloadSource($module, $props);

        // if (\PoP\Root\App::getState('nature') == $this->getNature($module)) {
        if (\PoP\Root\App::getState('nature') == UserRouteNatures::USER) {
            // Allow URE to add the Organization/Community content source attribute
            $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
            $ret = \PoP\Root\App::applyFilters('PoP_Module_Processor_CustomSectionBlocks:getDataloadSource:author', $ret, $author);
        }
        // }

        return $ret;
    }

    protected function getImmutableDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($module, $props);

        // Allow to override the limit by $props (eg: for the Website Features, Filter section)
        if ($limit = $this->getProp($module, $props, 'limit')) {
            $ret['limit'] = $limit;
        } elseif ($format = $this->getFormat($module)) {
            $limits = array(
                POP_FORMAT_SIMPLEVIEW => 6,
                POP_FORMAT_FULLVIEW => 6,
                POP_FORMAT_CAROUSEL => 6,
                POP_FORMAT_CAROUSELCONTENT => 6,
                // If they are horizontal, then bring all the results, until we fix how to place the load more button inside of the horizontal scrolling div
                POP_FORMAT_HORIZONTALMAP => -1,
            );
            if ($limit = $limits[$format] ?? null) {
                $ret['limit'] = $limit;
            }
        }

        // Allow to override the include by $props (eg: for GetPoP Organization Membes demonstration)
        if ($include = $this->getProp($module, $props, 'include')) {
            $ret['include'] = $include;
        }

        return $ret;
    }

    //-------------------------------------------------
    // PROTECTED Functions
    //-------------------------------------------------

    protected function getFeedbackmessageModule(array $module)
    {
        return [PoP_Module_Processor_DomainFeedbackMessages::class, PoP_Module_Processor_DomainFeedbackMessages::MODULE_FEEDBACKMESSAGE_ITEMLIST];
    }

    protected function getFeedbackmessagesPosition(array $module)
    {
        return 'bottom';
    }

    public function getQueryInputOutputHandler(array $module): ?QueryInputOutputHandlerInterface
    {
        return $this->instanceManager->getInstance(ListQueryInputOutputHandler::class);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        if ($inner_module = $this->getInnerSubmodule($module)) {
            $ret[] = $inner_module;
        }

        return $ret;
    }

    public function getInnerSubmodule(array $module)
    {
        return null;
    }

    // public function getModelPropsForDescendantModules(array $module, array &$props): array
    // {
    //     $ret = parent::getModelPropsForDescendantModules($module, $props);

    //     if ($filter_module = $this->getFilterSubmodule($module)) {
    //         $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
    //         $ret['filter-module'] = $filter_module;
    //         // $ret['filter'] = $moduleprocessor_manager->getProcessor($filter_module)->getFilter($filter_module);
    //     }

    //     return $ret;
    // }
}

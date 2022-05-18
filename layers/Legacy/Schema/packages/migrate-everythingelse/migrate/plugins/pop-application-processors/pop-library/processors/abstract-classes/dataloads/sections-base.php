<?php
use PoP\Application\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

abstract class PoP_Module_Processor_SectionDataloadsBase extends PoP_Module_Processor_DataloadsBase
{
    public function getDataloadSource(array $componentVariation, array &$props): string
    {
        $ret = parent::getDataloadSource($componentVariation, $props);

        // if (\PoP\Root\App::getState('nature') == $this->getNature($componentVariation)) {
        if (\PoP\Root\App::getState('nature') == UserRequestNature::USER) {
            // Allow URE to add the Organization/Community content source attribute
            $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
            $ret = \PoP\Root\App::applyFilters('PoP_Module_Processor_CustomSectionBlocks:getDataloadSource:author', $ret, $author);
        }
        // }

        return $ret;
    }

    protected function getImmutableDataloadQueryArgs(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($componentVariation, $props);

        // Allow to override the limit by $props (eg: for the Website Features, Filter section)
        if ($limit = $this->getProp($componentVariation, $props, 'limit')) {
            $ret['limit'] = $limit;
        } elseif ($format = $this->getFormat($componentVariation)) {
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
        if ($include = $this->getProp($componentVariation, $props, 'include')) {
            $ret['include'] = $include;
        }

        return $ret;
    }

    //-------------------------------------------------
    // PROTECTED Functions
    //-------------------------------------------------

    protected function getFeedbackmessageModule(array $componentVariation)
    {
        return [PoP_Module_Processor_DomainFeedbackMessages::class, PoP_Module_Processor_DomainFeedbackMessages::MODULE_FEEDBACKMESSAGE_ITEMLIST];
    }

    protected function getFeedbackmessagesPosition(array $componentVariation)
    {
        return 'bottom';
    }

    public function getQueryInputOutputHandler(array $componentVariation): ?QueryInputOutputHandlerInterface
    {
        return $this->instanceManager->getInstance(ListQueryInputOutputHandler::class);
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        if ($inner_componentVariation = $this->getInnerSubmodule($componentVariation)) {
            $ret[] = $inner_componentVariation;
        }

        return $ret;
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        return null;
    }

    // public function getModelPropsForDescendantComponentVariations(array $componentVariation, array &$props): array
    // {
    //     $ret = parent::getModelPropsForDescendantComponentVariations($componentVariation, $props);

    //     if ($filter_componentVariation = $this->getFilterSubmodule($componentVariation)) {
    //         $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
    //         $ret['filter-module'] = $filter_componentVariation;
    //         // $ret['filter'] = $componentprocessor_manager->getProcessor($filter_componentVariation)->getFilter($filter_componentVariation);
    //     }

    //     return $ret;
    // }
}

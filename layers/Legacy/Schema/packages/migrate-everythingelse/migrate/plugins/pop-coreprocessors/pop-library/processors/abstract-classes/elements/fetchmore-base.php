<?php
use PoP\Application\ModuleProcessors\DataloadingConstants;
use PoP\Application\QueryInputOutputHandlers\Utils;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_FetchMoreBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_FETCHMORE];
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $ret[GD_JS_TITLES]['fetchmore'] = sprintf(
            '%s %s',
            $this->getProp($module, $props, 'loading-spinner'),
            $this->getProp($module, $props, 'fetchmore-msg')
        );
        $ret[GD_JS_TITLES]['loading'] = $this->getProp($module, $props, 'loading-msg');

        $ret['hr'] = $this->getProp($module, $props, 'hr');

        return $ret;
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        // Needed for clicking on 'Retry' when there was a problem in the block
        $this->addJsmethod($ret, 'saveLastClicked');
        $this->addJsmethod($ret, 'fetchMore');
        $this->addJsmethod($ret, 'waypointsFetchMore');

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {

        // $classs = $this->get_general_prop($props, 'btn-submit-class') ? $this->get_general_prop($props, 'btn-submit-class') : 'btn btn-info btn-block';
        $classs = $this->getProp($module, $props, 'btn-submit-class') ?? 'btn btn-info btn-block';
        $this->setProp($module, $props, 'class', $classs);
        $this->setProp($module, $props, 'fetchmore-msg', TranslationAPIFacade::getInstance()->__('Load more', 'pop-coreprocessors'));
        $this->setProp($module, $props, 'loading-msg', GD_CONSTANT_LOADING_MSG);
        $this->appendProp($module, $props, 'class', 'pop-scrollformore');

        // Needed for clicking on 'Retry' when there was a problem in the block
        // $this->appendProp($module, $props, 'class', 'pop-sendrequest-btn');

        // Make infinite by default
        $this->setProp($module, $props, 'infinite', true);
        if ($this->getProp($module, $props, 'infinite')) {
            $this->appendProp($module, $props, 'class', 'waypoint');
        }

        parent::initModelProps($module, $props);
    }

    //-------------------------------------------------
    // Feedback
    //-------------------------------------------------

    public function getDataFeedback(array $module, array &$props, array $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids): array
    {
        $ret = parent::getDataFeedback($module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);
        
        // If it is lazy load, no need to calculate stop-fetching
        // If loading static data, then that's it
        // Do not send this value back when doing loadLatest, or it will mess up the original structure loading
        if (($data_properties[DataloadingConstants::LAZYLOAD] ?? null) || ($data_properties[DataloadingConstants::EXTERNALLOAD] ?? null) || (isset($data_properties[DataloadingConstants::DATASOURCE]) && $data_properties[DataloadingConstants::DATASOURCE] != \PoP\ComponentModel\Constants\DataSources::MUTABLEONREQUEST) || (\PoP\Root\App::getState('loading-latest'))) {
            return $ret;
        }

        // If data is not to be loaded, then "stop-fetching" as to not show the Load More button
        $stopFetching = Utils::stopFetching($dbobjectids, $data_properties);
        $ret['stop-fetching'] = $stopFetching;

        if (!$stopFetching && ($data_properties[DataloadingConstants::SOURCE] ?? null)) {
            $query_args = $data_properties[DataloadingConstants::QUERYARGS];
            $pagenumber = $query_args[\PoP\ComponentModel\Constants\PaginationParams::PAGE_NUMBER];
            $ret['query-next-url'] = GeneralUtils::addQueryArgs([
                \PoP\ComponentModel\Constants\PaginationParams::PAGE_NUMBER => $pagenumber+1,
            ], $data_properties[DataloadingConstants::SOURCE]);
        }

        return $ret;
    }
}

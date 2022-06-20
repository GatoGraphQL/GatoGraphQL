<?php
use PoP\Application\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\ModuleInfo as ComponentModelModuleInfo;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\ComponentProcessors\DataloadingConstants;
use PoP\ComponentModel\State\ApplicationState;

class GD_DataLoad_QueryInputOutputHandler_NotificationList extends ListQueryInputOutputHandler
{
    public function getHistTime(&$query_args)
    {
        if (\PoP\Root\App::hasState('loading-latest') && \PoP\Root\App::getState('loading-latest')) {
            return $query_args[GD_URLPARAM_TIMESTAMP];
        }

        // hist_time: needed so we always query the notifications which happened before hist_time,
        // so that if there were new notification they don't get on the way, and fetching more will not bring again a same record
        if (isset($query_args['hist_time'])) {
            return $query_args['hist_time'];
        }

        // Baseline: return now
        return ComponentModelModuleInfo::get('time');
    }

    public function getHistTimeCompare(&$query_args)
    {
        if (\PoP\Root\App::hasState('loading-latest') && \PoP\Root\App::getState('loading-latest')) {
            return '>';
        }

        if (isset($query_args['hist_time_compare'])) {
            return $query_args['hist_time_compare'];
        }

        return '<=';
    }

    public function prepareQueryArgs(&$query_args)
    {
        parent::prepareQueryArgs($query_args);

        $query_args['hist_time'] = $this->getHistTime($query_args);
        $query_args['hist_time_compare'] = $this->getHistTimeCompare($query_args);
    }

    public function getQueryParams(array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $objectIDOrIDs): array
    {
        $ret = parent::getQueryParams($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDOrIDs);

        // Send the hist_time back only for dynamic pages, so the time does not get cached
        // It will always work fine, since /notifications is mutableonrequestdata, so the first time it is invoked it will get that current time and set it
        if (PoP_UserState_Utils::currentRouteRequiresUserState()) {
            $query_args = $data_properties[DataloadingConstants::QUERYARGS];
            $ret['hist_time'] = $query_args['hist_time'];
        }

        return $ret;
    }
}

/**
 * Initialize
 */
new GD_DataLoad_QueryInputOutputHandler_NotificationList();

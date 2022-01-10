<?php
use PoP\Application\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\ComponentInfo as ComponentModelComponentInfo;
use PoP\ComponentModel\ModuleProcessors\DataloadingConstants;
use PoP\ComponentModel\State\ApplicationState;

class GD_DataLoad_QueryInputOutputHandler_NotificationList extends ListQueryInputOutputHandler
{
    public function getHistTime(&$query_args)
    {
        if (isset(\PoP\Root\App::getState('loading-latest')) && \PoP\Root\App::getState('loading-latest')) {
            return $query_args[GD_URLPARAM_TIMESTAMP];
        }

        // hist_time: needed so we always query the notifications which happened before hist_time,
        // so that if there were new notification they don't get on the way, and fetching more will not bring again a same record
        if (isset($query_args['hist_time'])) {
            return $query_args['hist_time'];
        }

        // Baseline: return now
        return ComponentModelComponentInfo::get('time');
    }

    public function getHistTimeCompare(&$query_args)
    {
        if (isset(\PoP\Root\App::getState('loading-latest')) && \PoP\Root\App::getState('loading-latest')) {
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

    public function getQueryParams($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs): array
    {
        $ret = parent::getQueryParams($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs);

        // Send the hist_time back only for dynamic pages, so the time does not get cached
        // It will always work fine, since /notifications is mutableonrequestdata, so the first time it is invoked it will get that current time and set it
        if (PoP_UserState_Utils::currentRouteRequiresUserState()) {
            $query_args = $data_properties[DataloadingConstants::QUERYARGS];
            $ret['hist_time'] = $query_args['hist_time'];
        }

        return $ret;
    }

    // function getUniquetodomainQuerystate($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids) {

    //     $ret = parent::getUniquetodomainQuerystate($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);

    //     // Send the hist_time back only for dynamic pages, so the time does not get cached
    //     // It will always work fine, since /notifications is mutableonrequestdata, so the first time it is invoked it will get that current time and set it
    //     if (PoP_UserState_Utils::currentRouteRequiresUserState()) {

    //         $query_args = $data_properties[ParamConstants::QUERYARGS];
    //         $ret[ParamConstants::PARAMS]['hist_time'] = $query_args['hist_time'];
    //     }

    //     return $ret;
    // }

    // function getDatafeedback($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids) {

    //     $ret = parent::getDatafeedback($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);

    //     // Send the hist_time back only for dynamic pages, so the time does not get cached
    //     // It will always work fine, since /notifications is mutableonrequestdata, so the first time it is invoked it will get that current time and set it
    //     if (PoP_UserState_Utils::currentRouteRequiresUserState()) {

    //         $query_args = $data_properties[ParamConstants::QUERYARGS];
    //         $ret[ParamConstants::PARAMS]['hist_time'] = $query_args['hist_time'];
    //     }

    //     return $ret;
    // }
}

/**
 * Initialize
 */
new GD_DataLoad_QueryInputOutputHandler_NotificationList();

<?php

use PoP\ComponentModel\ModuleInfo as ComponentModelComponentInfo;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\ModuleProcessors\DataloadingConstants;
use PoP\ComponentModel\QueryInputOutputHandlers\AbstractQueryInputOutputHandler;

class GD_DataLoad_QueryInputOutputHandler_Calendar extends AbstractQueryInputOutputHandler
{
    public function prepareQueryArgs(&$query_args)
    {
        parent::prepareQueryArgs($query_args);

        $today = ComponentModelComponentInfo::get('time');
        $year = $query_args[GD_URLPARAM_YEAR] ? intval($query_args[GD_URLPARAM_YEAR]) : date('Y', $today);
        // Format 'n': do not include leading zeros
        $month = $query_args[GD_URLPARAM_MONTH] ? intval($query_args[GD_URLPARAM_MONTH]) : date('n', $today);

        // For EM to filter, the format must be exactly 'Y-m-d', otherwise it doesn't work!!!
        $from = date('Y-m-01', strtotime($year.'-'.$month.'-01'));
        $to = date('Y-m-t', strtotime($from)); // 't' gives the amount of days in the month

        $query_args['scope'] = array($from, $to);

        // Also give back the day and year, for the JS to execute fullCalendar.gotoDate
        $query_args[GD_URLPARAM_YEAR] = $year;
        $query_args[GD_URLPARAM_MONTH] = $month;

        // Always bring all results
        $query_args[\PoP\ComponentModel\Constants\PaginationParams::LIMIT] = 0;
    }

    public function getQueryParams(array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbObjectIDOrIDs): array
    {
        $ret = parent::getQueryParams($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs);

        $query_args = $data_properties[DataloadingConstants::QUERYARGS];

        // Send back the year / month
        $ret[GD_URLPARAM_YEAR] = $query_args[GD_URLPARAM_YEAR];
        $ret[GD_URLPARAM_MONTH] = $query_args[GD_URLPARAM_MONTH];

        return $ret;
    }
}

/**
 * Initialize
 */
new GD_DataLoad_QueryInputOutputHandler_Calendar();

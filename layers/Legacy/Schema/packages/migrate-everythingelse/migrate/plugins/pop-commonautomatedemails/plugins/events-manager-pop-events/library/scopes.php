<?php
use PoP\ComponentModel\ComponentInfo as ComponentModelComponentInfo;
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

// Add the week scope
// Taken from http://wp-events-plugin.com/tutorials/create-your-own-event-scope/
\PoP\Root\App::getHookManager()->addFilter('em_events_build_sql_conditions', 'popEmAeEventsBuildSqlConditions', 1, 2);
function popEmAeEventsBuildSqlConditions($conditions, $args)
{

       // Somehow the scope could be an array, so `preg_match` below would fail, so make sure it is not an array
    if (!empty($args['scope']) && $args['scope'] == 'week') {
        // $end_date: if doing 7 days, then must produce +6 day, etc
        $start_date = date('Y-m-d', ComponentModelComponentInfo::get('time'));
        $end_date = date('Y-m-d', strtotime("+6 day", ComponentModelComponentInfo::get('time')));
        $conditions['scope'] = " ((event_start_date BETWEEN CAST('$start_date' AS DATE) AND CAST('$end_date' AS DATE)) OR (event_end_date BETWEEN CAST('$start_date' AS DATE) AND CAST('$end_date' AS DATE)))";
    }
    return $conditions;
}
\PoP\Root\App::getHookManager()->addFilter('em_get_scopes', 'popEmAeScopes', 1, 1);
function popEmAeScopes($scopes)
{
    $scopes['week'] = TranslationAPIFacade::getInstance()->__('week', 'poptheme-wassup');
    return $scopes;
}

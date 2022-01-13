<?php
use PoP\ComponentModel\ComponentInfo as ComponentModelComponentInfo;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

// Add the 3-days and 4-days scope
// Taken from http://wp-events-plugin.com/tutorials/create-your-own-event-scope/
HooksAPIFacade::getInstance()->addFilter('em_events_build_sql_conditions', 'popEmRssEventsBuildSqlConditions', 1, 2);
function popEmRssEventsBuildSqlConditions($conditions, $args)
{

       // Somehow the scope could be an array, so `preg_match` below would fail, so make sure it is not an array
    if (!empty($args['scope']) && !is_array($args['scope'])) {
        // Check if it suits the regex, and if so, get how many days
        $regex_pattern = "/^([2-6])-days$/";
        if (preg_match($regex_pattern, $args['scope'], $matches)) {
            $days = $matches[1];

            // $end_date: if doing 2 days, then must produce +1 day, etc
            $start_date = date('Y-m-d', ComponentModelComponentInfo::get('time'));
            $end_date = date('Y-m-d', strtotime(sprintf("+%s day", $days-1), ComponentModelComponentInfo::get('time')));
            $conditions['scope'] = " ((event_start_date BETWEEN CAST('$start_date' AS DATE) AND CAST('$end_date' AS DATE)) OR (event_end_date BETWEEN CAST('$start_date' AS DATE) AND CAST('$end_date' AS DATE)))";
        }
    }
    return $conditions;
}

HooksAPIFacade::getInstance()->addFilter('em_get_scopes', 'popEmRssScopes', 1, 1);
function popEmRssScopes($scopes)
{

    // Add scopes 2-days ... 6-days
    for ($i = 2; $i <= 6; $i++) {
        $scopes[$i.'-days'] = sprintf(
            TranslationAPIFacade::getInstance()->__('%s %s', 'poptheme-wassup'),
            $i,
            TranslationAPIFacade::getInstance()->__('days', 'poptheme-wassup')
        );
    }
    return $scopes;
}

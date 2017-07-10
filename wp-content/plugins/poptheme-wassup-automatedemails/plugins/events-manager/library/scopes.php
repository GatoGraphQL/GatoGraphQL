<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Configure the RSS Feed
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Add the week scope
// Taken from http://wp-events-plugin.com/tutorials/create-your-own-event-scope/
add_filter( 'em_events_build_sql_conditions', 'pop_em_ae_events_build_sql_conditions',1,2);
function pop_em_ae_events_build_sql_conditions($conditions, $args){
    
   	// Somehow the scope could be an array, so `preg_match` below would fail, so make sure it is not an array
    if (!empty($args['scope']) && $args['scope'] == 'week') {

        // $end_date: if doing 7 days, then must produce +6 day, etc
        $start_date = date('Y-m-d', POP_CONSTANT_CURRENTTIMESTAMP/*current_time('timestamp')*/);
        $end_date = date('Y-m-d', strtotime("+6 day", POP_CONSTANT_CURRENTTIMESTAMP/*current_time('timestamp')*/));
        $conditions['scope'] = " ((event_start_date BETWEEN CAST('$start_date' AS DATE) AND CAST('$end_date' AS DATE)) OR (event_end_date BETWEEN CAST('$start_date' AS DATE) AND CAST('$end_date' AS DATE)))";
    }
    return $conditions;
}
add_filter( 'em_get_scopes','pop_em_ae_scopes',1,1);
function pop_em_ae_scopes($scopes){

	$scopes['week'] = __('week', 'poptheme-wassup');
    return $scopes;
}

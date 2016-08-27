<?php 
//define and clean up formats for display
$summary_format = str_replace ( ">", "&gt;", str_replace ( "<", "&lt;", get_option ( 'dbem_ical_description_format' ) ) );
$description_format = str_replace ( ">", "&gt;", str_replace ( "<", "&lt;", get_option ( 'dbem_ical_real_description_format') ) );
$location_format = str_replace ( ">", "&gt;", str_replace ( "<", "&lt;", get_option ( 'dbem_ical_location_format' ) ) );

//figure out limits
$ical_limit = get_option('dbem_ical_limit');
$page_limit = $ical_limit > 50 || !$ical_limit ? 50:$ical_limit; //set a limit of 50 to output at a time, unless overall limit is lower
//get passed on $args and merge with defaults
$args = !empty($args) ? $args:array(); /* @var $args array */
$args = array_merge(array('limit'=>$page_limit, 'page'=>'1', 'owner'=>false, 'orderby'=>'event_start_date', 'scope' => get_option('dbem_ical_scope') ), $args);
$args = apply_filters('em_calendar_template_args',$args);
//get first round of events to show, we'll start adding more via the while loop
$EM_Events = EM_Events::get( $args );

//calendar header
$output = "BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//wp-events-plugin.com//".EM_VERSION."//EN";
echo preg_replace("/([^\r])\n/", "$1\r\n", $output);

//loop through events
$count = 0;
while ( count($EM_Events) > 0 ){
	foreach ( $EM_Events as $EM_Event ) {
		/* @var $EM_Event EM_Event */
	    if( $ical_limit != 0 && $count > $ical_limit ) break; //we've reached our maximum
	    //calculate the times along with timezone offsets
		if($EM_Event->event_all_day){
			$dateStart	= ';VALUE=DATE:'.date('Ymd',$EM_Event->start); //all day
			$dateEnd	= ';VALUE=DATE:'.date('Ymd',$EM_Event->end + 86400); //add one day
		}else{
			$dateStart	= ':'.get_gmt_from_date(date('Y-m-d H:i:s', $EM_Event->start), 'Ymd\THis\Z');
			$dateEnd = ':'.get_gmt_from_date(date('Y-m-d H:i:s', $EM_Event->end), 'Ymd\THis\Z');
		}
		if( !empty($EM_Event->event_date_modified) && $EM_Event->event_date_modified != '0000-00-00 00:00:00' ){
			$dateModified =  get_gmt_from_date($EM_Event->event_date_modified, 'Ymd\THis\Z');
		}else{
		    $dateModified = get_gmt_from_date($EM_Event->post_modified, 'Ymd\THis\Z');
		}
		
		//formats
		$summary = $EM_Event->output($summary_format,'ical');
		$description = $EM_Event->output($description_format,'ical');
		$location = $EM_Event->output($location_format, 'ical');
		
		//create a UID
		$UID = sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
	        // 32 bits for "time_low"
	        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
	        // 16 bits for "time_mid"
	        mt_rand( 0, 0xffff ),
	        // 16 bits for "time_hi_and_version",
	        // four most significant bits holds version number 4
	        mt_rand( 0, 0x0fff ) | 0x4000,
	        // 16 bits, 8 bits for "clk_seq_hi_res",
	        // 8 bits for "clk_seq_low",
	        // two most significant bits holds zero and one for variant DCE1.1
	        mt_rand( 0, 0x3fff ) | 0x8000,
	        // 48 bits for "node"
	        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
	    );
		
//output ical item		
$output = "
BEGIN:VEVENT
UID:{$UID}
DTSTART{$dateStart}
DTEND{$dateEnd}
DTSTAMP:{$dateModified}
SUMMARY:{$summary}";
if( $description ){
    $output .= "
DESCRIPTION:{$description}";
}
$output .= "
LOCATION:{$location}
URL:{$EM_Event->get_permalink()}
END:VEVENT";

		//clean up new lines, rinse and repeat
		echo preg_replace("/([^\r])\n/", "$1\r\n", $output);
		$count++;
	}
	if( $ical_limit != 0 && $count >= $ical_limit ){ 
	    //we've reached our limit, or showing one event only
	    break;
	}else{
	    //get next page of results
	    $args['page']++;
		$EM_Events = EM_Events::get( $args );
	}
}

//calendar footer
$output = "
END:VCALENDAR";
echo preg_replace("/([^\r])\n/", "$1\r\n", $output);